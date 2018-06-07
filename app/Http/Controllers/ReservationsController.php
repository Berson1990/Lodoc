<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Reservations;
use App\Http\Models\Users;
use App\Http\Models\DoctorsCalendar;
use App\Http\Models\Doctors;
use App\Http\Models\Specializations;
use App\Http\Models\MedicalSpecialties;
use App\Http\Models\Favourit;
use App\Http\Models\Week;

class ReservationsController extends Controller
{
    public function __construct()
    {
        $this->medical_specialties = new MedicalSpecialties();
        $this->users = new Users();
        $this->doctors = new Doctors();
        $this->specializations = new Specializations();
        $this->favourite = new Favourit();
        $this->doctors_calandar = new DoctorsCalendar();
        $this->week = new Week();
        $this->reservations = new Reservations();

    }

    public function GetMyReservationsList($id)
    {
        return $this->reservations
//            ->select('reservations_id')
            ->with([
                'DoctorsCalendar',
                'Doctors'
                => function ($query) {
                    $query->with(
                        'Users',
                        'DoctrsSpecializations.Specializations',
                        'Favorite'
                    );
                }
                ,
                'City.Zone',

            ])
            ->where('user_id', $id)
            ->get();
    }

    public function GetDoctorsCalndars($id)
    {
        return $this->doctors_calandar->with('Week')->where('doctors_id', $id)->get();

    }

    public function CreateReservations()
    {
        $input = Request()->all();

        $sub_services_pic = $input['health_insurance_pic'];
        $image_name = "pic - " . time() . " . png";
        $path = public_path() . "/health_insurance/" . $image_name;
        $input['health_insurance_pic'] = "health_insurance/" . $image_name;
        $voc = substr($sub_services_pic, strpos($sub_services_pic, ",") + 1);//take string after ,
        $voicedata = base64_decode($voc);
        $success = file_put_contents($path, $voicedata);

        return $this->reservations->create($input);
    }

    public function CancelReservations($id)
    {
        $this->reservations->find($id)->delete();
        return ['state' => '202'];
    }
}
