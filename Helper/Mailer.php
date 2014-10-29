<?php
/**
 * Created by PhpStorm.
 * User: GR
 * Date: 21.10.14
 * Time: 17:11
 */

//require_once "class.pop3.php";
//require_once "class.smtp.php";
//require_once "class.phpmailer.php";

require "PHPMailer-master/PHPMailerAutoload.php";


class Mail
{
    public static function Send($address,$subject,$body)
    {
       global $CONFIG;

       $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->Host = $CONFIG["SMTP_HOST"];                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username =$CONFIG["SMTP_USER"];                 // SMTP username
        $mail->Password = $CONFIG["SMTP_PASS"];                           // SMTP password
        $mail->SMTPSecure = $CONFIG["SMTP_SECURE"];                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port =$CONFIG["SMTP_PORT"];                                    // TCP port to connect to

        $mail->From = $CONFIG["MAIL_FROM"];
        $mail->FromName = $CONFIG["MAIL_FROM_NAME"];
        $mail->addAddress($address, 'Information');     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        //$mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;//'Here is the subject';
        $mail->Body    = $body;//'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            return false;
            //return "Message could not be sent.\n Mailer Error: " . $mail->ErrorInfo;
        } else {
            return true;
            //return 'Message has been sent';
        }
    }
}




