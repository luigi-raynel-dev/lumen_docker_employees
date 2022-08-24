<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 
        'name'
    ];

    /**
     * The validations for each attribute.
     *
     * @var string[]
     */
    public static $rules = [
        "name" => "required|unique:departments,name|max:50"
    ];
}
