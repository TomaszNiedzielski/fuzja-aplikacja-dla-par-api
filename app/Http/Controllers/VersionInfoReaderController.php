<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class VersionInfoReaderController extends Controller
{
    public function getInfo(Request $request)
    {
        $user = Auth::user();
        $info = 'new_version';

        // check if read about version
        $check = DB::table('version_info_readers')
            ->where('user_id', $user->id)
            ->where('version', '1.0.0') // trzeba zmienić za każdym razem !!!!!!!!!!!!!!!!!!!!
            ->get();

        if($check->isEmpty()) {
            DB::table('version_info_readers')
                ->insert([
                    'user_id' => $user->id,
                    'version' => '1.0.0' // trzeba zmienić za każdym razem !!!!!!!!!!!!!!!!!!!!
                ]);
            return response()->json(array('info' => $info));
        } else {
            return response()->json('Info read.');
        }

    }
}
