<?php
include('adicionar_curso.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Curso</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Adicionar Novo Curso</h1>
    <form action="adicionar_curso.php" method="POST">
        <label for="nome">Nome do Curso:</label><br>
        <input type="text" name="nome" required><br><br>

        <label for="descricao">Descrição:</label><br>
        <textarea name="descricao" required></textarea><br><br>

        <label for="data_inicio">Data de Início:</label><br>
        <input type="date" name="data_inicio" required><br><br>

        <label for="data_fim">Data de Fim:</label><br>
        <input type="date" name="data_fim" required><br><br>

        <label for="professor">Professor:</label><br>
        <input type="text" name="professor" required><br><br>

        <input type="submit" value="Adicionar Curso">
    </form>
</body>
</html>


