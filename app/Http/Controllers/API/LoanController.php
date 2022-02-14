<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetLoanRequest;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoanController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getLoan(GetLoanRequest $request){
        $input = $request->all();
        $loanTitle = 'LOAN-APP-'.rand(1111,999999);

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
            $this->approveLoan($loan->id, $loan->user_id);

            $installment = [];
            $installmentAmount = $input['amount']/$input['term'];

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

            Loan::where('id','=',$loan->id)->first()->installments()->insert($installment);

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
}
