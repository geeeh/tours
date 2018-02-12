<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";

    public $fillable = [
        "name", "type", "description"
    ];

    public static $rules = [
        'name' => 'required',
        'type' => 'in:LOCATION,ACTIVITY',
        'description' => 'required'
    ];


    public $hidden = [];
}