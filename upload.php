<?php 
require_once 'auth.php'; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Importar Alunos - Sistema</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .upload-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 400px;
            position: relative;
        }
        /* Botão Sair no topo direito */
        .btn-logout {
            position: absolute;
            top: 15px;
            right: 15px;
            text-decoration: none;
            color: #dc3545;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
            transition: 0.3s;
        }
        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
            margin-bottom: 25px;
        }
        .form-group {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }
        input[type="file"] {
            margin-bottom: 10px;
            width: 100%;
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
        }
        button:hover {
            background-color: #218838;
        }
        p.instrucao {
            font-size: 13px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <!-- Link para Sair -->
    <a href="logout.php" class="btn-logout">Sair</a>

    <h2>Importar Clientes</h2>
    
    <form action="processa.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label style="display:block; margin-bottom:10px; color:#555;">Selecione a planilha .xlsx</label>
            <input type="file" name="planilha" accept=".xlsx" required>
        </div>
        
        <button type="submit">Enviar e Processar</button>
    </form>

    <p class="instrucao">Certifique-se de que as colunas seguem o padrão: Nome, CPF, Email e Telefone.</p>
</div>

</body>
</html>
