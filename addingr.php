<?php
include("conexao.php");

$errorname = '';
$ingredientadded = '';

if(isset($_POST['name']))
{
    if(strlen($_POST['name']) == 0)
    {
        echo "Preencha o nome do ingrediente";
    }
    else{
        $username = $mysqli->real_escape_string($_POST['username']);
        
        if(!isset($_SESSION))
        {
            session_start();
        }

    
        $nome = $mysqli->real_escape_string($_POST['name']);
        $userid = $_SESSION['user'];
        $energia = floatval($mysqli->real_escape_string($_POST['energia']));
        $proteinas = floatval($mysqli->real_escape_string($_POST['proteinas']));
        $carboidrato = floatval($mysqli->real_escape_string($_POST["carboidrato"]));
        $colesterol = floatval($mysqli->real_escape_string($_POST['colesterol']));
        $fibras = floatval($mysqli->real_escape_string($_POST['fibras']));
        $sodio = floatval($mysqli->real_escape_string($_POST['sodio']));
        $acucares = floatval($mysqli->real_escape_string($_POST['acucares']));
        $gordurastotais = floatval($mysqli->real_escape_string($_POST['gordurastotais']));
        $saturadas = floatval($mysqli->real_escape_string($_POST['saturadas']));
        $gordurastrans = floatval($mysqli->real_escape_string($_POST['gordurastrans']));

        $sql_code = "INSERT INTO `ingredientes` (`nome`, `id_usuario`, `energia`, `proteinas`, `carboidrato`, `colesterol`, `fibras`, `sodio`, `acucares`, `Gorduras totais`, `Gorduras saturadas`, `Gorduras trans`) VALUES 
        ('$nome', $userid, $energia, $proteinas, $carboidrato, $colesterol, $fibras, $sodio, $acucares, $gordurastotais ,$saturadas,$gordurastrans);";
        if ($mysqli->query($sql_code)) {
            $ingredientadded = "Ingrediente adicionado com sucesso!";
        } else {
            echo "Erro: " . $mysqli->error;
        }

    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Ingrediente | KiWi</title>
    <link rel="stylesheet" href="style_addi.css">
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
    <div>
        <h2>Adicionar Ingrediente</h2>
        <span id = "feedback"><?php echo $ingredientadded ?></span>
        <form action="" method="POST">
            <div class="form-group">
                <label for="ingredient-name">Nome do Ingrediente:</label>
                <input type="text" id="ingredient-name" name="name">
                <span><?php echo $errorname; ?></span>
            </div>

            <h3>Valores Nutricionais (por 100g):</h3>
            <div class="form-group">
                <label for="energia">Energia (kcal):</label>
                <input type="number" id="energia" name="energia" step="0.01">
            </div>
            <div class="form-group">
                <label for="proteinas">Proteínas (g):</label>
                <input type="number" id="proteinas" name="proteinas" step="0.01">
            </div>
            <div class="form-group">
                <label for="carboidrato">Carboidratos (g):</label>
                <input type="number" id="carboidrato" name="carboidrato" step="0.01">
            </div>
            <div class="form-group">
                <label for="colesterol">Colesterol (mg):</label>
                <input type="number" id="colesterol" name="colesterol" step="0.01">
            </div>
            <div class="form-group">
                <label for="fibras">Fibras Alimentares (mg):</label>
                <input type="number" id="Fibras" name="fibras" step="0.01">
            </div>
            <div class="form-group">
                <label for="sodio">Sódio (mg):</label>
                <input type="number" id="sodio" name="sodio" step="0.01">
            </div>
            <div class="form-group">
                <label for="acucares">Açucares (g):</label>
                <input type="number" id="acucares" name="acucares" step="0.01">
            </div>
            <div class="form-group">
                <label for="gordurastotais">Gorduras Totais (mg):</label>
                <input type="number" id="gordurastotais" name="gordurastotais" step="0.01">
            </div>
            <div class="form-group">
                <label for="Saturadas">Gorduras Saturadas (mg):</label>
                <input type="number" id="Saturadas" name="saturadas" step="0.01">
            </div>
            <div class="form-group">
                <label for="gordurastrans">Gorduras trans (mg):</label>
                <input type="number" id="gordurastrans" name="gordurastrans" step="0.01">
            </div>
            
            <button type="submit" id="addingredient">Adicionar Ingrediente</button>

        </form>
    </div>
    </main>
    
</body>
</html>
