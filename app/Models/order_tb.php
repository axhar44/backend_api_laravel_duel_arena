<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_tb extends Model
{
    use HasFactory;

     protected $fillable = [
		'method_id_fk',
		'order_no',
		'user_id_fk',
		'token_amount',	
		'order_status'
        ];

   
}
