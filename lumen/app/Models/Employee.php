<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 
        'firstname',
        'lastname',
        'email',
        'birthdate',
        'occupation_id'
    ];

    /**
     * The validations for each attribute.
     *
     * @var string[]
     */
    public static $rules = [
        "firstname" => "required|max:30",
        "lastname" => "required|max:30",
        "email" => "required|email|unique:employees,email|max:100",
        "birthdate" => "required|date",
        "occupation_id" => "required|numeric|exists:occupations,id"
    ];

     /**
     * This entity belongs to occupations
     * 
     * @return Occupation
     */
    public function department()
    {
        return $this->belongsTo(Occupation::class);
    }
}
