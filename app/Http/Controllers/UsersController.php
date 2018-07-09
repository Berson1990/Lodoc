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
                $input['type'] = 1;
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

    public function FaceBookLogin()
    {
        $input = Request()->all();
        $check = $this->users
            ->where('facebook_id', $input['id'])
            ->get();
        if (!empty($check)) {

            return $this->users->create([
                'mail' => $input['email'],
                'facebook_id' => $input['id'],
                'name' => $input['name'],
                'type' => 1
            ]);


        } else {

            return $check;

        }
    }

    public function TwitterLogin()
    {
        $input = Request()->all();
        $check = $this->users
            ->where('twitter_id', $input['id'])
            ->get();
        if (!empty($check)) {

            return $this->users->create([
                'name' => $input['name'],
                'twitter_id' => $input['id'],
                'secret_key' => $input['secret_key'],
                'type' => 1
            ]);


        } else {

            return $check;

        }

    }

    public function InstgramLogin()
    {
        $input = Request()->all();
        $check = $this->users
            ->where('instagram_id', $input['id'])
            ->get();
        if (!empty($check)) {

            return $this->users->create([
                'name' => $input['name'],
                'instagram_id' => $input['id'],
                'type' => 1
            ]);


        } else {

            return $check;

        }

    }

    public function UpdateUserToken($id)
    {
        $input = Request()->all();
        $this->users->find($id)->update(['token_id' => $input['token_id']]);
        return $this->users->where('user_id', $id)->get();
    }
    
    
    function pushAndroid($Token, $Title)
    {

        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $Token;
        $serverKey = 'AAAAqF_qKgA:APA91bEGPPp-9gfkQs7qvOquiOmtOD0zVY7IuDDWtXcPjcHgDkMsNmfwgGLUNRyePswCr_9NTkdijL3tfJDVVabJYVHSuOg7iWGhA_PML9wV4Putr2Qgs-l5p9wFAWow4MGK_hySinvw';
        $title = $Title;
        $body = $title;
        $notification = array('title' => $title, 'text' => $body, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_exec($ch);
//        if ($response === FALSE) {
//            die('FCM Send Error: ' . curl_error($ch));
//        } else {
//            echo $response;
//        }

        curl_close($ch);

    }

}
