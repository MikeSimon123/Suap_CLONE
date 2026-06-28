<?php
    session_start();
    require_once "connection.php";
    header("Content-Type: application/json; charset=utf-8");

    $dados = json_decode(file_get_contents("php://input"), true);
    $resposta = ["status"=>"falhou"];

    if($dados){
        if($dados["comando"] == "sair"){
            $_SESSION["login"] = "";
            $_SESSION["id"] = "";
            $resposta = [
                "status" => "sucesso"
            ];
        }
        
        if($dados["comando"] == "entrar"){
            $comando = $conexao->query("select * from usuarios");
            $users = $comando->fetchAll(PDO::FETCH_ASSOC);
            foreach($users as $usuario){
                if($usuario["user"] == $dados["login"] && $usuario["senha"] == $dados["senha"]){
                    $_SESSION["login"] = $dados["login"];
                    $_SESSION["nomeC"] = $usuario["nome"];
                    $_SESSION["func"] = $usuario["func"];
                    $_SESSION["id"] = $usuario["senha"];
                    $resposta = [
                        "status" => "sucesso"
                    ];
                }
            }
            if($resposta == []){
                $resposta = [
                    "status" => "falhou"
                ];
            }
        }
        if($dados["comando"] == "cadastrar"){
            $nome = $dados["nome"];
            $data = (string)$dados["data"];
            $email = $dados["email"];
            $tel = $dados["tel"];
            $user = $dados["user"];
            $senha = $dados["senha"];
            try{
                $comando = $conexao->query("insert into usuarios(
            nome, data, email, tel, user, senha, func) values(
            '$nome', '$data', '$email', '$tel', '$user', '$senha', 'aluno')"); //inserir
                $resposta = [
                    "status" => "sucesso"
                ];
            }catch(Exception $erro){
                $resposta = [
                    "status" => "falha",
                    "erro" => "$erro"
                ];
            }
            
        }
    }
    if(ob_get_length()){
        ob_clean(); //necessário para limpar coisas de include e session
    }
    echo json_encode($resposta);
    exit;