<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cadastro</title>
</head>
<body>
    <header>
        <select id="escola">
            <option value="conheca">Conheça a escola</option>
            <option value="efi">Ensino fundamental I</option>
            <option value="efii">Ensino fundamental II</option>
            <option value="em">Ensino Médio</option>
            <option value="estude">Estude com a gente</option>
            <option value="trabalhe">Trabalhe com a gente</option>
        </select>
        <a href="qsms.php">Quem somos</a>
        <a href="login.php">Login</a>
    </header>
    <h1>Cadastro</h1>
    <form action="" method="post" id='formCadastro'> 
        <p>Nome completo:</p><input type="text" name="cxnome" id="nome" required placeholder='Digite seu nome completo'>
        <p>Data de Nascimento:</p><input type="date" name="cxdate" id="date" required>
        <p>Email:</p><input type="email" name="cxemail" id="email" required placeholder='Digite seu email'>
        <p>Telefone:</p><input type="tel" name="cxtel" id="tel" placeholder='Digite seu telefone'>
        <p>Nome de usuário:</p><input type="text" name="cxuser" id="user" required placeholder='Digite um nome de usuário'>
        <p>Senha:</p><input type="password" name="cxpass" id="pass" required placeholder='Digite sua senha'>
        <p>Confirme sua senha:</p><input type="password" name="cxcpass" id="cpass" required placeholder='Digite novamente sua senha'>
        <input type="reset" value="Apagar"><input type="submit" value="Cadastrar">
    </form>

    <script>
        const formCadastro = document.getElementById("formCadastro");
        const nome = document.getElementById("nome")
        const data = document.getElementById("date")
        const email = document.getElementById("email")
        const tel = document.getElementById("tel")
        const user = document.getElementById("user")
        const senha = document.getElementById("pass")
        const csenha = document.getElementById("cpass")
        const msgForm = document.getElementById("msgForm")
        function cadastrar(){
            const dados = {
                nome: nome.value,
                data: data.value,
                email: email.value,
                tel: tel.value,
                user: user.value,
                senha: senha.value,
                comando: "cadastrar"
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
                    alert("Cadastro realizado com sucesso! Redirecionando para fazer login...");
                    window.location = "login.php";
                } else if(dado["status"] == "falha"){
                    alert("Algo deu errado com seu cadastro... Tente novamente mais tarde!");
                    alert("Erro: " + dado["erro"]);
                }
            })
            .catch(erro => {
                alert("Erro: "+erro);
            })
        }
        formCadastro.addEventListener("submit", e => {
            e.preventDefault();
            if(senha.value != csenha.value){
                alert("As senhas não coincidem! Tente novamente!");
                window.location = "#cpass";
            } else {
                cadastrar();
            }
        })
    </script>
</body>
</html>