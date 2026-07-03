<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        include "php/verify.php";
    ?>
    <?php
        if(!array_key_exists("turma", $_SESSION)){
            echo "<script>window.location = 'cursos.php'</script>";
        } else if(array_key_exists("turma", $_SESSION)){
            if($_SESSION["turma"] == ""){
                echo "<script>window.location = 'cursos.php'</script>";
            }
        }
    ?>
    <title>Atividades</title>
</head>
<body>
    <section id="menu_lateral">
        <?php
            include_once "php/menu.php";
        ?>
    </section>
    <h1><?php 
        if(array_key_exists("turma", $_SESSION)){
            echo "Atividades de " . $_SESSION["turma"];
        } else {
            echo "Não tem ";
        }
        ?>
    </h1>
    
    <?php 
        if($_SESSION["func"] == "aluno"){
            echo "<a href='atividades.php'>Atividades</a>";
            echo "<a href='materias.php'>Materiais</a>";
            echo "<a href='progresso.php'>Progresso</a>";
            echo "<h2>Atividades</h2>";
        }
        if($_SESSION["func"] == "professor"){
            echo "<a href='atividades.php'>Atividades</a>";
            echo "<a href='materias.php'>Materiais</a>";
            echo "<a href='progresso.php'>Progresso</a>";
            echo "<h2>Atividades</h2>";
        }
    ?>
    <section id='atividadesTabela'></section>
    <?php if($_SESSION["func"] == "professor"){echo "<input type='button' value='Criar Atividade' onclick=criarAtividade()>";}?>
    <section id="criadorAtividade">
        <h2>Crie uma atividade</h2>
        <form action="" method="post" id='formCriadorAtividade'>
            <p>Nome da atividade</p>
            <input type="text" name="cxnome" id="nomeAtividade">
            <p>OBSERVAÇÃO: você pode editar a atividade após criá-la</p>
            <input type="submit" value="Criar Atividade">
        </form>
    </section>
    <script>
        /* Criador de Atividades*/
        const criadorAtividade = document.getElementById("criadorAtividade");
        const formCriadorAtividade = document.getElementById("formCriadorAtividade");
        const nomeAtividade = document.getElementById("nomeAtividade");
        const atividadesTabela = document.getElementById("atividadesTabela");
        criadorAtividade.style.display = "none";
        function criarAtividade(){
            criadorAtividade.style.display = "block";
        }
        function atualizarTabela(){
            const dados = {
                comando: "atualizarAtividades",
                tabela: "<?php echo $_SESSION['nomeTratado']?>"
            }
            fetch("php/acoes.php", {
                method:"post",
                headers:{
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dados)
            })
            .then(response => response.json())
            .then(dado => {
                atividadesTabela.innerHTML = "";
                if(dado["status"] == "falha"){
                    atividadesTabela.innerHTMl += "<p>Não foi possível acessar as atividades</p>"
                }
                if(dado["status"] == "sucesso"){
                    atividades = JSON.parse(dado["atividades"]);
                    atividades.forEach(atividade => {
                        atividadesTabela.innerHTML += "ATIVIDADE: " + atividade["nome"] + "<br>";
                    });
                    if(atividades == []){
                        atividadesTabela.innerHTML += "Não existem atividades do curso ainda";
                    }
                }
            })
        }
        atualizarTabela();
        formCriadorAtividade.addEventListener("submit", e => {
            e.preventDefault();
            if(nomeAtividade.value != ""){
                const dados = {
                    comando: "criarAtividade",
                    nome: nomeAtividade.value,
                    tabela: "<?php echo $_SESSION['nomeTratado']?>"
                }
                fetch("php/acoes.php", {
                    method: "post",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(dados)
                })
                .then(response => response.json())
                .then(dado => {
                    if(dado["status"] == "sucesso"){
                        alert("Atividade criada com sucesso!");
                        atualizarTabela();
                    } else if(dado["status"] == "falha"){
                        alert("Falha na criação da atividade: "+dado["erro"]);
                    }
                })
                .catch(erro => {
                    alert("Erro: "+erro);
                })
            } else {
                alert("Você precisa ao menos dar um nome a atividade!");
                window.location = "#nomeAtividade";
            }
        })
        /* Criador de Atividades*/
        window.addEventListener("pagehide", e => {
            const dados = {
                comando: "closeCurso"
            }
            fetch("php/acoes.php", {
                method: "post",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dados)
            })
            .then(response => response.json())
            .then(dado => {
                if(dado["status"] != "sucesso"){
                    window.location = "login.php";
                }
            })
        })
    </script>
</body>
</html>