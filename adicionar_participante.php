<?php
include('config.php');

// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Pegando o ID do curso da URL
$curso_id = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : 0;

// Verificando se o ID do curso é válido
if ($curso_id == 0) {
    die("Curso inválido.");
}

// Consulta para buscar todos os alunos
$query = "SELECT * FROM alunos";
$stmt = $pdo->prepare($query);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = isset($_POST['aluno_id']) ? (int)$_POST['aluno_id'] : 0;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null; // Obtemos o tipo a partir do formulário

    if ($aluno_id > 0 && $tipo !== null) {
        // Inserir o participante na tabela participantes
        $query = "INSERT INTO participantes (curso_id, nome, tipo) VALUES (:curso_id, (SELECT nome FROM alunos WHERE id = :aluno_id), :tipo)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->bindParam(':aluno_id', $aluno_id);
        $stmt->bindParam(':tipo', $tipo);

        try {
            $stmt->execute();
            echo "Participante adicionado com sucesso! <a href='curso.php?id=$curso_id'>Voltar para o curso</a>";
        } catch (PDOException $e) {
            echo "Erro ao adicionar participante: " . $e->getMessage();
        }
    } else {
        echo "Selecione um aluno e um tipo válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Participante</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Adicionar Participante ao Curso</h1>
    <form action="adicionar_participante.php?curso_id=<?php echo $curso_id; ?>" method="POST">
        <label for="aluno_id">Selecionar Aluno:</label>
        <select name="aluno_id" id="aluno_id" required>
            <option value="">Selecione um aluno</option>
            <?php foreach ($alunos as $aluno): ?>
                <option value="<?php echo htmlspecialchars($aluno['id']); ?>"><?php echo htmlspecialchars($aluno['nome']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        
        <label for="tipo">Selecionar Papel:</label>
        <select name="tipo" id="tipo" required>
            <option value="">Selecione um papel</option>
            <option value="Aluno">Aluno</option>
            <option value="Professor">Professor</option>
        </select>
        <br>

        <input type="submit" value="Adicionar Participante">
    </form>
    <p><a href="curso.php?id=<?php echo $curso_id; ?>">Voltar para o curso</a></p>
</body>
</html>
