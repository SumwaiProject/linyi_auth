<?php

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Mail
{

    /**
     * mail -> send
     *
     * @param  string $to      to somebody
     * @param  string $subject mail title
     * @param  string $body    mail content
     * @return bool
     */
    public static function send(string $to = '', string $subject = '', string $body = '')
    {
        $mail = new PHPMailer(true);
    
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = env('smtp.server', 'smtp.qq.com');                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = env('smtp.user', 'sumwai@qq.com');                     //SMTP username
            $mail->Password   = env('smtp.password', '');                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = env('smtp.port', 465);                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet    = PHPMailer::CHARSET_UTF8;
            //Recipients
            $mail->setFrom(env('smtp.user'), env('smtp.name'));
            $mail->addAddress($to);     //Add a recipient
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            // $mail->AltBody = $body;

            $mail->send();
        } catch (Exception $e) {
            return false;
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        return true;
    }
}
