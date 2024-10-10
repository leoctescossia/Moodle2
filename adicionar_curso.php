<?php
include('config.php');


// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $professor = $_POST['professor'];

    // Inserindo o curso no banco de dados
    $query = "INSERT INTO cursos (nome, descricao, data_inicio, data_fim, professor) 
              VALUES (:nome, :descricao, :data_inicio, :data_fim, :professor)";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
    $stmt->bindParam(':professor', $professor);
    
    if ($stmt->execute()) {
        echo "Curso adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar o curso.";
    }
}
?>
