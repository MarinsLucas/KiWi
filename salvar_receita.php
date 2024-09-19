<?php
include('conexao.php');
if(!isset($_SESSION))
{
    session_start();
}
// Recebe os dados da requisição
$data = json_decode(file_get_contents('php://input'), true);

$nome = $mysqli->real_escape_string($data['name']);
$descricao = $mysqli->real_escape_string($data['description']);
$userid = $_SESSION["user"];
$ingredientes = $data['ingredients'];

// Insere a receita na tabela de receitas
$sql = "INSERT INTO receitas (nome_receita, descricao, id_user) VALUES ('$nome', '$descricao', $userid)";
if ($mysqli->query($sql) === TRUE) {
    $recipeId = $mysqli->insert_id; // Pega o ID da receita inserida

    // Insere cada ingrediente na tabela de ingredientes_receita
    foreach ($ingredientes as $ingrediente) {
        $idingrediente = $mysqli->real_escape_string($ingrediente['alimento']['id']);
        $quantidade = $mysqli->real_escape_string($ingrediente['quantidade']);
        $unmedida = $mysqli->real_escape_string($ingrediente['unidade']);
        
        $sql_ingrediente = "INSERT INTO receitas_ingredientes (id_receita, id_ingrediente, quantidade, unidade_medida) 
                            VALUES ('$recipeId', '$idingrediente', '$quantidade', '$unmedida')";
        $mysqli->query($sql_ingrediente);
    } 

    echo "Receita inserida com sucesso!";
} else {
    echo "Erro ao inserir receita: " . $mysqli->error;
}
?>
