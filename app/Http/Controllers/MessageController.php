<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Message;

use App\Events\NewMessageEvent;
use App\Events\MarkMessagesAsReadEvent;
use App\Events\UserIsTypingMessageEvent;

use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;
use Illuminate\Notifications\ChannelManager;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{
    public function load(Request $request)
    {
        $user = Auth::user();

        $take = 20;
        $messages = DB::table('messages')
                    ->where('from', $user->id)
                    ->orWhere('to', $user->id)
                    ->select('id as _id', 'from', 'text', 'image', 'read', 'created_at as createdAt')
                    ->groupBy('id', 'from', 'text', 'image', 'read', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->skip($take * $request->messagesPagination)
                    ->take($take)
                    ->get();

        // add user property to messages
        foreach($messages as $message)
        {
            $message->user = DB::table('users')
                            ->where('users.id', $message->from)
                            ->leftJoin('avatars', 'avatars.user_id', '=', 'users.id')
                            ->select('users.id as _id', 'users.name', 'avatars.avatar_name as avatar')
                            ->groupBy('users.id', 'users.name', 'avatars.avatar_name')
                            ->first();
        }

        // update read
        $affected = DB::table('messages')
            ->where('to', $user->id) //tylko te do mnie
            ->where('read', 0)
            ->update([
                'read' => 1
            ]);

        if($affected > 0) {
            event(new MarkMessagesAsReadEvent($user->partner_id));
        }

        return response($messages);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        $message = new Message;
        $message->from = $user->id;
        $message->to = $user->partner_id;
        $message->text = $request->message;
        $message->save();

        $partner_id = $message->to; // need that to make channel

        $new_message = DB::table('messages')
                    ->where('id', $message->id)
                    ->select('id as _id', 'from', 'text', 'image', 'read', 'created_at as createdAt')
                    ->groupBy('id', 'from', 'text', 'image', 'read', 'created_at')
                    ->first();

        // add user property to messages
        $new_message->user = DB::table('users')
                        ->where('users.id', $new_message->from)
                        ->leftJoin('avatars', 'avatars.user_id', '=', 'users.id')
                        ->select('users.id as _id', 'users.name', 'avatars.avatar_name as avatar')
                        ->groupBy('users.id', 'users.name', 'avatars.avatar_name')
                        ->first();

        // check if partner uses access code
        $access_code = DB::table('access_codes')
                        ->where('user_id', $user->partner_id)
                        ->get();

        if(!$access_code->isEmpty()) {
            $secret_notification = true;
        } else {
            $secret_notification = false;
        }

        // run event
        event(new NewMessageEvent($new_message, $partner_id));

        // new notification
        $partner = User::where('id', $message->to)->get();

        Notification::send($partner, new NewMessageNotification($new_message, $secret_notification));

        return response()->json($partner);
    }

    public function loadUnreadMessages(Request $request)
    {
        $user = Auth::user();        

        $messages = DB::table('messages')
                    ->where('to', $user->id) //tylko te do mnie
                    ->where('read', 0)
                    ->select('id as _id', 'from', 'text', 'image', 'read', 'created_at as createdAt')
                    ->groupBy('id', 'from', 'text', 'image', 'read', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // add user property to messages
        foreach($messages as $message)
        {
            $message->user = DB::table('users')
                            ->where('users.id', $message->from)
                            ->leftJoin('avatars', 'avatars.user_id', '=', 'users.id')
                            ->select('users.id as _id', 'users.name', 'avatars.avatar_name as avatar')
                            ->groupBy('users.id', 'users.name', 'avatars.avatar_name')
                            ->first();
        }

        // update read
        $affected = DB::table('messages')
            ->where('to', $user->id) //tylko te do mnie
            ->where('read', 0)
            ->update([
                'read' => 1
            ]);
        
        if($affected > 0) {
            event(new MarkMessagesAsReadEvent($user->partner_id));
        }

        return response()->json($messages);

    }

    public function markMessagesAsRead(Request $request)
    {
        $user = Auth::user();        
        
        $affected = DB::table('messages')
            ->where('to', $user->id) //tylko te do mnie
            ->where('read', 0)
            ->update([
                'read' => 1
            ]);

        
        if($affected > 0) {
            event(new MarkMessagesAsReadEvent($user->partner_id));
        }

        return response()->json('ok');

    }

    public function sendImage(Request $request)
    {
        $this->validate($request, [
            'photo' => 'image|max:10240',
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
            $path = $request->file('photo')->storeAs('public/chat', $fileNameToStore);
        }
        
        //$api_image = 'http://10.0.2.2:8000/storage/chat/';
        $api_image = 'http://79.137.34.88/storage/chat/';
        
        if(isset($fileNameToStore))
        {
            $message = new Message;
            $message->from = $user->id;
            $message->to = $user->partner_id;
            $message->image = $api_image.$fileNameToStore;
            $message->save();

            $partner_id = $message->to; // need that to make channel            

            $newMessage = DB::table('messages')
                    ->where('id', $message->id)
                    ->select('id as _id', 'from', 'image', 'read', 'created_at as createdAt')
                    ->groupBy('id', 'from', 'image', 'read', 'created_at')
                    ->first();
            
            // add user property to messages
            $newMessage->user = DB::table('users')
                            ->where('users.id', $newMessage->from)
                            ->leftJoin('avatars', 'avatars.user_id', '=', 'users.id')
                            ->select('users.id as _id', 'users.name', 'avatars.avatar_name as avatar')
                            ->groupBy('users.id', 'users.name', 'avatars.avatar_name')
                            ->first();

            event(new NewMessageEvent($newMessage, $partner_id));

            return response()->json($newMessage); //zwroc nazwe spowrotem 
        }
    }

    public function userIsTypingMessage()
    {
        $user = Auth::user();
        
        event(new UserIsTypingMessageEvent($user->partner_id));

        return response()->json('fuck off');
    }
}
