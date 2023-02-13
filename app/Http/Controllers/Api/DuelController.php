<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\duel_record;
use App\Models\order_tb;
use App\Models\ticket_support_tb;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Mail;
use Session;
use Validator;
 use Response;
 use Carbon\Carbon;



class DuelController extends Controller
{
	
	function get_duel()
	{    
		$data  = duel_record::all();
		return response()->json(['data' => $data]);
	}


	function add_order(Request $request)
	{    
		

		$validator = Validator::make($request->all(), [
         'user_id_fk' => ['required'],
         'method_id_fk' => ['required'],
         'token_amount' => ['required'],
         'order_status' => ['required'],          
        ]);

      if ($validator->fails()) {
         return self::failure($validator->errors()->first());
        }

    $get_order_data = $request->all();
    $random = rand(100000, 999999);
    $get_order_data['order_no'] = "$random";
    $add_order  = order_tb::create($get_order_data);

       if($add_order) {
           return self::success('Your Order Has Been Created Successfully', ['data' => [
            'msg' => 'Your Order Has Been Created Successfully',
        ]]);

        }
		
	}


	function get_last_order_id(Request $request)
	{

		$validator = Validator::make($request->all(), [
         'id' => ['required'],      
        ]);

          if ($validator->fails()) {
         return self::failure($validator->errors()->first());
        }

		if ($request->id) {
			 

$get_order_data =DB::select('select * from order_tbs where user_id_fk=? and order_status="true" order by order_id DESC limit 1',[$request->id]);



        return self::success('Your Order Has Been Approved Successfully', ['data' => [
            'user' => $get_order_data,
            'status' => true,
            'msg' => 'Your Order Has Been Approved Successfully',
        ]]);

        
		}
		else {
            return self::failure('Yor Order Has Been Failed while API');
        } 
	}



	function create_support_ticket(Request $request)
	{

		$validator = Validator::make($request->all(), [
         // 'ticket_no' => ['required'],
         'user_id_fk' => ['required'],
         'ussue_name' => ['required'],
         'ticket_name' => ['required'],  
         'description' => ['required'],    
        ]);


      if ($validator->fails()) {
         return self::failure($validator->errors()->first());
        }

      $get_order_data = $request->all();
      $random = rand(100000, 999999);
      // $request->['ticket_no'] = $random ;
      $get_order_data['ticket_no'] = "$random";
       $get_order_data['status'] = 'false';

		$create_suppport_ticket  = ticket_support_tb::create($get_order_data);


       if($create_suppport_ticket) {
           return self::success('Ticket Created Successfully', ['data' => [
            'msg' => $create_suppport_ticket,
            'query_status' => true,
        ]]);

        }
        else {
            return self::failure('Yor Order Has Been Failed while API');
        } 
	}




	function get_support_ticket_data(Request $request)
	{

    // return Response::json('data', $request->id);
   
    $user_id = $request->all();


    $current_datetime = Carbon::now();
     $current_datetime->toDayDateTimeString();
     //return response()->json(['data' => $data]);

		if ($user_id) {
			$get_all_ticket_data = ticket_support_tb::where('user_id_fk', '=', $user_id)->get();

       return self::success('Get All Support Ticket Data', ['data' => [
            'user' => $get_all_ticket_data,
             'query_status' => true,
             'time' => $current_datetime,
        ]]);

        //return response()->json(['data' => $get_all_ticket_data]);
		}else {
             return self::failure('Get All Support Ticket Data');
         } 
	  }




  function get_user_orders_data(Request $request)
  {

    // return Response::json('data', $request->id);
   
    $user_id = $request->all();
     //return response()->json(['data' => $data]);

    if ($user_id) {
      $get_user_order_data = order_tb::where('user_id_fk', '=', $user_id)->get();

       return self::success('Get All Users Order Data', ['data' => [
            'user' => $get_user_order_data,
            'query_status' => true,
             
        ]]);

        //return response()->json(['data' => $get_all_ticket_data]);
    }else {
             return self::failure('Get All Users Order Data');
         } 
    }







}
