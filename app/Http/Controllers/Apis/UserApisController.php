<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplates;
use App\Models\UnregisteredUsers;
use App\Models\User;
use Validator,Hash,Auth;

class UserApisController extends Controller{
    
    /**
     * Function used to send a invitation link to email
     * @param email
     */
    public function sendInvitationLink(Request $request){
        
        /** validation */
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ],[]);

        if ($validator->fails()) {
            $response = ['status'=>'false','message'=>'Validation errors','errors'=>$validator->errors()];
            return response()->json($response);
        }

        /** Check if user with same email is already exists */
        $check_user_already_exists = User::where(['email'=>$request['email'] , 'is_deleted'=>'0'])->first();
        if(!empty($check_user_already_exists)){
            $response = ['status'=>'false','message'=>'User with same email is already exists'];
            return response()->json($response);
        }

        $invitation = UnregisteredUsers::where(['email'=>$request['email'],'is_deleted'=>'0'])->first();
        if($invitation){
            $invitation->status = '0';
        }else{
            $invitation = new UnregisteredUsers($request->all());
        }

        if($invitation->save()){

            /** Send email 
             * We can also send emails by using queue and jobs
            */
            $emailData = ['slug'=>'signup_invitation_link','email'=>$invitation->email];
            $constents = ['LINK'=>route('signupUsingInvitation',['id'=>$invitation->id])];
            sendEmail($emailData, $constents);

            $response = ['status'=>'true','message'=>'Link sent'];
            return response()->json($response);
        }else{
            $response = ['status'=>'false','message'=>'Something wrong'];
            return response()->json($response);
        }
        // sendEmail
    } // endof sendInvitationLink

    /** 
     * Function is used to signup via email link 
     * @param id (from link)
     * @param username (min:4, max:20)
     * @param password (min:8)
     * */
    public function signupUsingInvitation(Request $request){

        $user_details = UnregisteredUsers::where('id',$request['id'])->first();
        if(empty($user_details)){
            $response = ['status'=>'false','message'=>'Invalid link'];
            return response()->json($response);
        }

        /** Change unregister user status to clicked on link */
        $user_details->status = '1';
        $user_details->save();

        /** validation */
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:4|max:20|unique:users',
            'password' => 'required|min:8'
        ],[]);

        if ($validator->fails()) {
            $response = ['status'=>'false','message'=>'Validation errors','errors'=>$validator->errors()];
            return response()->json($response);
        }

        $otp = generateOTP();

        $request['password'] = Hash::make($request['password']);
        $request['email'] = $user_details->email;
        $request['otp'] = $otp;

        $new_user = new User($request->all());
        if($new_user->save()){

            /** If user delete successfully then delete from unregisterd users */
            $user_details->delete();

            /** Send email 
             * We can also send emails by using queue and jobs
            */
            $emailData = ['slug'=>'otp_verification','email'=>$new_user->email];
            $constents = ['OTP'=>$otp];
            sendEmail($emailData, $constents);

            $response = ['status'=>'true','message'=>'Please check your email for OTP','user_id'=>$new_user->id];
            return response()->json($response);
        }else{
            $response = ['status'=>'false','message'=>'Something wrong'];
            return response()->json($response);
        }
    } // endof signupUsingInvitation

    /**
     * Function to use match OTP received from email
     * @param OTP
     * @param user_id
     */
    public function matchOTP(Request $request){

        /** validation */
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'user_id' => 'required'
        ],[]);

        if ($validator->fails()) {
            $response = ['status'=>'false','message'=>'Validation errors','errors'=>$validator->errors()];
            return response()->json($response);
        }

        $user = User::where('id',$request['user_id'])->first();
        
        if(!empty($user)){

            if($user->otp == $request['otp']){


                $user->otp = NULL;
                $user->is_email_verified = '1';
                $user->save();

                $response = ['status'=>'true','message'=>'Your account verified successfully'];
                return response()->json($response);

            }else{
                $response = ['status'=>'false','message'=>'User not identified'];
                return response()->json($response);
            }

        }else{
            $response = ['status'=>'false','message'=>'User not identified'];
            return response()->json($response);
        }

    } // endof matchOTP

    /** 
     * Function is used to login of user 
     * @param email/username 
     * @param password
     * 
     * NOTE:: In login we can use JWT token and Oauth but here i am using simple method using user_id.
    */
    public function login(Request $request){
        
        /** validation */
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6',
        ],[]);

        if ($validator->fails()) {
            $response = ['status'=>'false','message'=>'Validation errors','errors'=>$validator->errors()];
            return response()->json($response);
        }

        if(filter_var($request['username'], FILTER_VALIDATE_EMAIL)) {
            $attempt_login_details = ['email' =>$request['username'], 'password' => $request['password']];
        } else {
            $attempt_login_details = ['username' =>$request['username'], 'password' => $request['password']];
        }

        if(Auth::attempt($attempt_login_details)){

            $user = Auth::user();
            if($user->is_email_verified != '1'){

                /** 
                 * if user not verified then send otp to verify account  
                 * We can also send emails by using queue and jobs
                */
                $otp = generateOTP();
                $emailData = ['slug'=>'otp_verification','email'=>$new_user->email];
                $constents = ['OTP'=>$otp];
                $user->otp = $otp;
                $user->save();
                sendEmail($emailData, $constents);

                $response = ['status'=>'false','message'=>'Check your email to verify account'];
                return response()->json($response);
            }else{
                Auth::login($user);
                $response = ['status'=>'true','message'=>'Login successfully','user_id'=>$user->id];
                return response()->json($response);
            }

        }else{
            $response = ['status'=>'false','message'=>'Invalid username or passwrod'];
            return response()->json($response);
        }



    } // endof login

    /**
     * Function for update user profile
     * @param image
     * @param name
     * @param user_id
     */
    public function updateProfile(Request $request){

        $user = User::where('id',$request['user_id'])->first();
        if(empty($user)){
            $response = ['status'=>'false','message'=>'User not identified'];
            return response()->json($response); 
        }

        $file = $request->file('image');
        $fileArray = array('image' => $file, 'name'=>$request['name']);

        /** validation */
        $validator = Validator::make($fileArray, [
            'name' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000|dimensions:max_width=256,max_height=256'
        ],[]);

        if ($validator->fails()) {
            $response = ['status'=>'false','message'=>'Validation errors','errors'=>$validator->errors()];
            return response()->json($response);
        }

        $user->avatar = upload_img($file);
        $user->name = $request['name'];
        if( $user->save() ){
            $response = ['status'=>'true','message'=>'Profile updated successfully'];
            return response()->json($response); 
        }else{
            $response = ['status'=>'false','message'=>'Something wrong'];
            return response()->json($response); 
        }

    } // endof updateProfile

}
