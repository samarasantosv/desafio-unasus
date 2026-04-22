<?php
// Crie este arquivo em: C:\samara_php\desafio-unasus\processa.php
require_once __DIR__ . '/../vendor/autoload.php';
include 'conexao.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['planilha'])) {
    
    $arquivoTmp = $_FILES['planilha']['tmp_name'];
    $extensao = pathinfo($_FILES['planilha']['name'], PATHINFO_EXTENSION);

    // Validar se é realmente um XLSX
    if ($extensao !== 'xlsx') {
        die("Erro: Por favor, envie um arquivo .xlsx");
    }

    try {
        // Lemos o arquivo diretamente do local temporário onde o PHP o salvou
        $spreadsheet = IOFactory::load($arquivoTmp);
        $abaAtiva = $spreadsheet->getActiveSheet();
        $dadosExcel = $abaAtiva->toArray(null, true, true, true);

        $sql = "INSERT INTO clientes (nome, cpf, email, telefone) VALUES (:nome, :cpf, :email, :telefone)";
        $stmt = $pdo->prepare($sql);

        $sucesso = 0;
        $falha = 0;

        foreach ($dadosExcel as $indice => $coluna) {
            if ($indice == 1) continue; // Pula cabeçalho

            $nome     = $coluna['A'] ?? '';
            $cpf      = $coluna['B'] ?? '';
            $email    = $coluna['C'] ?? '';
            $telefone = $coluna['D'] ?? '';

            // Validações Básicas
            if (empty(trim($nome)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $falha++;
                continue;
            }

            $cpfLimpo = preg_replace('/[^0-9]/', '', (string)$cpf);
            $telLimpo = preg_replace('/[^0-9]/', '', (string)$telefone);

            try {
                $stmt->execute([
                    'nome'     => $nome,
                    'cpf'      => $cpfLimpo,
                    'email'    => $email,
                    'telefone' => $telLimpo
                ]);
                $sucesso++;
            } catch (Exception $e) {
                $falha++;
            }
        }

        echo "<h3>Processamento concluído!</h3>";
        echo "✅ Importados: $sucesso <br>";
        echo "❌ Falhas/Ignorados: $falha <br>";
        echo "<br><a href='upload.php'>Voltar</a>";

    } catch (Exception $e) {
        die("Erro ao ler o arquivo: " . $e->getMessage());
    }
} else {
    header("Location: upload.php");
}
