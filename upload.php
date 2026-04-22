<!-- Crie este arquivo em: C:\samara_php\desafio-unasus\upload.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Importar Alunos</title>
</head>
<body>
    <h2>Importar Planilha de Clientes (.xlsx)</h2>
    <form action="processa.php" method="POST" enctype="multipart/form-data">
        <label>Selecione o arquivo:</label>
        <input type="file" name="planilha" accept=".xlsx" required>
        <br><br>
        <button type="submit">Enviar e Processar</button>
    </form>
</body>
</html>
