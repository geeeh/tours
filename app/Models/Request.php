<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = "requests";

    public $fillable = [
        "email", "phone", "location", "description"
    ];

    protected $casts = [
        "location" => "array",
    ];

    public static $rules = [
        'email' => 'required',
        'location' => 'required|string',
        'phone' => 'numeric',
        'description' => 'required'
    ];


    public $hidden = [];
}