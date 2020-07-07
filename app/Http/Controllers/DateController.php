<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Date;

class DateController extends Controller
{
    public function saveDates(Request $request)
    {
        $user = Auth::user();
        
        $date = new Date;
        $date->user_id = $user->id;
        $date->dates = $request->datesInJson;
        $date->save();

        DB::table('dates')
            ->updateOrInsert(
                ['user_id' => $user->id],
                ['dates' => $request->datesInJson, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

        return response()->json("ok");
    }

    public function getDates(Request $request) {
        $user = Auth::user();        
        $datesJson = DB::table('dates')
                    ->where('user_id', $user->id)
                    ->select('dates')
                    ->get();

        return response()->json($datesJson[0]->dates);
    }

}