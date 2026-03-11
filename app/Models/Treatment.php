<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_birth',
        'patient_gender',
        'patient_address',
        'patient_phone',
        'treatment',
        'doctor_name',
        'appointment_at',
        'price',
        'paid_amount',
        'due_amount',
        'status',
        'chief_complain',
        'medical_history',
        'allergies',
        'anesthetic',
        'penicillin',
        'hemophilia',
        'diabetes',
        'hypertension',
        'hepatitis',
        'hiv',
        'heart_attack',
        'angina',
        'bone_disease',
        'pregnant',
    ];
}