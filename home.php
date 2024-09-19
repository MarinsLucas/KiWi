<?php
include("protect.php");
include("conexao.php");

$username = $_SESSION["nome"];

if(!isset($_SESSION)){
    session_start();
}

$userid = $_SESSION['user'];

$sql_code = "
    SELECT 
        receitas.*, 
        usuarios.nome AS nome_autor 
    FROM receitas
    JOIN usuarios ON receitas.id_user = usuarios.id
    ORDER BY receitas.data_hora_publicacao DESC LIMIT 10
";

$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | KiWi</title>
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
        <div id="recipe-list">
        <?php
            // Exibe as receitas
            if($sql_query->num_rows > 0) {
                while($row = $sql_query->fetch_assoc()) {
                    echo "<div class='recipe' onclick=\"window.location.href='receita.php?id=" . $row['id'] . "'\">";
                    echo "<h3>" . $row['nome_receita'] . "</h3>";
                    echo "<span>" . $row['nome_autor'] . "</span>";
                    echo "<p>Descrição: " . $row['descricao'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Você ainda não tem receitas cadastradas.</p>";
            }
        ?>
        </div>
    </main>
    
</body>
</html>
