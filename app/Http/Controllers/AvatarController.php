<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Avatar;
use DB;
use Intervention\Image\ImageManagerStatic as Photo;

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

            // resize the image
            $thumbnailpath = public_path('storage/avatars/'.$fileNameToStore);
            $img = Photo::make($thumbnailpath)->resize(80, 80, function($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

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

    public function loadCouplesAvatars(Request $request)
    {
        $user = Auth::user();
        
        $user_avatar = DB::table('avatars')
            ->where('user_id', $user->id)
            ->select('avatar_name as avatarName')
            ->first();

        $partner_avatar = DB::table('avatars')
            ->where('user_id', $user->partner_id)
            ->select('avatar_name as avatarName')
            ->first();

        return response()->json(['userAvatar' => $user_avatar, 'partnerAvatar' => $partner_avatar]);
    }
}
