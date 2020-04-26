<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Events\RelationshipOfferEvent;
use App\Relationship;

class RelationshipController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // create a connection
        $relation = new Relationship;
        $relation->user_id = $user->id;
        $relation->partner_email = $request->email;
        $relation->save();

        // check id of person who i typed
        $typed_by_me = DB::table('users')
                        ->where('email', $request->email)
                        ->select('id')
                        ->get();

        //check who typed me
        $potential_partner = DB::table('relationships')
                ->where('partner_email', $user->email)
                ->select('user_id')
                ->get();
        if(!$typed_by_me->isEmpty() && !$potential_partner->isEmpty()) {
            if($typed_by_me[0]->id == $potential_partner[0]->user_id) {
                // add partner id to users table
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'partner_id' => $potential_partner[0]->user_id
                    ]);
                // add for partner mine too
                DB::table('users')
                    ->where('id', $potential_partner[0]->user_id)
                    ->update([
                        'partner_id' => $user->id
                    ]);
                
                // get partner data
                $ready_partner = DB::table('users')
                        ->where('id', $potential_partner[0]->user_id)
                        ->select('id', 'name')
                        ->first();

                // run a event
                event(new RelationshipOfferEvent($ready_partner->id, $user->id));

                return response()->json(array('status' => 'ok', 'partner' => $ready_partner));
                
            } else {
                return response()->json(array('status' => 'waiting'));
            }
        } else {
            return response()->json(array('status' => 'waiting'));
        }
    }

    public function checkPartnerData(Request $request)
    {
        $user = Auth::user();
        
        $partner_id = DB::table('users')
                        ->where('id', $user->id)
                        ->select('partner_id')
                        ->get();

        $partner_data = DB::table('users')
                        ->where('id', $partner_id[0]->partner_id)
                        ->select('id', 'name', 'email')
                        ->get();

        if(!$partner_data->isEmpty()) {
            return response()->json(array('partner_data' => $partner_data[0]));
        } else {
            return response()->json('ja pierdole co za szajs');
        }
    }
}
