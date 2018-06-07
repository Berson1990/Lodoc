<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\MedicalSpecialties;
use App\Http\Models\Users;
use App\Http\Models\Doctors;
use App\Http\Models\Specializations;
use App\Http\Models\Favourit;
use App\Http\Models\Reservations;
class MedicalSpecialtiesController extends Controller
{
    //
    public function __construct()
    {
        $this->medical_specialties = new MedicalSpecialties();
        $this->users = new Users();
        $this->doctors = new Doctors();
        $this->specializations = new Specializations();
        $this->favourite = new Favourit();
        $this->reservations = new Reservations();
    }

    public function GetallSpcecialties()
    {
        return $this->medical_specialties->all();
    }

    public function GetSpecializations($specializations_id, $type, $user_id)
    {

        return $this->doctors->with(['Users', 'DoctrsSpecializations' => function ($query) use ($specializations_id) {
            $query->with('Specializations')
                ->where('medical_specialties_id', '=', $specializations_id);
        }, 'Favorite' => function ($query) use ($user_id) {
            $query->where($this->favourite->getTable() . '.user_id', $user_id);
        },'Reservations'=>function($query)use($user_id){
            $query->where($this->reservations->getTable() . '.user_id', $user_id);
        }])
            ->join($this->specializations->getTable(), $this->doctors->getTable() . '.doctors_id', $this->specializations->getTable() . '.doctors_id')
            ->where('medical_specialties_id', '=', $specializations_id)
            ->where('type', '=', $type)
            ->get();
    }
}
