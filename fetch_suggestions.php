<?php
include('conexao.php'); // Inclua o arquivo de conexão com o banco de dados

if (isset($_GET['query'])) {

    $query = $mysqli->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM ingredientes WHERE nome LIKE '%$query%' LIMIT 10"; // Substitua "nome" e "ingredientes" pelos seus nomes de tabela e coluna

    $result = $mysqli->query($sql);

    $suggestions = [];

    if ($result->num_rows > 0) {
        // Itera sobre os resultados dos alimentos
        while ($row = $result->fetch_assoc()) {
            $id_usuario = $row['id_usuario'];

            // Faz uma nova consulta para buscar o nome do usuário
            $user_query = "SELECT nome FROM usuarios WHERE id = $id_usuario";
            $user_result = $mysqli->query($user_query);

            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $row['nome_usuario'] = $user_row['nome']; // Adiciona o nome do usuário ao array
            } else {
                $row['nome_usuario'] = 'Usuário não encontrado'; // Caso não encontre o usuário
            }

            $suggestions[] = $row;
        }
    }
    else {
        echo "Nenhum alimento encontrado com esse nome.";
    }
    
    echo json_encode($suggestions);
    $mysqli->close();

}


?>