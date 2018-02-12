<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "companies";

    public $fillable = [
        "name", "image", "description"
    ];

    public static $rules = [
        'name' => 'required',
        'image' => 'string|required',
        'description' => 'required'
    ];


    public $hidden = [];
}