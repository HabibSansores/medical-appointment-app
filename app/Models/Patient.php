<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model

{

    protected $fillable = [
        'blood_type_id',
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        ];
    //Relacion UNO A UNO INVERSA
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //Relacion UNO A UNO
    public function blood_type()
    {
        return $this->belongsTo(BloodType::class);
    }

    //Relacion UNO A MUCHOS
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
