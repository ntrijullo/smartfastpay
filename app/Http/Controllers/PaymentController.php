<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\TransactionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json([
            'status' => 'success',
            'payments' => $payments
        ], 200);
    }

    public function show(Payment $payment)
    {
        if($payment){
            return response()->json([
                'status' => 'success',
                'payment' => $payment
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Not found'
        ], 422);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'cpf' => "required",
            'description' => "required|string|max:200",
            'value' => "required|integer",
            'payment_method' => "required|".Rule::in(["visa", "mastercard"]),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $paymentMethod = PaymentMethod::where("slug", $data['payment_method'])->first();
            if($paymentMethod){
                $user = User::find(Auth::user()->id);
                $payment = Payment::create([
                    "name" => $user->name,
                    "cpf" => $data['cpf'],
                    "description" => $data['description'],
                    "value" => $data['value'],
                    "payment_method_id" => $paymentMethod->id,
                    "status" => "pendiente"
                ]);
                $transaction = New TransactionService;
                $pay = $transaction->pay($payment);
                if($pay){
                    $payment->status = "pagado";
                    $payment->payment_date = Carbon::now();
                    $payment->save();
    
                    $user->balance = $user->balance + $transaction->total_amount;
                    $user->save();
    
                    return response()->json([
                        'status' => 'success',
                        'payment' => $payment
                    ], 200);
                }
                $payment->status = "fallido";
                $payment->save();
    
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error in transaction'
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Please check the information'
            ], 422);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
