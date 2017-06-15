<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function loginAction(Request $request)
    {
        $data = [];
        $input = [];
        if($request->has('code'))
        {
            $input = $request->input();
            $code = $input['code'];
            $appid = "wx864c414a6e820db7";
            $secret = "6cefcffb739117cb5184ae8fc6708bdb";
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";
            $contents = file_get_contents($url); 
            $contents = utf8_encode($contents); 
            $results = json_decode($contents); 
            $openid = $results->openid;
            $userObj = User::where('wechat_openid', $openid)->first();
            if($userObj != NULL && count($userObj) != 0)
            {
                
            }
            else
            {
                $userObj = new User;
                $userObj->username = "onion";
                $userObj->password = Hash::make("123456");
                $userObj->wechat_openid = $openid;
                $userObj->save();
            }
            $data['username'] = $userObj->username;
            return json_encode($data);
        }
    }
}
