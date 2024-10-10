<?php
include('config.php');
session_start();

// Verificar se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: login.php");
    exit();
}

// Exibir o nome do aluno
echo "<h1>Bem-vindo, " . htmlspecialchars($_SESSION['nome']) . "!</h1>";

// Conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta para obter as turmas do aluno
$aluno_id = $_SESSION['aluno_id'];
$query = "
    SELECT cursos.id AS curso_id, cursos.nome AS curso_nome
    FROM participantes
    JOIN cursos ON participantes.curso_id = cursos.id
    WHERE participantes.tipo = 'Aluno' 
      AND participantes.curso_id IN (
          SELECT curso_id 
          FROM alunos 
          WHERE id = :aluno_id
      )
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':aluno_id', $aluno_id);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Exibir as turmas do aluno
if (count($cursos) > 0) {
    echo "<h2>Turmas nas quais você participa:</h2>";
    echo "<ul>";
    foreach ($cursos as $curso) {
        echo "<li><a href='curso.php?id=" . htmlspecialchars($curso['curso_id']) . "'>" . htmlspecialchars($curso['curso_nome']) . "</a></li>";
    }
    echo "</ul>";
} else {
    echo "<p>Você não está matriculado em nenhuma turma.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno</title>
    <style>
        /* styles.css */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            background-color: #4CAF50; /* Cor de fundo verde */
            color: white; /* Texto branco */
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333; /* Cor do título */
            margin-top: 20px;
        }

        ul {
            list-style-type: none; /* Remove marcadores da lista */
            padding: 0;
        }

        li {
            background: #ffffff; /* Fundo branco */
            margin: 10px 0; /* Margem entre os itens */
            padding: 15px; /* Espaçamento interno */
            border-radius: 5px; /* Bordas arredondadas */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra leve */
            transition: background 0.3s; /* Transição suave para hover */
        }

        li:hover {
            background: #e9e9e9; /* Cor de fundo ao passar o mouse */
        }

        a {
            text-decoration: none; /* Remove o sublinhado dos links */
            color: #4CAF50; /* Cor do link */
        }

        a:hover {
            color: #45a049; /* Cor do link ao passar o mouse */
        }

        p {
            text-align: center; /* Centraliza o texto */
            margin-top: 20px; /* Espaço acima do parágrafo */
        }


    </style>
</head>
<body>
    <p><a href="logout.php">Sair</a></p>
</body>
</html>

