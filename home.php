<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        session_start();
        if($_SESSION["login"] == ""){
            echo "<script>window.location = 'index.php';</script>";
        }
    ?>
    <title>Página Inicial</title>
</head>
<body>
    <h1>Bem vindo(a) ao sistema, <?php echo $_SESSION["login"]?>!</h1>
    <input type="button" value="Sair" onclick="sair()">
    <?php
        if($_SESSION["func"] == "aluno"){
            echo "<input type='button' value='Ver notas' onclick='notas()'>";
        }
        if($_SESSION["func"] == 'professor'){
            echo "<input type='button' value='Gerenciador de notas' onclick='notas()'>";
        }
    ?>
    <script>
        function sair(){
            const dados = {
                comando: "sair"
            };
            fetch("php/acoes.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dados)
            })
            .then(response => {
                return response.json()})
            .then(dado => {
                if(dado["status"] == "sucesso"){
                    alert("Saindo...");
                    window.location = "index.php";
                }
            })
            .catch(error => {
                alert("Erro: " + error);
            })
        }
        function notas(){
            window.location = "notas.php";
        }
    </script>
</body>
</html>