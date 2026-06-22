<?php
$host = 'localhost';
$db = 'gerenciadordb';
$username = '';
$password = '';
try{
    $conexao = new PDO("mysql:host=$host;dbname=$db",$username,$password);
    $conexao -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo("Conexão realizada");
}
catch (PDOException $erro) {
    echo("Erro:" . $erro -> getMessage());
}
