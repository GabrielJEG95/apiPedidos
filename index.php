<?php
require_once 'Controller/route.controller.php';
require_once 'Controller/clienteController.php';
require_once 'Controller/productoController.php';
require_once 'Controller/vendedorController.php';
require_once 'Controller/pedidosController.php';
require_once 'Controller/bancoController.php';
require_once 'Controller/formapagoController.php';
require_once 'Controller/tipoventaController.php';
require_once 'Controller/vendedorController.php';
require_once 'Models/clienteModel.php';
require_once 'Models/pedidosModel.php';
require_once 'Models/bancoModel.php';
require_once 'Models/formapagoModel.php';
require_once 'Models/tipoventaModel.php';
require_once 'Models/vendedorModel.php';

$rutas = new ControllerRoute();

$rutas->inicio();
