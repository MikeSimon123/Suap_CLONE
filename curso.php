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
    <title><?php echo $_SESSION["turma"]?></title>
</head>
<body>
    <section id="menu_lateral">
        <?php
            include_once "php/menu.php";
        ?>
    </section>
    <h1><?php 
        if(array_key_exists("turma", $_SESSION)){
            echo $_SESSION["turma"];
        } else {
            echo "Não tem ";
        }
        ?>
    </h1>
    <?php 
        if($_SESSION["func"] == "aluno" or $_SESSION["func"] == "professor"){
            echo "<a href='atividades.php'>Atividades</a>";
            echo "<a href='materias.php'>Materiais</a>";
            echo "<a href='progresso.php'>Progresso</a>";
        } 
    ?>
    <script>
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