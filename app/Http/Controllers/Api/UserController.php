<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Mail;
use Session;


class UserController extends Controller
{
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'            
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first());
        }
        // return $request;
        if ( Auth::attempt(['email' => $data['email'], 'password' => $data['password'] ] ) ) {
            $user = Auth::user();

            Session::push('login_detail', $user);
          

  
  /////////////////////////////////////////////// sent otp request////////////////////////////


      $otp = rand(100000, 999999);
     
       $user1 = User::where('email', '=', $data['email'])->update(['otp' => $otp]);

       if ($user1) {

        // foreach ($user_data_get as  $value) {
        //     $email1 = $value['email'];
        // }

        $mail_details = [
            'subject' => 'Gaming Application OTP',
            'body' => 'Your OTP is : ' . $otp,
        ];

       



        Mail::send('mail', $mail_details, function($message) {
            $user_data_get = Session::get('login_detail');
            foreach ($user_data_get as  $value) {
            $email1 = $value['email'];
        }


             $var_email = "azharhussain984347@gmail.com";
           $message->to($email1, 'Dual arena')->subject
           ('Gaming Application OTP Verification');
           $message->from('testdev@logowebtech.com','Verification');
       });



         $token = "XumI5XjJazp87HGb9oVSYc7j0URcZqEEYUjzRH4K";
            return self::success('User login', ['data' => [
                'user' => $user,
                'token' => $token,
            ]]);


        return self::success('User login success', ['data' =>$email1 ]);

        } 
    }

    else {
            return self::failure('User login failed');
        } 
}




 // public function login(Request $request)
 //    {
 //        $data = $request->all();


 //        $code = random_int(100000, 999999);

 //        return response()->json(['data' => $code]);

 //            // return self::success('User login', ['data' => [
 //            //     'Code' => $code,
 //            // ]]);
 //    }




    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return self::failure($validator->errors()->first(), ['data' => []]);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();

        $token = $user->createToken('MyLaravelApp')->accessToken;

        return self::success('User created', ['data' => [
            'user' => $user,
            'token' => $token,
        ]]);
    }

    public function logout(Request $request)
    {
        $data = $request->user()->tokens()->delete();
        return self::success('User logout', ['data' => []]);
    }

    /**
     * details api
     * @return \Illuminate\Http\Response
     */
    public function userDetails()
    {
        $user = Auth::user();
        return self::success('User Detaild', ['data' => [
            'user' => $user,
        ]]);

    }




    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );



            

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status),
            ];

               return response()->json(['data' => $status]);
        }else
        {
            return response()->json(['data' => 'not valid response']);
        }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);
    }




    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message' => 'Password reset successfully',
            ]);
        }

        return response([
            'message' => __($status),
        ], 500);

    }




    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified',
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'verification-link-sent'];
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified',
            ];
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return [
            'message' => 'Email has been verified',
        ];
    }




    public function requestOtp($email)
    {

       $user = Auth::user();

       $otp = rand(100000, 999999);
       $user = User::where('email', '=', $email)->update(['otp' => $otp]);

       if ($user) {

        $mail_details = [
            'subject' => 'Gaming Application OTP',
            'body' => 'Your OTP is : ' . $otp,
        ];
        // Mail::to($request->email)->send(new mail($mail_details));

        Mail::send('mail', $mail_details, function($message) {
           $message->to('testdev@logowebtech.com', 'Dual arena')->subject
           ('Gaming Application OTP Verification');
           $message->from('testdev@logowebtech.com','Verification');
       });

        return self::success('otp send successfully', ['data' => [
            'user' => $user,
            'Otp' => $otp,
            'mail' => $mail_details,
        ]]);

    } else {
        return self::failure("otp unsuccessful");
    }
}










public function verifyOtp(Request $request)
{
    $data = $user = Auth::user();

    //return response()->json(['response' => $data]);
    $user = User::where(['email' => $request->email, 'otp' => $request->otp])->first();
    if ($user) {
        auth()->login($user, true);

        User::where('email', '=', $request->email)->update(['otp' => null]);


          $token = "XumI5XjJazp87HGb9oVSYc7j0URcZqEEYUjzRH4K";
        return self::success('Otp verified', ['data' => [
            'user' => $user,
            'token' => $token,
        ]]);


          // $token = "XumI5XjJazp87HGb9oVSYc7j0URcZqEEYUjzRH4K";
          //   return self::success('User login', ['data' => [
          //       'user' => $user,
          //       'token' => $token,
          //   ]]);


            return Response::json('data', "Otp verified");



    } else {
        return self::failure('Otp failed', ['data' => []]);
    }
}







    public function change_password(Request $request)
    {
    


        $validator = Validator::make($request->all(), [
         'new_password' => ['required'],
         'id' => ['required'],          
         'new_confirm_password' => ['same:new_password'],          
        ]);

        $var_id = $request->id;

      if ($validator->fails()) {
         return self::failure($validator->errors()->first());
        }


     $change_password =  User::find($var_id)->update(['password'=> Hash::make($request->new_password)]);


    if($change_password) {

           return self::success('Yor Password Has Been Updated Successfully', ['data' => [
            'user' => $change_password,
            'msg' => 'Your Password Has Been Updated Successfully',
        ]]);
        }
        
    else{
         return response()->json(['data' => 'API failed']);
        }


        // if($query) {
        //  return response()->json(['data' => 'Yor Password Has Been Updated Successfully']);
        // }
        // else{
        //  return response()->json(['data' => 'API failed']);
        // }



          // $data = $request->all();
          //  return response()->json(['data' => $data]);

        // $var_current_password = $request->current_password;
        // $var_new_password = $request->current_password;
        // $var_new_confirm_password = $request->current_password;

        // if ($var_new_password != $var_new_confirm_password) {
        //     return response()->json(['data' => "Confirm password not match with new password"]);
        // }

        // return response()->json(['data' => "Confirm password not match with new password"]);


        //  if ($var_new_password != $var_new_confirm_password) {
        //     return response()->json(['data' => "Confirm password not match with new password"]);
        // }


         


        // if ($validator->fails()) {
        //  return self::failure($validator->errors()->first());
        // }


        // // return response()->json(['data user' => $data]);
        // $change_password =  User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        // if($change_password) {
        //  return response()->json(['data' => 'Yor Password Has Been Updated Successfully']);
        // }
        // else{
        //  return response()->json(['data' => 'API failed']);
        // }



    }






}


