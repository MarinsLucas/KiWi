<?php

$usuario = 'root';
$psw = '';
$database = 'login_kiwi';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $psw, $database);

if($mysqli->error){
    die("Falha ao conectar ao banco de dados: "  . $mysqli->error);
}

?>