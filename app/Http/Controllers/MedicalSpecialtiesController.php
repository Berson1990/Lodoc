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

    public function GetSpecializations($specializations_id, $type, $user_id, $city_id, $zone_id)
    {

        $output = $this->doctors
            ->withCount('Reservations')
            ->with(['Users', 'DoctrsSpecializations' => function ($query) use ($specializations_id) {
            $query->with('Specializations')
                ->where('medical_specialties_id', '=', $specializations_id);
        }, 'Favorite' => function ($query) use ($user_id) {
            $query->where($this->favourite->getTable() . '.user_id', $user_id);
        }, 'Reservations' => function ($query) use ($user_id) {
            $query->where($this->reservations->getTable() . '.user_id', $user_id);
        }])
            ->join($this->specializations->getTable(), $this->doctors->getTable() . '.doctors_id', $this->specializations->getTable() . '.doctors_id')
            ->where('medical_specialties_id', '=', $specializations_id)
            ->where('type', '=', $type);
        if (!empty($city_id)) {
            $output = $output->where($this->doctors->getTable() . '.city_id', $city_id);
        }

        if (!empty($zone_id)) {
            $output = $output->where($this->doctors->getTable() . '.zone_id', $zone_id);
        }
        $output = $output->get();

        return $output;

    }

    public function Search($key_word, $user_id, $type, $city_id, $zone_id)
    {

        $output = $this->doctors
            ->withCount('Reservations')
            ->with(['Users' => function ($query) use ($key_word) {

            },
                'DoctrsSpecializations' => function ($query) use ($key_word) {
                    $query->with(['Specializations' => function ($query) use ($key_word) {

//                    $query->where('medical_specialties_ar', '=', $key_word)
//                        ->where('medical_specialties_en', '=', $key_word);
                    }]);

                }, 'Favorite' => function ($query) use ($user_id) {
                    $query->where($this->favourite->getTable() . '.user_id', $user_id);
                }, 'Reservations' => function ($query) use ($user_id) {
                    $query->where($this->reservations->getTable() . '.user_id', $user_id);
                }])
            ->leftjoin($this->users->getTable(), $this->doctors->getTable() . '.user_id', $this->users->getTable() . '.user_id')
            ->leftjoin($this->specializations->getTable(), $this->doctors->getTable() . '.doctors_id', $this->specializations->getTable() . '.doctors_id')
            ->where($this->users->getTable() . '.name', 'Like', '%' . $key_word);
        if (!empty($type)) {
            $output = $output->where($this->doctors->getTable() . '.type', '=', $type);
        }

        if (!empty($city_id)) {
            $output = $output->where($this->doctors->getTable() . '.city_id', $city_id);
        }

        if (!empty($zone_id)) {
            $output = $output->where($this->doctors->getTable() . '.zone_id', $zone_id);
        }
        $output = $output->get();

        return $output;
    }


}
