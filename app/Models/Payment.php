<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "cpf",
        "description",
        "value",
        "status",
        "payment_method_id",
        "payment_date"
    ];

    protected $visible = [
        "name",
        "cpf",
        "description",
        "value",
        "status",
        "payment_date"
    ];
}
