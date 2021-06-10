<?php

/**
 * Function is used to send email
*/
function sendEmail($email_data, $constants){

    $email_template = App\Models\EmailTemplates::where(['slug'=>$email_data['slug']])->first();
    
    /** Replace constants to data */
    foreach ($constants as $template_key => $template_value) {
        $email_template->message = str_replace('{'.$template_key.'}', $template_value, $email_template->message);
    }

    /** Send email */
    $data = ['data'=>$email_template->message];
    Mail::send('Emails.default', $data, function($message ) use($email_data,$email_template) {
        $message->to($email_data['email'], 'DEFAULT_APP_NAME')->subject($email_template->subject);
        $message->from('amitkumar@gmail.com','DEFAULT_APP_NAME');
    });

} // endof sendEmail

/**
 * Function use to generate OTP
 */
function generateOTP(){
    return mt_rand(100000,999999);
} // endof generateOTP


/** 
 * upload_img is used to upload a singal image 
 * @param image ;
 * @param upload_path ;
 */
function upload_img($image,$upload_path=''){
	
	if(empty($upload_path)){ $upload_path = public_path('/uplodes/users'); }
	
	$ext = $image->getClientOriginalExtension();
	$name = time().'.'.$ext;
    $destinationPath = $upload_path;
    $image->move($destinationPath, $name);
    return $name;
}