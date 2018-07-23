<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 21/07/2018
 * Time: 17:24
 */
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: application/json');

require_once("../Models/Automovel_model.php");
$automovel_model = new Automovel_model();

$resultado_lista = $automovel_model->get_lista_automovel($_GET['categoria'], $_GET['marca'], $_GET['modelo'], $_GET['valor1'], $_GET['valor2'], $_GET['ano1'], $_GET['ano2'], $_GET['cidade'], $_GET['usuario']);

## URL de teste
## http://localhost/seminovosbh/Controllers/ListaController.php?categoria=carro&marca=Toyota&ano1=2011&ano2=2015&preco2=20000

print_r($resultado_lista);
?>