<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Prediction;
use App\Map;

class PredictionController extends Controller
{
    public function predict(Request $request)
    {
        $data  = [];
        if($request->method() == "POST")
        {
            $input = $request->input();
            $path = $input['path'];
            $date = $input['date'];
            $pred = 0;
            $time_range = $input['time'];
            for($i = 0; $i < sizeof($path)-1; $i++)
            {
                if($path[$i] < $path[$i+1])
                {
                    $src = $path[$i];
                    $dst = $path[$i+1];
                }
                else
                {
                    $src = $path[$i+1];
                    $dst = $path[$i];
                }
                $mapObj = Map::where([
                    'src' => $src,
                    'dst' => $dst
                ])->first();
                $t = $time_range + floor($pred / 60);
                $id = $mapObj->road_id;
                // echo "$date+$t+$id<br>";
                // return json_encode(["prediction" => "$date+$t+$id"]);
                $predObj = Prediction::where([
                    'date' => $date,
                    'time_range' => $time_range + floor($pred / 60),
                    'road_id' => $mapObj->road_id
                ])->first();
                $pred += $predObj->prediction;
            }
            return json_encode(["prediction" => $pred]);
        }
    }

    public function insertPrediction(Request $request)
    {
        if($request->method() == "POST")
        {
            $input = $request->input();
            $predictions = json_decode($input['data']);
            for($i = 1; $i <= 7; $i++)
            {
                $predictionObj = new Prediction;
                $predictionObj->road_id = $input['road_id'];
                $predictionObj->date = "2017-04-0".$i;
                $predictionObj->time_range = $input['time'];
                $predictionObj->prediction = $predictions[$i-1];
                $predictionObj->save();
            }
            return json_encode(["message" => "success"]);
        }
    }

    public function addWeight(Request $request)
    {
        if($request->method() == "POST")
        {
            $input = $request->input();
            $weight = json_decode($input['data']);
            return json_encode(["message" => $input['data']]);
        }
    }

    public function generatePrediction(Request $request)
    {
        $len = Map::where('road_id', 1)->first()->length;
        $predictionObj = Prediction::all();
        foreach($predictionObj as $prediction)
        {
            $road_id = $prediction->road_id;
            $time_range = $prediction->time_range;
            $date = $prediction->date;
            $pred = $prediction->prediction;
            for($i = 2; $i <= 40; $i++)
            {
                $mapObj = Map::where('road_id', $i)->first();
                $length = $mapObj->length;
                $time = ($length/$len + $this->randomFloat(-0.3, 0.3)) * $pred;
                $predObj = new Prediction;
                $predObj->road_id = $i;
                $predObj->date = $date;
                $predObj->time_range = $time_range;
                $predObj->prediction = $time;
                $predObj->save();
            }
        }
    }
    function randomFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}
