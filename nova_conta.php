<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome     = $_POST['nome'];
    $cpf      = $_POST['cpf'];
    $email    = $_POST['email'];
    $telefone = $_POST['telefone'];
    // Criptografia da senha
    $senha    = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios (nome, cpf, email, telefone, senha) 
                VALUES (:nome, :cpf, :email, :telefone, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'cpf' => $cpf,
            'email' => $email,
            'telefone' => $telefone,
            'senha' => $senha
        ]);
        echo "Conta criada com sucesso! <a href='index.php'>Faça login</a>";
    } catch (PDOException $e) {
        echo "Erro ao criar conta: " . $e->getMessage();
    }
}
?>

<h2>Criar nova conta</h2>
<form method="POST" action="" enctype="multipart/form-data">
    <label>Nome:</label>
    <input type="text" name="nome" required><br>

    <label>CPF (apenas números):</label>
    <input type="text" name="cpf" maxlength="11" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Telefone:</label>
    <input type="tel" name="telefone" required><br>

    <label>Senha:</label>
    <input type="password" name="senha" required><br>

    <button type="submit">Cadastrar</button>
</form>
<p><a href="index.php">Já tenho conta</a></p>
