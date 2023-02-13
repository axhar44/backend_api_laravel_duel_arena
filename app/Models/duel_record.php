<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class duel_record extends Model
{
     use HasFactory;

    protected $fillable = [
    'streak',
    'name',
    'level',
    'image',
];
}
