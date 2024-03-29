<?php
require_once './Common/paginacion.php';

class ControllerPedidos {

  public function PostPedido($data,$detalle){

    $datosPedido = $data;
    //validar que el campo codigo sea numerico
    if(!preg_match("/^[[:digit:]]+$/",$data["codigo"]))
    {

      $json = array(
        "mensaje"=>"El campo codigo solo acepta numeros",
        "statusCode"=>"400"
      );

      echo json_encode($json);
      return;
    }
    //validar que el campo total sea numerico
    else if(!preg_match("/^[[:digit:]]+$/",$data["Banco"]))
    {

      $json = array(
        "mensaje"=>"El campo total solo acepta numeros",
        "statusCode"=>"400"
      );

      echo json_encode($json);
      return;
    }
    //validar que el campo descuento sea numerico
    else if(!preg_match("/^[[:digit:]]+$/",$data["TipoVenta"]))
    {

      $json = array(
        "mensaje"=>"El campo descuento solo acepta numeros",
        "statusCode"=>"400"
      );

      echo json_encode($json);
      return;
    }
    else if(!preg_match("/^[[:digit:]]+$/",$data["numCheque"]))
    {

      $json = array(
        "mensaje"=>"El campo descuento solo acepta numeros",
        "statusCode"=>"400"
      );

      echo json_encode($json);
      return;
    }
    //si se cumplen las validaciones anteriores, se envia la data al modelo
    else {

      $pedidos=ModelPedidos::crearPedidos($data,$detalle);

      $array = json_decode($pedidos,true);
      $email = new ControllerEmail();

      $infoPedido = ModelPedidos::obtenerPedido($array["pedido"]);
      $detailsPedido = ModelPedidos::obtenerDetalles($array["pedido"]);
      $detallePed = ModelPedidos::obtenerDetalles($array["pedido"]);
      $pedido = ModelPedidos::obtenerPedidoAssoc($array["pedido"]);
      $pdf = pdfPedido::generatePDF($array["pedido"],$infoPedido,$detallePed);
      $excel = ExcelPedido::generateExcel($detailsPedido,$pedido);
      $emailUser = ModelusuarioVendedor::getUsuarioVendedorAssoc($data["UsuarioRegistro"]);
      $email -> sendEmail($data["UsuarioRegistro"],$pdf, $excel);
      $correoVendedor = $emailUser[0]["Email"];

      //$email -> sendEmailByUser($data["UsuarioRegistro"],$correoVendedor,$pdf);
      
      echo $pedidos;
      return;
    }

  }

  public function getPedidos($user,$cantidad,$pagina){


    $count = ModelPedidos::countRegPedidos($user);
    $pagination = pagination::paginacion("pedidos",$cantidad,$pagina,$count[0]["totalRegistros"]);
    $pedidos = ModelPedidos::listarPedidos($user,$pagination);
    if($pedidos==null)
    {
      $data = array(
        "mensaje"=>"No existen registros en la base de datos",
        "statusCode"=>404
      );

      echo json_encode($data);
      return;
    }
    else
    {
      $data = array(
        "data"=>$pedidos,
        "statusCode"=>200,
        "paginacion" => $pagination
      );

      echo json_encode($data,true);

      return;
    }
  }

  public function getDetallePedido($IdPedido,$cantidad,$pagina){
    $count = ModelPedidos::countRegDetallePedidos($IdPedido);
    $pagination = pagination::paginacion("detallePedido",$cantidad,$pagina,$count[0]["totalRegistros"]);
    $detallePedidos = ModelPedidos::listarDetallePedido($IdPedido,$pagination);

    if($detallePedidos == null)
    {
      $data = array("mensaje" => "No existen registros en la base de datos", "statusCode" => 404);
      echo json_encode($data,true);
      return;
    }
    else
    {
      $data = array(
        "items" => $detallePedidos,
        "statusCode" => 200,
        "paginacion" => $pagination
      );

      echo json_encode($data,true);
      return;
    }
  }

  public function deletePedido($IdPedido){
    $pedido = ModelPedidos::anularPedido($IdPedido);

    echo $pedido;
    return;
  }

  public function deleteDetallePedido($IdDetallePedido) {
    $detallePedido = ModelPedidos::anularDetallePedido($IdDetallePedido);
    echo $detallePedido;
    return;
  }

}
 ?>
