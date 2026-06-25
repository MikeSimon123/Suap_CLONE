<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        session_start();
        if($_SESSION["login"] == ""){
            echo "<script>window.location='index.php'</script>";
        }
    ?>
    <title>Página de Notas</title>
</head>
<body>
    <h1>Página de notas</h1>
    <input type="button" value="Página inicial" onclick="inicio()">
    <?php 
        if($_SESSION["func"] == "aluno"){
            echo "<h2>Suas notas: </h2>";
        }
        if($_SESSION["func"] == "professor"){
            echo "<h2>Gerencie as notas dos seus alunos: </h2>";
        }
    ?>

    <script>
        function inicio(){
            window.location = "home.php";
        }
    </script>
</body>
</html>