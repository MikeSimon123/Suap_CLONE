<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        include "php/verify.php";
    ?>
    <title>Cursos</title>
</head>
<body>
    <section id="menu_lateral">
        <?php
            include_once "php/menu.php";
        ?>
    </section>
    <h1>Cursos</h1>
    <?php
    if($_SESSION["func"] == "aluno"){
        echo "<input type='button' value='Cadastrar-se em uma turma' onclick='cadastrarse()'>";
    }
    if($_SESSION["func"] == "professor"){
        echo "<input type='button' value='Cadastre uma turma no sistema' onclick='cadastrar()'>";
    }
    ?>
    <section id="cadastroProf">
        <h2>Cadastro de Turma</h2>
        <form action="" method="post" id="cadastroProfForm">
            <p>Nome da turma:</p>
            <input type="text" name="cxnome" id="nomeCadProf" placeholder="Digite o nome da turma">
            <p>Descrição do curso:</p>
            <textarea name="cxdesc" id="desc" placeholder="Uma breve descrição do curso"></textarea>
            <input type="submit" value="Cadastrar turma">
        </form>
    </section>
    <section id="cadastroAluno">
        <h2>Cadastrar-se em uma turma</h2>
        <select name="cxcurso" id="cursos">
            <?php
                try{
                    include_once "php/connection.php";
                    $comando = $conexao->query("select * from cursos");
                    $cursos = $comando->fetchAll(PDO::FETCH_ASSOC);
                    foreach($cursos as $curso){
                        $nome = $curso["nome"];
                        echo "<option value='$nome'>$nome</option>";
                    }
                }catch (Exception $error) {
                    echo "<script>alert('Erro' . $error)</script>";
                }
                
            ?>
        </select>
        <input type="submit" value="Cadastrar-se" id='cadAluno'>
    </section>
    <script>
        const cadastroProf = document.getElementById("cadastroProf");
        const nomeCadastroProf = document.getElementById("nomeCadProf");
        const descCadastroProf = document.getElementById("desc");
        const cadastroAluno = document.getElementById("cadastroAluno");
        const cadAluno = document.getElementById("cadAluno");
        cadastroProf.style.display = "none";
        cadastroAluno.style.display = "none";
        function cadastrar(){
            cadastroProf.style.display = "block";
        }
        function cadastrarse(){
                cadastroAluno.style.display = "block"
        }
        cadastroProf.addEventListener("submit", e => {
            e.preventDefault();
            const dados = {
                comando: "cadastrar-turma",
                nome: nomeCadastroProf.value,
                desc: descCadastroProf.value
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
                if(dado["status"] == "falha-nome"){
                    alert("Não foi possível criar porque um curso já tem esse nome");
                }
                else if(dado["status"] == "sucesso"){
                    alert('Turma cadastrada com sucesso!');
                }
                else if(dado["status"] == "falha"){
                    alert("ERRO: "+dado["erro"]);
                } else if(dado["status"] == "funciona"){
                    alert("funciona");
                }
            })
            .catch(erro => {
                alert('Erro:' + erro);
            });
        })
    </script>
</body>
</html>