<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class DesktopController extends Controller
{
    public function createDesktopBackground(Request $request)
    {
        $this->validate($request, [
            'photo' => 'image',
        ]);

        $user = Auth::user();
       
        if ($request->hasFile('photo')) {
        
            
            // Get filename with the extension
            $fileNameWithExt = $request->file('photo')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('photo')->getClientOriginalExtension();

            // File name to store
            $fileNameToStore = $filename.'_'.time().mt_rand( 0, 0xffff ).'.'.$extension;

            // Upload Image 
            $path = $request->file('photo')->storeAs('public/desktops', $fileNameToStore);
        }
        
        
        if(isset($fileNameToStore))
        {
            DB::table('desktops')->updateOrInsert(
                ['user_id' => $user->id],
                ['desktop_background' => $fileNameToStore]
            );
            return response()->json(array('desktop_background_name' => $fileNameToStore), 200);
        }
    }
    public function getDesktopBackgroundName(Request $request) {
        $user = Auth::user();

        $desktop_background_name = DB::table('desktops')
                                ->where('user_id', $user->id)
                                ->select('desktop_background')
                                ->get();
        if(!$desktop_background_name->isEmpty()) {
            return response()->json(array('desktop_background_name' => $desktop_background_name[0]->desktop_background), 200);
        } else {
            return response()->json("no");
        }
    }
}
