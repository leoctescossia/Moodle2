<?php
include('config.php');

// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta para obter a lista de cursos
$query_cursos = "SELECT * FROM cursos";
$stmt_cursos = $pdo->prepare($query_cursos);
$stmt_cursos->execute();
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .nav-bar {
            margin: 20px 0;
            display: flex;
            justify-content: space-around;
        }
        .nav-bar a {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .nav-bar a:hover {
            background-color: #555;
        }
        .content {
            background: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .content h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .add-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<header>
    <h1>Painel Administrativo</h1>
</header>

<div class="container">
    <div class="nav-bar">
        <a href="painel.php">Painel</a>
        <a href="cadastrar_curso.php">Cadastrar Novo Curso</a>
        <a href="adicionar_conteudo.php">Adicionar Conteúdo</a>
        <a href="adicionar_participante.php">Adicionar Participantes</a>
        <a href="cadastrar_aluno.php">Cadastrar Aluno</a>
        <a href="listar_usuarios.php">Lista de Usuários</a>
    </div>

    <div class="content">
        <h2>Cursos Disponíveis</h2>
        <?php if (count($cursos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Professor</th>
                        <th>Data de Início</th>
                        <th>Data de Fim</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($curso['id']); ?></td>
                        <td><?php echo htmlspecialchars($curso['nome']); ?></td>
                        <td><?php echo htmlspecialchars($curso['professor']); ?></td>
                        <td><?php echo htmlspecialchars($curso['data_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($curso['data_fim']); ?></td>
                        <td>
                            <a href="curso.php?id=<?php echo $curso['id']; ?>" class="add-btn">Visualizar Curso</a>
                            <a href="adicionar_participante.php?curso_id=<?php echo $curso['id']; ?>" class="add-btn">Adicionar Participante</a>
                            <a href="adicionar_conteudo.php?curso_id=<?php echo $curso['id']; ?>" class="add-btn">Adicionar Conteúdo</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum curso cadastrado.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
