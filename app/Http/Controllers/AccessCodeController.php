<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class AccessCodeController extends Controller
{
    
    public function saveAccessToken(Request $request)
    {
        $user = Auth::user();
        
        DB::table('access_codes')
            ->updateOrInsert(
                ['user_id' => $user->id],
                ['access_code' => $request->access_code, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );

        return response()->json('success');

    }

    public function removeAccessToken(Request $request) {
        $user = Auth::user();

        DB::table('access_codes')
                ->where('user_id', $user->id)
                ->delete();

        return response()->json('deleted');
    }
    
}