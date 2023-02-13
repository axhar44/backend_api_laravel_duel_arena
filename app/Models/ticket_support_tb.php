<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticket_support_tb extends Model
{
    use HasFactory;

     protected $fillable = [
		'ticket_no',			
		'user_id_fk',
		'ussue_name',	
		'ticket_name',	
		'description',		
		'status'
    ];

}




