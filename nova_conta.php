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
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Sistema</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 380px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.2);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        p {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Criar nova conta</h2>
    
    <form method="POST" action="">
        <label>Nome:</label>
        <input type="text" name="nome" placeholder="Seu nome completo" required>

        <label>CPF (apenas números):</label>
        <input type="text" name="cpf" maxlength="11" placeholder="000.000.000-00" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="exemplo@email.com" required>

        <label>Telefone:</label>
        <input type="tel" name="telefone" placeholder="(00) 00000-0000" required>

        <label>Senha:</label>
        <input type="password" name="senha" placeholder="Crie uma senha forte" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p><a href="index.php">Já tenho conta (Voltar ao Login)</a></p>
</div>

</body>
</html>
