<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    public function prescriptionFile(){
        return $this->hasMany(PrescriptionFile::class);
    }
}
