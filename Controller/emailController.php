<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
require './Common/templateEmail.php';

class ControllerEmail {

  public function sendEmail($usuario,$filename,$fileExcel) {
    $emailConfig = ModelEmail::getConfigEmail();
    $emailList = ModelEmail::obtenerEmailActivos();
    if($emailConfig == null)
    {
      $data = array("mensaje" => "No existe una configuracion de correo para la aplicacion","statusCode" => 401 );
      echo json_encode($data);
      return;
    }
    else
    {
      $mail = new PHPMailer(true);
      try {
          $mail->CharSet = 'UTF-8';
          $mail->SMTPDebug = 0;// SMTP::DEBUG_SERVER;                      //Enable verbose debug output
          $mail->isSMTP();                                            //Send using SMTP
          $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
          $mail->Username   = $emailConfig[0]["email"];                  //SMTP username
          $mail->Password   = base64_decode($emailConfig[0]["password"]);                             //SMTP password
          $mail->SMTPSecure = 'tls';//PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
          $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

          //Recipients
          $mail->setFrom($emailConfig[0]["email"], 'Tomador de Pedidos');

          foreach($emailList as $lista) {
            $value = $lista["Email"];

            $mail->addAddress($value);     //Add a recipient
            //$mail->addAddress('');               //Name is optional
            $mail->addReplyTo('controladorver@formunica.com', 'Information');
            //$mail->addCC('gabrieljeg2009@hotmail.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('files/Logo.png');         //Add attachments
            $mail->addAttachment('files/'.$filename, 'pedido.pdf');
            $mail->addAttachment('files/'.$fileExcel, 'pedido_Excel.xlsx');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Formunica-Tomador de Pedidos Honduras';
            $mail->Body    = template::getTemplate($usuario); //'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'Este correo a sido generado por sistema';

            $mail->send();

            $mail->clearAddresses();
          }

      } catch (\Exception $e) {
      }
    }
  }

  public function sendEmailByUser($usuario,$email,$filename) {
    $emailConfig = ModelEmail::getConfigEmail();
    $emailList = ModelEmail::obtenerEmailActivos();
    if($emailConfig == null)
    {
      $data = array("mensaje" => "No existe una configuracion de correo para la aplicacion","statusCode" => 401 );
      echo json_encode($data);
      return;
    }
    else
    {
      $mail = new PHPMailer(true);
      try {
          $mail->CharSet = 'UTF-8';
          $mail->SMTPDebug = 0;// SMTP::DEBUG_SERVER;                      //Enable verbose debug output
          $mail->isSMTP();                                            //Send using SMTP
          $mail->Host       = 'smtp.office365.com';                     //Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
          $mail->Username   = $emailConfig[0]["email"];                  //SMTP username
          $mail->Password   = base64_decode($emailConfig[0]["password"]);                             //SMTP password
          $mail->SMTPSecure = 'tls';//PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
          $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

          //Recipients
          $mail->setFrom($emailConfig[0]["email"], 'Tomador de Pedidos');


            $mail->addAddress($email, 'Formunica');     //Add a recipient           //Name is optional
            $mail->addReplyTo('controladorver@formunica.com', 'Information');

            //Attachments
            //$mail->addAttachment('files/Logo.png');         //Add attachments
            $mail->addAttachment('files/'.$filename, 'pedido.pdf');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Formunica-Tomador de Pedidos Honduras';
            $mail->Body    = template::getTemplate($usuario); //'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'Este correo a sido generado por sistema';

            $mail->send();
          

      } 
      catch (\Exception $e) 
      {

      }
    }
  }

  public function postEmailConfig($data) {
    if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/",$data["email"]))
    {
      $data = array(
        "mensaje" => "Correo electrinico invalido",
        "statusCode" => 401
      );

      echo json_encode($data);
      return;
    }
    else
    {
      $email = ModelEmail::registerConfigEmail($data);
      echo $email;
      return;
    }

  }

  public function getEmailLista(){
    $email = ModelEmail::getListEmail();

    if($email==null)
    {
      $data = array("mensaje" => "No existen registros en la base de datos", "statusCode" => 401);

      echo json_encode($data,true);
      return;
    }
    else
    {
      $data = array(
        "items" => $email,
        "statusCode" => 200
      );

      echo json_encode($data,true);
      return;
    }
  }

  public function postEmailLista($data){
    $email = ModelEmail::postListEmail($data);
    echo $email;
    return;
  }

  public function deleteListEmailById($IdEmail) {
    $email = ModelEmail::deleteEmail($IdEmail);
    echo $email;
    return;
  }

  public function putEmailList($IdEmail) {
    $email = ModelEmail::reactiveEmail($IdEmail);
    echo $email;
    return;
  }
}




 ?>
