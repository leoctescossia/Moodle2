<?php
include('config.php');

// Tentar conectar ao banco de dados
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de falha, exibir mensagem de erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Iniciar a sessão
session_start();

// Verificar se o formulário foi enviado
if (isset($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar se os dados foram recebidos corretamente
    if (empty($email) || empty($senha)) {
        echo "Os campos de email e senha são obrigatórios.";
    } else {
        // Verificar se o email existe no banco de dados
        $query = "SELECT * FROM alunos WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            // Email encontrado, verificar senha
            if ($senha === $aluno['senha']) {
                $_SESSION['aluno_id'] = $aluno['id']; // Armazenar o ID do aluno na sessão
                $_SESSION['nome'] = $aluno['nome'];    // Armazenar o nome do aluno na sessão
                header("Location: painel_aluno.php");
                exit();
            } else {
                echo "Senha incorreta!";
            }
        } else {
            echo "Email não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aluno</title>
</head>
<body>
    <h2>Login Aluno</h2>

    <?php if (isset($erro)): ?>
        <p style="color: red;"><?php echo $erro ; ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
