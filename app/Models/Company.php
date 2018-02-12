<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = "companies";

    public $fillable = [
        "name", "location", "description"
    ];

    public static $rules = [
        'name' => 'required',
        'location' => 'required|string',
        'phone' => 'required|string',
        'email' => 'string|email',
        'description' => 'required'
    ];


    public $hidden = [];

    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }
}