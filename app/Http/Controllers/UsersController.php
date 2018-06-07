<?php
/**
 * Created by PhpStorm.
 * User: mahmoud
 * Date: 6/2/2018
 * Time: 1:41 PM
 */

namespace App\Http\Controllers;

use App\Http\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->users = new Users();

    }

    public function Register($lang)
    {

        $input = Request()->all();
        $rules = array('mail' => 'unique:users,mail',
            'phone' => 'unique:users,phone');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            if ($lang == 'ar') {
                return ['error' => 'هذة العضويه مسجلة لدنيا'];
            } else {
                return ['error' => 'this account is exisit'];

            }


        } else {
            if ($input['password'] != $input['c-password']) {

                if ($lang == 'ar') {
                    return ['error' => 'كلمتى المرور غير متطابقتين'];
                } else {
                    return ['error' => 'The two passwords do not match'];

                }

            } else {
                $input['password'] = md5($input['password']);
                $input['c-password'] = md5($input['c-password']);
                return $this->users->create($input);
            }


        }

    }

    public function Login($lang)
    {

        $input = Request()->all();


        $check = $this->users
            ->where('phone', $input['phone'])
            ->where('password', md5($input['password']))
            ->get();
        if (count($check) > 0) {
            return $check['0'];
        } else {
            if ($lang == 'ar') {
                return ['error' => 'هذة العضويه غير مسجله لدينا'];
            } else {
                return ['error' => 'this account is not registerd'];
            }
        }

    }
}