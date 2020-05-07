<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Avatar;
use DB;

class AvatarController extends Controller
{
    public function create(Request $request)
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
            $path = $request->file('photo')->storeAs('public/avatars', $fileNameToStore);
        }
        
        //$api_image = 'http://10.0.2.2:8000/storage/avatars/';
        $api_image = 'http://79.137.34.88/storage/avatars/';
        
        if(isset($fileNameToStore))
        {
            // delete last avatar
            DB::table('avatars')
                ->where('user_id', $user->id)
                ->delete();

            // create new avatar
            $avatar = new Avatar;
            $avatar->user_id = $user->id;
            $avatar->avatar_name = $api_image.$fileNameToStore;
            $avatar->save();

            return response()->json(['avatar' => $fileNameToStore]);
        }
    }
}
