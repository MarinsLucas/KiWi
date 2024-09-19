<?php
include('conexao.php');

// Verifica se o id_receita foi passado pela URL
if (isset($_GET['id_receita'])) {
    $id_receita = intval($_GET['id_receita']); // Converte o parâmetro para inteiro

    // Query para buscar os ingredientes e seus detalhes no banco de dados
    $sql_code = "
        SELECT 
            ingredientes.*,  -- Pega todas as colunas de ingredientes
            receitas_ingredientes.quantidade,
            receitas_ingredientes.unidade_medida
        FROM receitas_ingredientes
        JOIN ingredientes ON receitas_ingredientes.id_ingrediente = ingredientes.id
        WHERE receitas_ingredientes.id_receita = $id_receita
    ";

    $sql_query = $mysqli->query($sql_code);

    // Se houver ingredientes encontrados
    if ($sql_query->num_rows > 0) {
        $ingredients = [];
        while ($row = $sql_query->fetch_assoc()) {
            $ingredients[] = $row; // Adiciona cada ingrediente ao array
        }

        // Retorna os dados como JSON
        echo json_encode([
            'success' => true,
            'ingredients' => $ingredients
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Nenhum ingrediente encontrado'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID da receita não fornecido'
    ]);
}
