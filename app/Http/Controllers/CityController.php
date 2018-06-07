<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\City;
class CityController extends Controller
{
    //
    public function __construct()
    {
        $this->city = new City();
    }

    public function GetCity(){
        return $this->city->all();
    }
}
