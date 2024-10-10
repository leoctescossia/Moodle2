<?php
include('config.php');


// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verificando se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id = $_POST['curso_id'];
    $titulo = $_POST['titulo'];
    $tipo_conteudo = $_POST['tipo_conteudo'];

    $conteudo = ''; // Inicializando o conteúdo

    // Processar diferentes tipos de conteúdo
    if ($tipo_conteudo === 'Arquivo' && isset($_FILES['arquivo'])) {
        // Verificar e fazer upload de arquivos
        $arquivo = $_FILES['arquivo'];
        $pasta_upload = 'uploads/'; // Pasta onde os arquivos serão salvos

        // Verifica se o arquivo foi enviado
        if ($arquivo['error'] == 0) {
            $caminho_arquivo = $pasta_upload . basename($arquivo['name']);
            if (move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
                $conteudo = $caminho_arquivo; // Salva o caminho do arquivo no banco de dados
            } else {
                die("Erro ao fazer upload do arquivo.");
            }
        }
    } elseif ($tipo_conteudo === 'URL') {
        // Processar URL
        $conteudo = $_POST['url'];
    } elseif ($tipo_conteudo === 'Texto') {
        // Processar Texto
        $conteudo = $_POST['conteudo_texto'];
    }

    // Inserindo o conteúdo no banco de dados
    $query = "INSERT INTO conteudos (curso_id, titulo, conteudo, tipo_conteudo) VALUES (:curso_id, :titulo, :conteudo, :tipo_conteudo)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':curso_id', $curso_id);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':conteudo', $conteudo);
    $stmt->bindParam(':tipo_conteudo', $tipo_conteudo);
    $stmt->execute();

    echo "Conteúdo adicionado com sucesso!";
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Conteúdo ao Curso</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Função para alternar os campos visíveis com base no tipo de conteúdo
        function mostrarCamposTipoConteudo() {
            var tipoConteudo = document.getElementById("tipo_conteudo").value;
            document.getElementById("campo_arquivo").style.display = "none";
            document.getElementById("campo_url").style.display = "none";
            document.getElementById("campo_texto").style.display = "none";

            if (tipoConteudo === "Arquivo") {
                document.getElementById("campo_arquivo").style.display = "block";
            } else if (tipoConteudo === "URL") {
                document.getElementById("campo_url").style.display = "block";
            } else if (tipoConteudo === "Texto") {
                document.getElementById("campo_texto").style.display = "block";
            }
        }
    </script>
</head>
<body>
    <h1>Adicionar Conteúdo</h1>
    <form action="adicionar_conteudo.php" method="POST" enctype="multipart/form-data">
        <label for="curso_id">ID do Curso:</label><br>
        <input type="number" name="curso_id" required><br><br>

        <label for="titulo">Título do Conteúdo:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label for="tipo_conteudo">Tipo de Conteúdo:</label><br>
        <select id="tipo_conteudo" name="tipo_conteudo" onchange="mostrarCamposTipoConteudo()" required>
            <option value="Arquivo">Arquivo</option>
            <option value="URL">URL</option>
            <option value="Texto">Texto</option>
        </select><br><br>

        <!-- Campo para Upload de Arquivo -->
        <div id="campo_arquivo" style="display:none;">
            <label for="arquivo">Carregar Arquivo:</label><br>
            <input type="file" name="arquivo" accept=".pdf, .docx, .pptx, .mp4, .jpg, .png"><br><br>
        </div>

        <!-- Campo para URL -->
        <div id="campo_url" style="display:none;">
            <label for="url">Link Externo:</label><br>
            <input type="url" name="url"><br><br>
        </div>

        <!-- Campo para Texto/HTML -->
        <div id="campo_texto" style="display:none;">
            <label for="conteudo_texto">Conteúdo (Texto/HTML):</label><br>
            <textarea name="conteudo_texto" rows="10" cols="50"></textarea><br><br>
        </div>

        <input type="submit" value="Adicionar Conteúdo">
    </form>
</body>
</html>

