<?php
include('conexao.php');

$nousername = '';
$noemail = '';
$nopassword = '';
$passworderror = '';
if(isset($_POST['email']) || isset($_POST['senha']) || isset($_POST['username']) || isset($_POST['senha-confirm'])) {
    if(strlen($_POST['username']) == 0)
    {
        $nousername = "Preencha seu nome de usuário";
    }else if(strlen($_POST['email']) == 0)
    {
        $noemail =  "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0)
    {
        $nopassword = "Preencha sua senha";
    } 
    else if($_POST["senha"] != $_POST["senha-confirm"])
    {
        $passworderror = "As senhas informadas não são iguais";
    }
    else{
        $username = $mysqli->real_escape_string($_POST['username']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES (NULL, '$username', '$email', '$senha');";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        header("Location: index.php");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar | KiWi</title>
    <link rel="stylesheet" href="style_login.css">

</head>
<body>
    <main>
        <h1>Registrar-se</h1>
        <form action="" method="POST">
            <div class="login-group">
                <label for="username-label">Nome de Usuário</label>
                <input type="text" name="username">
                <span><?php echo $nousername?></span>
            </div>
            <div class="login-group">
                <label for="email-label">E-mail</label>
                <input type="text" name="email">
                <span><?php echo $noemail?></span>

            </div>
            <div class="login-group">
                <label for="password-label">Digite sua senha</label>
                <input type="password" name="senha">
                <span><?php echo $nopassword?></span>

            </div>
            <div class="login-group">
                <label for="password-label">Confirme sua senha</label>
                <input type="password" name="senha-confirm">
                <span><?php echo $passworderror?></span>

            </div>
            <div class="login-group">
                <b>AVISO!! Nenhum dos seus dados estarão protegidos!</b>
            </div>
            <div class="login-group">
                <button type="submit">Registrar-se</button>
            </div>
        </form>
    </main>
</body>
</html>