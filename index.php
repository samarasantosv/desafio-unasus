<?php
include 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();

        // O CORRETO: comparar a variável $senha (do POST) 
    // com a coluna 'password' (do banco)
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        
        header('Location: upload.php');
        exit(); 
    } else {
        echo "E-mail ou senha incorretos.";
    }

    // Verifica se usuário existe e se a senha criptografada bate
/*    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        echo "Login realizado com sucesso!";
        header('Location: upload.php');
        exit(); // Sempre use exit após um redirecionamento para parar a execução do script
    } else {
        echo "E-mail ou senha incorretos.";
    }
*/        
}
?>

<h2>Login</h2>
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    
    <label>Senha:</label>
    <input type="password" name="senha" required><br>
    
    <button type="submit">Entrar</button>
</form>
<p><a href="nova_conta.php">Criar conta</a></p>
