<?php

return [
    'user' => [
        'login' => [
            'success'   => 'User logged in successfully',
            'error'     => 'Error in log in'
        ],
    ],
    'loan' => [
        'approve' => [
            'success'   => 'Loan has been approved',
            'error'     => 'Loan can not be approved. Try again later'
        ]
    ],
    'installment' => [
        'payment' => [
            'success'   => 'Installment paid successfully',
            'error'     => 'Error in Installment payment',
            'no_inst'   => 'There are no installments to pay. Congratulations!'
        ]
    ]
];
