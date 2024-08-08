<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = collect([
            [
                "name" => "Pix",
                "slug" => "pix"
            ],
            [
                "name" => "Boleto",
                "slug" => "boleto"
            ],
            [
                "name" => "Transferencia Bancaria",
                "slug" => "transferencia_bancaria"
            ],
        ]);

        $paymentMethods->each(function($item, $key){
            PaymentMethod::create($item);
        });
    }
}
