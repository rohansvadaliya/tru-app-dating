<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'question_answers';
    
    protected $fillable = [
        'id',
        'user_id',
        'question_id',
        'answer',
    ];

    

}
