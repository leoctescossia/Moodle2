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
$curso_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Verificando se o ID do curso é válido
if ($curso_id == 0) {
    die("Curso inválido.");
}

// Consulta para buscar o curso específico
$query = "SELECT * FROM cursos WHERE id = :curso_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':curso_id', $curso_id);
$stmt->execute();
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificando se o curso foi encontrado
if (!$curso) {
    die("Curso não encontrado.");
}

// Consulta para buscar o conteúdo do curso
$query_conteudo = "SELECT * FROM conteudos WHERE curso_id = :curso_id";
$stmt_conteudo = $pdo->prepare($query_conteudo);
$stmt_conteudo->bindParam(':curso_id', $curso_id);
$stmt_conteudo->execute();
$conteudos = $stmt_conteudo->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar os participantes (professores e alunos)
$query_participantes = "SELECT * FROM participantes WHERE curso_id = :curso_id";
$stmt_participantes = $pdo->prepare($query_participantes);
$stmt_participantes->bindParam(':curso_id', $curso_id);
$stmt_participantes->execute();
$participantes = $stmt_participantes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['nome']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .nav-bar {
            display: flex;
            margin-bottom: 20px;
        }
        .nav-bar a {
            padding: 10px 20px;
            background-color: #f2f2f2;
            margin-right: 10px;
            text-decoration: none;
            color: #000;
            border-radius: 5px;
        }
        .nav-bar a.active {
            background-color: #ccc;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($curso['nome']); ?></h1>
    <p><strong>Professor:</strong> <?php echo htmlspecialchars($curso['professor']); ?></p>
    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($curso['descricao']); ?></p>
    <p><strong>Data de Início:</strong> <?php echo htmlspecialchars($curso['data_inicio']); ?></p>
    <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars($curso['data_fim']); ?></p>

    <!-- Barra de navegação -->
    <div class="nav-bar">
        <a href="#" class="tab-link active" data-tab="conteudo">Conteúdo</a>
        <a href="#" class="tab-link" data-tab="participantes">Participantes</a>
    </div>

    <!-- Conteúdo -->
    <div id="conteudo" class="tab-content active">
        <h2>Conteúdos do Curso</h2>
        <a href="adicionar_conteudo.php?curso_id=<?php echo $curso['id']; ?>" class="add-btn">Adicionar Conteúdo</a>
        <?php if (count($conteudos) > 0): ?>
            <ul>
            <?php foreach ($conteudos as $conteudo): ?>
                <li>
                    <h3><?php echo htmlspecialchars($conteudo['titulo']); ?></h3>
                    
                    <?php if ($conteudo['tipo_conteudo'] === 'Arquivo'): ?>
                        <a href="<?php echo htmlspecialchars($conteudo['conteudo']); ?>" download>Baixar Arquivo</a><br>
                    <?php elseif ($conteudo['tipo_conteudo'] === 'URL'): ?>
                        <a href="<?php echo htmlspecialchars($conteudo['conteudo']); ?>" target="_blank">Visitar Link</a><br>
                    <?php elseif ($conteudo['tipo_conteudo'] === 'Texto'): ?>
                        <p><?php echo nl2br(htmlspecialchars($conteudo['conteudo'])); ?></p>
                    <?php endif; ?>

                    <br><em>Publicado em: <?php echo htmlspecialchars($conteudo['data_publicacao']); ?></em>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum conteúdo disponível para este curso.</p>
        <?php endif; ?>
    </div>

    <!-- Participantes -->
    <div id="participantes" class="tab-content">

        <h2>Participantes do Curso</h2>
        
        <!-- Adicionando um link para a página de adicionar participantes -->
        <p><a href="adicionar_participante.php?curso_id=<?php echo $curso_id; ?>">Adicionar Participante</a></p>

        <?php if (count($participantes) > 0): ?>
            <ul>
            <?php foreach ($participantes as $participante): ?>
                <li>
                    <strong><?php echo htmlspecialchars($participante['nome']); ?></strong> 
                    (<?php echo htmlspecialchars($participante['tipo']); ?>) <!-- Tipo pode ser 'Aluno' ou 'Professor' -->
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum participante registrado.</p>
        <?php endif; ?>
    </div>

    <script>
        // Script para alternar entre abas
        document.querySelectorAll('.tab-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove a classe ativa de todas as abas
                document.querySelectorAll('.tab-link').forEach(function(l) {
                    l.classList.remove('active');
                });
                // Adiciona a classe ativa à aba clicada
                this.classList.add('active');

                // Esconde todas as abas de conteúdo
                document.querySelectorAll('.tab-content').forEach(function(tab) {
                    tab.classList.remove('active');
                });
                // Mostra o conteúdo da aba clicada
                document.getElementById(this.getAttribute('data-tab')).classList.add('active');
            });
        });
    </script>
</body>
</html>
