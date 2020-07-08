<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Gallery;
use DB;
use Illuminate\Support\Facades\Validator;


class GalleryController extends Controller
{
    public function addImage(Request $request)
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
            $path = $request->file('photo')->storeAs('public/gallery', $fileNameToStore);
        }
        
        
        if(isset($fileNameToStore))
        {
            $gallery = new Gallery;
            $gallery->user_id = $user->id;
            $gallery->image_name = $fileNameToStore;
            $gallery->description = $request->description;
            $gallery->save();

            $image = DB::table('galleries')
                            ->where('galleries.id', $gallery->id)
                            ->join('users', 'galleries.user_id', '=', 'users.id')
                            ->select('users.name as user_name', 'galleries.id', 'galleries.image_name', 'galleries.description', 'galleries.created_at')
                            ->groupBy('users.name', 'galleries.id', 'galleries.image_name', 'galleries.description', 'galleries.created_at')
                            ->get();

            return response()->json(['gallery_images' => $image]);
        }
    }

    public function loadGallery(Request $request)
    {
        $user = Auth::user();
        
        $gallery_images = DB::table('galleries')
                        ->whereIn('user_id', [$user->id, $user->partner_id])
                        ->join('users', 'galleries.user_id', '=', 'users.id')
                        ->select('users.name as user_name', 'galleries.id', 'galleries.image_name', 'galleries.description', 'galleries.created_at')
                        ->groupBy('users.name', 'galleries.id', 'galleries.image_name', 'galleries.description', 'galleries.created_at')
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return response()->json(['gallery_images' => $gallery_images]);

    }

    public function deleteImage(Request $request)
    {
        $user = Auth::user();

        DB::table('galleries')
            ->whereIn('user_id', [$user->id, $user->partner_id])            
            ->where('id', $request->imageId)
            ->delete();

        return response()->json("ok", 200);
    }

    public function updateDescription(Request $request) {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:700',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        DB::table('galleries')
            ->where('id', $request->imageId)
            ->whereIn('user_id', [$user->id, $user->partner_id])
            ->update([
                'description' => $request->description
            ]);

        return response()->json('ok');

    }

    public function uploadVideo(Request $request)
    {
        //$this->validate($request, [
            //'photo' => 'video',
        //]);

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
            $path = $request->file('photo')->storeAs('public/gallery', $fileNameToStore);
        }

        return response()->json($fileNameToStore);

    }
}
