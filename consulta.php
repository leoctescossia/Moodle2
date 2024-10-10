<?php
include('config.php');


// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta para buscar todos os cursos
$query = "SELECT * FROM cursos";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Disponíveis</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Cursos Disponíveis</h1>
    <table border="1">
        <tr>
            <th>Nome do Curso</th>
            <th>Descrição</th>
            <th>Data de Início</th>
            <th>Data de Fim</th>
            <th>Professor</th>
        </tr>
        <?php foreach ($cursos as $curso): ?>
        <tr>
            <td>
                <!-- Link para a página do curso específico -->
                <a href="curso.php?id=<?php echo $curso['id']; ?>">
                    <?php echo htmlspecialchars($curso['nome']); ?>
                </a>
            </td>
            <td><?php echo htmlspecialchars($curso['descricao']); ?></td>
            <td><?php echo htmlspecialchars($curso['data_inicio']); ?></td>
            <td><?php echo htmlspecialchars($curso['data_fim']); ?></td>
            <td><?php echo htmlspecialchars($curso['professor']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
