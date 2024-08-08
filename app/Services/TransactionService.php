<?php
namespace App\Services;

use App\Models\Payment;
use Exception;

class TransactionService
{
    public $total_amount;

    public function pay(Payment $payment)
    {
        $this->total_amount = $this->totalAmount($payment); 
        try{
            //simula tiempo respuesta pago
            sleep(2);
            $rand = mt_rand(1,10);
            return $rand <=7;
        }catch(Exception $e){
            return false;
        }

    }

    private function totalAmount(Payment $payment)
    {
        $total = 0;
        switch ($payment->paymentMethod->slug) {
            case 'pix':
                $discountPercentage = 1.5;
                $total = $payment['value'] * (1 - $discountPercentage / 100);
                break;
            case 'boleto':
                $discountPercentage = 2;
                $total = $payment['value'] * (1 - $discountPercentage / 100);
                break;
            case 'transferencia_bancaria':
                $discountPercentage = 4;
                $total = $payment['value'] * (1 - $discountPercentage / 100);
                break;
        }
        return $total;
    }
}