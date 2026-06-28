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
    <h1>Bem vindo(a) ao sistema, <?php echo $_SESSION["nomeC"]?>!</h1>
    <section id="menu_lateral">
        <?php
            include_once "php/menu.php";
        ?>
    </section>
</body>
</html>