<?php
// Skript, dass eine E-Mail mit dem eingefügten Template versendet


// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// Load Composer's autoloader
require '../vendor/autoload.php';




function sendNewsletter($receiver, $content, $header, $token) {
   // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = 2;
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = '*******';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = '******';                     // SMTP username
            $mail->Password   = '******';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 000;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('******', 'Mate App Newsletter');
            $mail->addAddress($receiver);               // Name is optional
            $mail->addReplyTo('******', 'Mate App Support');

            // Unsubscribe Informationen
            $mail->addCustomHeader('List-Unsubscribe: <mailto: ******?subject=unsubscribe>, <http://newsletter.mate-app.de/sites/unsubscribe.php?mail='.$receiver.'&token='.$token.'>');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $header;
            $mail->CharSet = 'UTF-8';

            // Attachements
            $mail->AddEmbeddedImage('../images/header.jpg', 'header');
            $mail->AddEmbeddedImage('../images/instagram.png', 'instagram');
            $mail->AddEmbeddedImage('../images/twitter.png', 'twitter');


            // Place HTML Template here
            $mail->Body    = '<body>
    <!-- Roboto einbinden -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300" rel="stylesheet">
    <style type="text/css">
        * {
            font-family: Roboto, sans-serif;
            font-weight: 100;
            color: black;
            font-size: 16px;
        }
    </style>

    <!-- Table für Positionierung -->
    <table style="table-layout: fixed; width: 900px;">
        <tr>
            <td colspan="8" style="text-align: center;">
                <img style="border-radius: 5px;" src="cid:header" alt="Logo Header">
            </td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="6" style="padding-left: 60px">'.$content.'</td>
            <td></td>
        </tr>
        <tr style="height: 40px">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" style="text-align: center;">
                <a href="https://www.instagram.com/officialmateapp/"><img style="width: 30px; margin-right: 10px" src="cid:instagram" alt="Instagram Logo"></a>
                <a href="https://twitter.com/officialmateapp"><img  style="width: 30px; margin-left: 10px;" src="cid:twitter"></a>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center;">
                <p style="font-size: 22px; color: black !important;">Du willst immer auf dem Laufenden bleiben?</p>
            </td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align: center; ">
                <a style=" color: white; font-size: 22px; font-weight: 300; background-color: #FF9933; padding: 8px 100px; text-decoration: none; border-radius: 5px;" href="">Anmeldung bestätigen</a></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="height: 50px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td style="background-color: #FF9933;" colspan="6"></td>
            <td></td>
        </tr>
        <tr style="height: 20px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="text-align: center; color: #FF9933; font-size: 12px;" colspan="8">Tobias Pörtner &middot; Beispielstraße 1, 12345 Stadt &middot; <a style="text-decoration: none; color: #FF9933; font-size: 12px;" href="tel:+491708275715">+49 170 8275715</a> &middot; <a style="text-decoration: none; color: #FF9933; font-size: 12px;" href="mailto: legal@mate.app.de">legal@mate-app.de</a>&middot;
            <a style="text-decoration: none; color: #FF9933; font-size: 12px;" href="https://mate-app.de/datenschutz">Datenschutz</a></td>
        </tr>
        <tr style="height: 5px;">
            <td colspan="8"></td>
        </tr>
        <tr>
            <td style="text-align: center; color: #FF9933; font-size: 12px;" colspan="8">
                <a 
                    style="text-decoration: none; color: #FF9933; font-size: 12px;" 
                    href="http://newsletter.mate-app.de/sites/unsubscribe.php?mail='.$receiver.'&token='.$token.'"
                >
                    abbestellen
                </a>
            </td>
        </tr>
    </table>    
</body>';
            $mail->AltBody = strip_tags($content);
            $mail->send();
            $mail->clearAllRecipients();
            return '';
        } catch (Exception $e) {
            error_log($mail->ErrorInfo, 3, '../logs/mail_errors.log');
            return $receiver;
        }
} 





?>