<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Doctor extends Model
{
    // use HasFactory;
    public $table = 'doctors';

     protected $dates =[
    'created_at',
    'updated_at',
    ];

    protected $fillable = [
        'name',
        'user_id',
        'image_url',
        'start_time',
        'end_time',
    ];

        public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
