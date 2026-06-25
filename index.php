<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        session_start();
        if($_SESSION["login"] != ""){
            echo "<script>window.location = 'home.php'</script>";
        }
    ?>
    <title>Página de Login</title>
</head>
<body>
    <h1>Página de Login</h1>
    <form action="" method="post" id="formLogin">
        <input type="text" name="cxlogin" id="login" placeholder="Digite seu login" required>
        <input type="password" name="cxpass" id="pass" placeholder="Digite sua senha" required>
        <input type="reset" value="Cancelar"><input type="submit" value="Entrar">
    </form>

    <script>
        const form = document.getElementById('formLogin');
        const login = document.getElementById("login");
        const senha = document.getElementById("pass");
        
        form.addEventListener("submit", e => {
            e.preventDefault();
            const dados = {
                comando: "entrar",
                login: login.value,
                senha: senha.value
            };
            fetch("php/acoes.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dados)
            })
            .then(response => response.json())
            .then(dado => {
                if(dado["status"] == "sucesso"){
                    alert("Sucesso! Entrando...")
                    window.location = "home.php";
                } else if(dado["status"] == "falhou"){
                    alert("Tentativa de Login falha");
                } else {
                    alert("qualquer coisa");
                }
            })
            .catch(error => {
                alert("Erro: "+error);
            })
        })
    </script>
</body>
</html>