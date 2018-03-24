<?php

namespace App\Http\Controllers;
// use PHPMailerAutoload;
use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\Exception as Exception;

use Illuminate\Http\Request;
 

class MailController  extends Controller {

    public function sendMail(Request $request) {
        $email = $request->json()->get('email');
        $code = $request->json()->get('code');
        $error = 0;

        if($email) {
        try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');

        // $mail->isSMTP(true);

        $mail->SMTPAuth = true;

        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');

        $mail->SMTPSecure = env('MAIL_SMTP_SECURE');
        $mail->Port = env('MAIL_PORT');

        $mail->Subject = "test email";
        $mail->Body = "Your verification code is = " . $code;
        $mail->SetFrom(env('MAIL_USERNAME'));
        $mail->addAddress($email);

        $result = $mail->send();

        if(!$result) {
            $error = 1;
        } 

        }catch(Exception  $e) {
            $error = 1;
        }
        } else {
            $error = 1;  //email not found
        }

        $output = [
            'status' => $error==0 ? 'success': 'error',
            'mailid' => $email
        ];

        return json_encode($output);
    }
}