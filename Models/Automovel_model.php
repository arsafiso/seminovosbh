<?php
/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 21/07/2018
 * Time: 14:42
 */

require_once("../simple_html_dom.php");

class Automovel_model
{
    public $modelo;
    public $valor;
    public $ano;
    public $km;
    public $combustivel;
    public $n_portas;
    public $cor;
    public $placa;
    public $troca;
    public $codigo;
    public $acessorios;

    function get_automovel($id)
    {
        $automovel_model = new Automovel_model();
        $html = file_get_html('https://www.seminovosbh.com.br/comprar////'.$id);

        // Retorna o nome do Veículo
        foreach ($html->find('div[id=textoBoxVeiculo] > h5') as $a) {
            $veiculo = $a->innertext();
        }

        // Retorna o valor do Veículo
        foreach ($html->find('div[id=textoBoxVeiculo] > p') as $a) {
            $valor = $a->innertext();
        }

        // Retorna os detalhes do veículo
        $i = 0;
        foreach ($html->find('div[id=infDetalhes] > span > ul > li') as $a) {
            $texto = $a->innertext();
            if($i < 5) {
                $detalhes[] = $texto;
            }else if($i == 5 && strpos($texto, 'placa')){
                $placa = $texto;
            }else if($i == 5 && !strpos($texto, 'placa')){
                $placa = null;
                $troca = $texto;
            }else if($i == 6){
                $troca = $texto;
            }
            $i++;
        }

        // Retorna os acessórios do veículo
        foreach ($html->find('div[id=infDetalhes2] > ul > li') as $a) {
            $acessorios[] = $a->innertext();
        }

        // Retorna o código do veículo
        foreach ($html->find('li[class=cod-veiculo]') as $a) {
            $cod = $a->innertext();
            $codigo = trim(substr($cod, 7));
        }

        $automovel_model->modelo = $veiculo;
        $automovel_model->valor = $valor;
        $automovel_model->ano = $detalhes[0];
        $automovel_model->km = $detalhes[1];
        $automovel_model->combustivel = $detalhes[2];
        $automovel_model->n_portas = $detalhes[3];
        $automovel_model->cor = $detalhes[4];
        $automovel_model->placa = $placa;
        $automovel_model->troca = $troca;
        $automovel_model->acessorios = $acessorios;
        $automovel_model->codigo = $codigo;

        return json_encode($automovel_model);
    }

    function get_lista_automovel($categoria = '', $marca = '', $modelo = '', $valor1 = '', $valor2 = '', $ano1 = '', $ano2 = '', $cidade = '', $usuario = '')
    {
        if(isset($categoria) && !empty($categoria)){
            $categoria = $categoria."/";
        }else {
            echo "Escolha uma categoria (Moto/Carro/Caminhão)";
        }
        if(isset($marca) && !empty($marca)){
            $marca = "marca/".$marca."/";
        }
        if(isset($modelo) && !empty($modelo)){
            $modelo = "modelo/".$modelo."/";
        }
        if(isset($valor1) && !empty($valor1)){
            $valor1 = str_replace('.', '', $valor1);
            $valor1 = "valor1/".$valor1."/";
        }
        if(isset($valor2) && !empty($valor2)){
            $valor2 = str_replace('.', '', $valor2);
            $valor2 = "valor2/".$valor2."/";
        }
        if(isset($ano1) && !empty($ano1)){
            $ano1 = "ano1/".$ano1."/";
        }
        if(isset($ano2) && !empty($ano2)){
            $ano2 = "ano2/".$ano2."/";
        }
        if(isset($cidade) && !empty($cidade)){
            $cidade = "cidade/".$cidade."/";
        }
        if(isset($usuario) && !empty($usuario)){
            $usuario = "usuario/".$usuario."/";
        }

        $url_padrao = "https://www.seminovosbh.com.br/resultadobusca/index/veiculo/";
        $url_completa = $url_padrao.$categoria.$marca.$modelo.$valor1.$valor2.$ano1.$ano2.$cidade.$usuario;

        $automovel_model = new Automovel_model();
        $html = file_get_html($url_completa);

        // Monta os nomes dos Veículos
        foreach ($html->find('dd[class=titulo-busca] > a > h4 ') as $a) {
            $conteudo = $a->innertext();
            $pos = strpos($conteudo, '<span');
            $veic = substr($conteudo, 0, $pos);
            $array_veiculo[] = $veic;
        }

        // Monta os valores dos Veículos
        foreach ($html->find('dd[class=titulo-busca] > a > h4 > span') as $a) {
            $conteudo = $a->innertext();
            $pos = strpos($conteudo, '<img');
            if($pos) {
                $valor = substr($conteudo, 0, $pos);
            }else{
                $valor = $conteudo;
            }
            $array_valor[] = $valor;
        }

        $i_detalhes = 0;
        // Monta os detalhes dos Veículos
        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoNitroHomeDesc] > p') as $a) {
            if($i_detalhes > 0 && $i_detalhes % 5 == 0){
                $i_detalhes = 0;
            }
            if($i_detalhes == 0){
                $array_ano[] = $a->innertext();
            }else if($i_detalhes == 1) {
                $array_km[] = $a->innertext();
            }else if($i_detalhes == 2) {
                $array_portas[] = $a->innertext();
            }else if($i_detalhes == 3) {
                $array_cor[] = $a->innertext();
            }else if($i_detalhes == 4) {
                $array_combustivel[] = $a->innertext();
            }
            $i_detalhes++;
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoTurboDesc] > p') as $a) {
            if($i_detalhes > 0 && $i_detalhes % 5 == 0){
                $i_detalhes = 0;
            }
            if($i_detalhes == 0){
                $array_ano[] = $a->innertext();
            }else if($i_detalhes == 1) {
                $array_km[] = $a->innertext();
            }else if($i_detalhes == 2) {
                $array_portas[] = $a->innertext();
            }else if($i_detalhes == 3) {
                $array_cor[] = $a->innertext();
            }else if($i_detalhes == 4) {
                $array_combustivel[] = $a->innertext();
            }
            $i_detalhes++;
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoGratisDesc] > p') as $a) {
            if($i_detalhes > 0 && $i_detalhes % 5 == 0){
                $i_detalhes = 0;
            }
            if($i_detalhes == 0){
                $array_ano[] = $a->innertext();
            }else if($i_detalhes == 1) {
                $array_km[] = $a->innertext();
            }else if($i_detalhes == 2) {
                $array_portas[] = $a->innertext();
            }else if($i_detalhes == 3) {
                $array_cor[] = $a->innertext();
            }else if($i_detalhes == 4) {
                $array_combustivel[] = $a->innertext();
            }
            $i_detalhes++;
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoGratisFreeDesc] > p') as $a) {
            if($i_detalhes > 0 && $i_detalhes % 5 == 0){
                $i_detalhes = 0;
            }
            if($i_detalhes == 0){
                $array_ano[] = $a->innertext();
            }else if($i_detalhes == 1) {
                $array_km[] = $a->innertext();
            }else if($i_detalhes == 2) {
                $array_portas[] = $a->innertext();
            }else if($i_detalhes == 3) {
                $array_cor[] = $a->innertext();
            }else if($i_detalhes == 4) {
                $array_combustivel[] = $a->innertext();
            }
            $i_detalhes++;
        }

        // Retorna se o proprietário aceita troca.
        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoNitroHomeDesc] > span') as $a) {
            $array_troca[] = $a->innertext();
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoTurboDesc] > span') as $a) {
            $array_troca[] = $a->innertext();
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoGratisDesc] > span') as $a) {
            $array_troca[] = $a->innertext();
        }

        foreach ($html->find('div[class=bg-nitro-mais-home] > dd[class=planoGratisFreeDesc] > span') as $a) {
            $array_troca[] = $a->innertext();
        }

        $i_veiculo = 0;
        $i_acessorios = 0;
        //Monta os acessorios dos veículos
        foreach ($html->find('dd[class=list-acessorios] > span') as $a) {
            if($i_acessorios > 0 && $i_acessorios % 6 == 0){
                $i_veiculo++;
            }
            $array_acessorios[$i_veiculo][] = $a->innertext();
            $i_acessorios++;
        }

        $automovel_model->modelo = $array_veiculo;
        $automovel_model->valor = $array_valor;
        $automovel_model->ano = $array_ano;
        $automovel_model->km = $array_km;
        $automovel_model->n_portas = $array_portas;
        $automovel_model->cor = $array_cor;
        $automovel_model->combustivel = $array_combustivel;
        $automovel_model->troca = $array_troca;
        $automovel_model->acessorios = $array_acessorios;

        return json_encode($automovel_model);
    }
}