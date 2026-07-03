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
    <section id="editorAtividade">
        <h2>Editor de Atividade</h2>
        <p>Nome da atividade:</p>
        <input type="text" name="cxnomeEditor" id="nomeEditor">
        <section id="previa"></section>
        <input type="button" value="Adicionar elemento à atividade" id='adElemento'>
        <input type="button" value="Finalizar edição" id='finalizarEdicao'>
    </section>
    <section id="elemento">
        <select name="cxtipo" id="elementoTipo">
            <option value="multiescolha">Questão Múltipla Escolha</option>
            <option value="textoescolha">Questão com Caixa de Texto</option>
        </select>
        <input type="button" value="Adicionar" onclick='adicionar()'>
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
                tabela: "atividades" + "<?php echo $_SESSION['nomeTratado']?>" + "tb"
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
                        atividadesTabela.innerHTML += "ATIVIDADE: " + atividade["nome"] + "<?php if($_SESSION["func"] == "professor"){echo "<input type='button' value='Editar' onclick='editarAtividade(`";}?>" + `${atividade["nome"]}` + "`)'>" + "<br>";
                    });
                    if(atividadesTabela.innerHTML == ""){
                        atividadesTabela.innerHTML += "Não existem atividades do curso ainda";
                    }
                }
            })
            .catch(erro => {
                alert("erro:" + erro);
            })
        }
        atualizarTabela();
        formCriadorAtividade.addEventListener("submit", e => {
            e.preventDefault();
            if(nomeAtividade.value != ""){
                const dados = {
                    comando: "criarAtividade",
                    nome: nomeAtividade.value,
                    tabela: "atividades" + "<?php echo $_SESSION['nomeTratado']?>" + 'tb'
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
                        criadorAtividade.style.display = "none";
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

        /* Editar atividade*/
        const editorAtividade = document.getElementById("editorAtividade");
        const adElemento = document.getElementById("adElemento");
        const finalizarEdicao = document.getElementById("finalizarEdicao");
        const elemento = document.getElementById("elemento");
        const elementoTipo = document.getElementById("elementoTipo");
        const elementos = [];
        editorAtividade.style.display = "none";
        elemento.style.display = "none";
        function editarAtividade(nome){
            editorAtividade.style.display = "block";
        }
        finalizarEdicao.addEventListener("click", e => {
            editorAtividade.style.display = "none";
        })
        adElemento.addEventListener("click", e => {
            elemento.style.display = "block";
        })
        function adicionar(){
            elementos.push(elementoTipo.value);
            elemento.style.display = "none";
        }
        /* Editar atividade*/
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