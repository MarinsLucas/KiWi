<?php
include("protect.php");
include("conexao.php");

if(!isset($_SESSION)){
    session_start();
}

// Verifica se o ID do usuário foi passado na URL
if (isset($_GET['id_user'])) {
    $userid = intval($_GET['id_user']); // ID do usuário passado na URL
} else {
    $userid = $_SESSION['user']; // Se não, usa o ID do usuário logado
}

// Pega o nome do usuário (dono do perfil)
$sql_user = "SELECT nome FROM usuarios WHERE id = $userid";
$result_user = $mysqli->query($sql_user) or die("Falha na execução do código SQL: " . $mysqli->error);
$user_data = $result_user->fetch_assoc();
$username = $user_data['nome'];

// Busca receitas do usuário especificado
$sql_code = "SELECT * FROM receitas WHERE id_user = $userid ORDER BY data_hora_publicacao DESC";
$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo $username; ?> | KiWi</title>
    <link rel="stylesheet" href="style_h.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header>
        <h1>KiWi</h1>
    </header>

    <div class="sidebar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="create.php">Criar Receita</a></li>
            <li><a href="addingr.php">Adicionar Ingrediente</a></li>
            <li><a href="perfil.php">Perfil</a></li>
            <li><a href="logout.php" id="sair">Sair</a></li>
        </ul>
    </div>

    <main>
        <h2><?php echo $username; ?></h2>
        <div id="recipe-list">
        <?php
            // Exibe as receitas do usuário
            if($sql_query->num_rows > 0) {
                while($row = $sql_query->fetch_assoc()) {
                    echo "<div class='recipe' onclick=\"window.location.href='receita.php?id=" . $row['id'] . "'\">";
                    echo "<h3>" . $row['nome_receita'] . "</h3>";
                    echo "<p>Descrição: " . $row['descricao'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Este usuário ainda não tem receitas cadastradas.</p>";
            }
        ?>
        </div>
    </main>
    
</body>
</html>
