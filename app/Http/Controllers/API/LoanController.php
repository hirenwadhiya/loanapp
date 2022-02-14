<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\GetLoanRequest;
use App\Http\Requests\PayInstallmentRequest;
use App\Models\Loan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoanController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function index(){
        $data = [];
        $user = Auth::id();
        $loans = Loan::where('user_id','=', $user)->get();

        if ($loans->count() > 0){
            $data['loans'] = $loans;
            return $this->sendResponse(__('message.loan.list.success'), $data);
        }

        if ($loans->count() == 0){
            return $this->sendResponse(__('message.loan.list.no_loan'), $data);
        }

        return $this->sendError(__('message.loan.list.error'), Response::HTTP_BAD_REQUEST);
    }

    public function getLoan(GetLoanRequest $request){
        $input = $request->all();

        //random loan name
        $loanTitle = 'LOAN-APP-'.rand(1111,999999);

        //create loan record
        $loan = Loan::create([
            'loan_title'    => $loanTitle,
            'user_id'       => Auth::id(),
            'amount'        => $input['amount'],
            'term'          => $input['term'],
            'start_date'    => now(),
            'end_date'      => now()->addWeeks($input['term']),
            'status'        => 0
        ]);

        if ($loan->id){
            //approve loan
            $this->approveLoan($loan->id, $loan->user_id);

            $installment = [];
            $installmentAmount = $input['amount']/$input['term'];

            //create installment array
            for($i=0; $i<$input['term']; $i++){
                $installment[$i]['user_id'] = Auth::id();
                $installment[$i]['loan_id'] = $loan->id;
                $installment[$i]['installment_number'] = $i+1;
                $installment[$i]['installment_amount'] = $installmentAmount;
                $installment[$i]['installment_date'] = now()->addWeeks($i+1);
                $installment[$i]['payment_status'] = 0;
                $installment[$i]['created_at'] = now();
                $installment[$i]['updated_at'] = now();
            }
            $loan->installments()->insert($installment);

            //create relational record for loan ledger
            $loan->ledger()->create([
                'loan_id'           => $loan->id,
                'paid_amount'       => 0,
                'remaining_amount'  => $input['amount'],
                'paid_installments' => 0,
                'remaining_installments' => $input['term']
            ]);

            $data = Loan::with('user')->where('id','=',$loan->id)->first();
            return $this->sendResponse(__('message.loan.approve.success'),$data);
        }
        return $this->sendError($request->errors,Response::HTTP_BAD_REQUEST);
    }

    public function approveLoan($loanId, $userId){
        $updateLoan = Loan::where('id','=',$loanId)
            ->where('user_id','=',$userId)
            ->update(['status' => 1]);
    }

    public function payLoanInstallment(PayInstallmentRequest $request){
        $input = $request->only('loan_title');
        $loan = Loan::with('installments','ledger')
            ->where('loan_title','=',$input['loan_title'])
            ->first();

        if ($loan != null){
            //find first unpaid installment
            $installmentToBePaid = $loan->installments
                ->where('payment_status','=',0)
                ->first();

            if ($installmentToBePaid != null){
                //update status of installment
                $installmentToBePaid->update(['payment_status' => 1]);

                $installmentAmount      = $installmentToBePaid->installment_amount;
                $paidAmount             = $loan->ledger->paid_amount;
                $remainingAmount        = $loan->ledger->remaining_amount;
                $paidInstallments       = $loan->ledger->paid_installments;
                $remainingInstallments  = $loan->ledger->remaining_installments;

                //update ledger
                $updateLedger = $loan->ledger->update([
                    'paid_amount'           => $paidAmount + $installmentAmount,
                    'remaining_amount'      => $remainingAmount - $installmentAmount,
                    'paid_installments'     => $paidInstallments + 1,
                    'remaining_installments'=> $remainingInstallments - 1
                ]);

                return $this->sendResponse(__('message.installment.payment.success'), $installmentToBePaid);
            }

            /*
             * update loan status if all installments are paid
             * loan status 0 = pending approval
             * loan status 1 = approved
             * loan status 2 = all installment paid
             */
            $loan->update(['status' => 2]);
            return $this->sendError(__('message.installment.payment.no_inst'), Response::HTTP_BAD_REQUEST);
        }
    }
}
