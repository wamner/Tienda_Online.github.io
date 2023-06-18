<?php


use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};


require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  //SMTP:: DEBUG_OFF;               
    $mail->isSMTP();                                            //Send using SMTP

    $mail->Host       = 'wamnerluna@gmail.com';  // Agregar el correo del dominio            

    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'wamnerluna@outlook.com';                     
    $mail->Password   = '';                             
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
    $mail->Port       = 465;  // use 587  `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('wamnerluna@outlook.com', 'TIENDA CDP');
    $mail->addAddress('wamnerluna@outlook.com', 'Joe User');    


    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Detalle de su compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo.= '<p> El ID de su compra es <b>'. $id_transaccion .'</b></p>';


    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra.';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el correo electronico de la compra: {$mail->ErrorInfo}";
    //exit;
}