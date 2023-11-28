<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $table = 'appointments';
    public $fillable = [
        'appointment_code',
        'user_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'status',
        'no_queue',
        'type_appointment',
    ];
    
    protected $dates =[
    'created_at',
    'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
