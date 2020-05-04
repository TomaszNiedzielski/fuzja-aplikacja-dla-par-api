<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class BroadcastingController extends Controller
{
    public function broadcastingAuth(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $pusher = new Pusher('4397a9033571317d5522', '3570ecaaf66c6066241e', '962483');

            $user_info = (object) [
                'name' => $user->name
            ];

            $auth = $pusher->presence_auth( $request->channel_name, $request->socket_id, $user->id, $user_info );
            $auth = json_decode($auth);
            return response()->json($auth);
        } else {
            return response()->json('Forbidden.', 403);
        }
    }

}
