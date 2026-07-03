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
                    $_SESSION["id"] = $usuario["id"];
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
            '$nome', '$data', '$email', '$tel', '$user', '$senha', 'aluno');"); //inserir
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
        if($dados["comando"] == "cadastrar-turma"){
            $nome = $dados["nome"];
            $desc = $dados["desc"];
            $nomeTratado = $dados["nomeTratado"];
            $nomeTabela = $nomeTratado . "tb";            
            try{
                $comand = $conexao->query("select * from cursos");
                $cursos = $comand->fetchAll(PDO::FETCH_ASSOC);
                foreach($cursos as $curso){
                    if($curso["nome"] == $dados["nome"]){
                        $resposta = ["status" => "falha-nome"];
                    }
                }
                if($resposta["status"] != "falha-nome"){
                    $comando = $conexao->query("insert into cursos(nome, nomeTratado, descricao) values('$nome', '$nomeTratado', '$desc')");
                    $comando2 = $conexao->query("
                        create table $nomeTabela(
                            id int auto_increment primary key,
                            nome varchar(90),
                            identificacao int,
                            nota1 float,
                            nota2 float,
                            nota3 float,
                            nota4 float,
                            mediaFinal float,
                            professor bool default false
                        )");
                        $n = $_SESSION["nomeC"];
                    $comando3 = $conexao->query("insert into $nomeTabela(nome, professor) values('$n', '1')");
                    $resposta = ["status" => "sucesso"];
                }
            }catch(Exception $erro){
                $resposta = ["status" => "falha", "erro" => "$erro"];
            }
        }
        if($dados["comando"] == "cadastrar-se"){
            $curso = $dados["turma"] . "tb";
            $nome = "";
            try{
                $id = $_SESSION["id"];
                $nome = $_SESSION["nomeC"];
                $comando = $conexao->query("insert into $curso(nome, identificacao) values('$nome', '$id')");
                $resposta = ["status" => "sucesso"];
            }catch(Exception $erro){
                $resposta = ["status" => "falha", "erro" => "$erro"];
            }
        }
        if($dados["comando"] == "goCurso"){
            try{
                $curso = $dados["turma"];
                $_SESSION["turma"] = $dados["turma"];
                $resposta = ["status" => "sucesso"];
            } catch(Exception $erro){
                $resposta = ["status" => "falha", "erro" => "$erro"];
            }   
        }
        if($dados["comando"] == "closeCurso"){
            try{
                $_SESSION["turma"] = "";
                $resposta = ["status" => "sucesso"];
            } catch(Exception $erro){
                $resposta = ["status" => "falha", "erro" => "$erro"];
            }
            
        }
    }
    if(ob_get_length()){
        ob_clean(); //necessário para limpar coisas de include e session
    }
    echo json_encode($resposta);
    exit;