<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'location', 'cost', 'date'
    ];

    protected $casts = [
        "activities" => "array",
    ];

    public static $rules = [
        'name' => 'required',
        'cost' => 'required',
        'location' => 'required|json',
        'activities' => 'required',
        'date' => 'required',
        'image' => 'file',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}