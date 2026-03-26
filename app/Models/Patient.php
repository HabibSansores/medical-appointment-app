<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model

{

    protected $fillable = [
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact_name',
        'emergency_contact_relationship',
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
}
