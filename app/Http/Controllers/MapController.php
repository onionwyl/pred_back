<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Point;
use App\Map;

class MapController extends Controller
{
    public function initMap(Request $request)
    {
        return;
        $file = fopen("points_distance.csv", 'r');
        $counter = 0;
        $points = [];
        while($data = fgetcsv($file))
        {
            $points[] = $data;
        }
        for($i = 0; $i < 20; $i++)
        {
            for($j = $i+1; $j < 20; $j++)
            {
                if($points[$i][$j] != 0)
                {
                    $mapObj = new Map;
                    $mapObj->src = $i;
                    $mapObj->dst = $j;
                    $mapObj->length = $points[$i][$j];
                    $mapObj->save();
                }
                echo (int)$points[$i][$j]." ";
            }
            echo "<br>";
        }
    }

    public function getMap(Request $request)
    {
        $map = [];
        for($i = 0; $i < 20; $i++)
            for($j = 0; $j < 20; $j++)
            {
                $mapObj = Map::where([
                    'src' => $i,
                    'dst' => $j
                ])->first();
                if($mapObj == NULL)
                    $map[$i][$j] = 0;
                else
                    $map[$i][$j] = $mapObj->length;
            }
        return json_encode($map);
    }
}
