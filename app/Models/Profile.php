<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = "user_profile";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'photoUrl', 'package', 'badge', 'name'
    ];

    public static $rules = [
        'name' => 'required',
        'phone' => 'required',
        'photoUrl' => 'required',
        'about' => 'required',
        'package' =>'required',
        'badge' => 'required'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
