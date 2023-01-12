<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestCategory extends Model
{
    use HasFactory;
    
    protected $table = "interest_categories";

    protected $fillable = ['parent_id', 'name'];
    
    public $timestamps = true;

    function child_category() {
		  return $this->hasMany(InterestCategory::class, 'parent_id','id');
	  }

}
