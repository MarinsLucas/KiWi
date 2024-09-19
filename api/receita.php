<?php
include("protect.php");
include("conexao.php");

if(!isset($_SESSION)){
    session_start();
}

$recipe_id = $_GET['id']; // Obtém o ID da receita da URL
$id_usuario = $_SESSION['user'];
$sql_code = "
    SELECT 
        receitas.*, 
        usuarios.nome AS nome_autor 
    FROM receitas
    JOIN usuarios ON receitas.id_user = usuarios.id
    WHERE receitas.id = $recipe_id
";

$sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

$recipe = $sql_query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipe["nome_receita"]?></title>
    <link rel="stylesheet" href="style_create.css">
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
    
    <!-- Conteúdo Principal -->
    <main>
        <h2><?php echo $recipe['nome_receita']; ?></h2>
        <div class="author-container">
            <a href="perfil.php?id_user=<?php echo $recipe["id_user"]; ?>"><?php echo $recipe['nome_autor']?></a>
        </div>

        <section>
            <div>
                <p><strong>Descrição da Receita</strong></p>
                <p><?php echo $recipe['descricao']; ?></p>
            </div>
        </section>

        <!-- Seção de Busca de Ingredientes -->
        <section>
            <h2>Ingredientes</h2>
            <div id="selected-items" aria-live="polite"></div>
        </section>
    
        <!-- Seção da Tabela Nutricional -->
        <section>
            <div id="nutrients-table-container"></div>
        </section>

    </main>

    <script src="view_recipe.js"></script>
</body>
</html> 