<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Testcase;
use App\User;
use App\Map;

class TestcaseController extends Controller
{
    public function submitTestcase(Request $request)
    {
        if($request->method() == "POST")
        {
            $input = $request->input();
            $username = $input['username'];
            $userObj = User::where('username', $username)->first();
            $uid = $userObj->uid;
            $testcaseObj = new Testcase;
            $testcaseObj->uid == $uid;
            $path = $input['path'];
            if($path[0] < $path[1])
            {
                $src = $path[0];
                $dst = $path[1];
            }
            else
            {
                $src = $path[1];
                $dst = $path[0];
            }
            $mapObj = Map::where([
                    'src' => $src,
                    'dst' => $dst
                ])->first();
            $testcaseObj->road_id = $mapObj->road_id;
            $testcaseObj->date = $input['date'];
            $testcaseObj->time_range = $input['time'];
            $testcaseObj->prediction = $input['pred'];
            var_dump($input['pred']);
            $testcaseObj->save();
            return json_encode(['message' => 'success']);
        }
    }
}
