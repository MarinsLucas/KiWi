<?php
include("protect.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create | KiWi</title>
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
        <h2>Criar Receita</h2>
        <section>
            <div id ="nome-receita" aria-live="polite">
                <p>Nome da Receita</p>
                <input type="text" id="receita-nome" placeholder="Digite o nome da receita" aria-label="Campo de nome da receita">
            </div>
            <div>
                <p>Descrição da Receita</p>
                <textarea id="recipe-description" name="recipeDescription" rows="10" cols="50" placeholder="Escreva aqui a descrição da receita..."></textarea><br>
            </div>
        </section>

        <!-- Seção de Busca de Ingredientes -->
        <section>
            <h2>Ingredientes</h2>
            <div id="selected-items" aria-live="polite"></div>

            <p>Digite o nome do ingrediente para procurar e adicionar à sua receita:</p>
            <input type="text" id="search" placeholder="Digite o nome do ingrediente..." aria-label="Campo de busca para ingredientes">
            <div id="suggestions" class="dropdown" role="listbox" aria-live="polite"></div>
        </section>
    
        <!-- Seção da Tabela Nutricional -->
        <section>
            <div id="nutrients-table-container"></div>
        </section>

        <button id="postreceita">Publicar Receita</button>
    </main>

    <script src="create_recipe.js"></script>
</body>
</html>


