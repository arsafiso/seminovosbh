<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 21/07/2018
 * Time: 15:19
 */
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: application/json');

require_once("../Models/Automovel_model.php");
$automovel_model = new Automovel_model();

$resultado_veiculo = $automovel_model->get_automovel($_GET['id']);

## URL de teste
## http://localhost/seminovosbh/Controllers/AutomovelController.php?id=2131275

print_r($resultado_veiculo);
?>