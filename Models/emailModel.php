<?php
require_once 'conexion.php';

class ModelEmail {
  static public function registerConfigEmail($data) {
    $stmt= BD::conexion()->prepare("INSERT INTO configuracionEmail (email,UsuarioRegistro,password) values(:email, :usuario, :password)");
    //$password = base64_encode($data["password"]);

    $stmt -> bindParam(":email",$data["email"],PDO::PARAM_STR);
    $stmt -> bindParam(":usuario",$data["usuario"],PDO::PARAM_STR);
    $stmt -> bindParam(":password",$data["password"],PDO::PARAM_STR);

    if($stmt -> execute())
    {
      $data = array(
        "mensaje" => "Registro completado con exito",
        "statusCode" => 200
      );

      return json_encode($data);
    }
    else
    {
      $data = array(
        "mensaje" => "Erro al completar el registro",
        "statusCode" => 400
      );

      return json_encode($data);
    }

    $stmt->close();
    $stmt = null;

  }

  static public function getConfigEmail(){
    $stmt = BD::conexion()->prepare("SELECT top 1 * FROM configuracionEmail where status = 1");
    $stmt -> execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->close();
    $stmt=null;
  }

  
}




 ?>