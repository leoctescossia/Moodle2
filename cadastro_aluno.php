<?php
include('config.php');

// Conectando ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Verificação básica de autenticação de gestor
/*session_start();
if (!isset($_SESSION['gestor_id'])) {
    die("Você precisa estar logado como gestor para acessar esta página.");
}
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
        
    
    // Criptografando a senha antes de salvar
    //$chave_secreta = "sua_chave_secreta";  // Uma chave secreta para usar na criptografia
    //$senhaHash = password_hash($senha, PASSWORD_BCRYPT);; // Hash da senha
    $senhaHash = $senha; // Não mais criptografada


    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];

    // Inserir aluno
    $query = "INSERT INTO alunos (nome, email, data_nascimento, telefone, senha) VALUES (:nome, :email, :data_nascimento, :telefone, :senha)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':data_nascimento', $data_nascimento);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':senha', $senhaHash);
    //$stmt->bindParam(':senha', $senha);

    try {
        $stmt->execute();
        echo "Aluno cadastrado com sucesso! <a href='cadastro_aluno.php'>Cadastrar outro aluno</a> | <a href='listar_usuarios.php'>Ver alunos cadastrados</a>";
    } catch (PDOException $e) {
        echo "Erro ao cadastrar aluno: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Aluno</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Realize o cadastro</h1>
    <form action="cadastro_aluno.php" method="POST">
            
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" name="data_nascimento" id="data_nascimento" required>
        <br>

        <label for="telefone">Telefone:</label>
        <input type="tel" name="telefone" id="telefone" required>
        <br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        <br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
