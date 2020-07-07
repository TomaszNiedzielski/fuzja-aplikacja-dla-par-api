<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class PaymentController extends Controller
{
    public function checkStatusOfPayment(Request $request)
    {
        //$user = Auth::user();
        /*$user = new \stdClass();
        $user->id = 1;

        // check if user is in payments table
        // if not check when he created an account
        // and if account is older than one month return response you need to pay
        // if isset in payments and end of subsciption is older than now return response you need to pay

        $payments = DB::table('payments')
                    ->where('user_id', $user->id)
                    ->get();

        if(!$payments->isEmpty()) {
            // if he is in payments
            if($payments[0]->end_of_subscription < date('Y-m-d')) { // session expired
                return response()->json(['status' => 'pay']);
            }
        } else {
            // this user is not in payments table
            // so check how long account isset
            $how_long_account_isset = DB::table('users')
                    ->where('id', $user->id)
                    ->select('created_at')
                    ->get();

            $t1 = Carbon::parse($how_long_account_isset[0]->created_at);
            $t2 = Carbon::now();
            $diff = $t1->diff($t2);

            if($diff->days > 30) {
                // if account is older than 30 days
                return response()->json(['status' => 'pay']);
            } else {
                // account has not 30 days
                return response()->json(['status' => 'ok']);                
            }

            

            
            
        }

        //$date = Carbon::now()->addMonths(2);

        //return $date->toDateTimeString();

        // if user has account longer than month
        //return response()->json(['status' => 'pay']);

        */
        return response()->json('debesta');
    }
}
