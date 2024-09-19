<?php
include('conexao.php');

$erroemail = '';
$errosenha = '';
$faillogin = '';

if(isset($_POST['email']) || isset($_POST['senha'])) {
    if(strlen($_POST['email']) == 0)
    {
        $erroemail =  "Preencha seu e-mail";
    } else if(strlen($_POST['senha']) == 0)
    {
        $errosenha = "Preencha sua senha";
    } else{
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1)
        {
            $usuario = $sql_query->fetch_assoc();

            if(!isset($_SESSION)){
                session_start();
            }

            $_SESSION['user'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: home.php");
        }else{
            $faillogin = "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style_login.css">

</head>
<body>
    <main>

    <h1>Entrar</h1>
        <form action="" method="POST">
            <span id="fail-login"><?php echo $faillogin; ?></span>

            <div class="login-group">
                <label for="email-label">Digite seu e-mail:</label>
                <input type="text" id="email-input" name="email">
                <span><?php echo $erroemail; ?></span>
            </div>

            <div class="login-group">
                <label for="password-label">Digite sua senha:</label>
                <input type="password" id="password-input" name="senha">
                <span><?php echo $errosenha; ?></span>
                <p><a href="password_recovery.php">Esqueceu sua senha?</a></p>
            </div>
            <div class="login-group">        
                <a href="register.php">Registrar-se</a>   
                <button type="submit">Entrar</button>
            </div>
        </form>
    </main>
    
</body>
</html>