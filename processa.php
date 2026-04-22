<?php
require_once __DIR__ . '/../vendor/autoload.php';
include 'conexao.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['planilha'])) {

    $arquivoTmp = $_FILES['planilha']['tmp_name'];
    $nomeArquivo = $_FILES['planilha']['name'];
    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

    if ($extensao !== 'xlsx') {
        die("Erro: Por favor, envie um arquivo .xlsx");
    }

    try {
        $spreadsheet = IOFactory::load($arquivoTmp);
        $abaAtiva = $spreadsheet->getActiveSheet();
        $dadosExcel = $abaAtiva->toArray(null, true, true, true);

        $sql = "INSERT INTO clientes (nome, cpf, email, telefone) VALUES (:nome, :cpf, :email, :telefone)";
        $stmt = $pdo->prepare($sql);

        $relatorio = []; // Array para armazenar os resultados e exibir na tabela
        $totalSucesso = 0;
        $totalFalha = 0;

        foreach ($dadosExcel as $indice => $coluna) {
            if ($indice == 1) continue; // Pula cabeçalho

            $nome     = $coluna['A'] ?? '';
            $cpf      = $coluna['B'] ?? '';
            $email    = $coluna['C'] ?? '';
            $telefone = $coluna['D'] ?? '';

            $erros = [];

            // Validações
            if (empty(trim($nome))) $erros[] = "Nome vazio";
            
            $cpfLimpo = preg_replace('/[^0-9]/', '', (string)$cpf);
            if (strlen($cpfLimpo) !== 11) $erros[] = "CPF inválido";

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "E-mail inválido";

            $telLimpo = preg_replace('/[^0-9]/', '', (string)$telefone);
            if (!preg_match('/^[0-9]{10,11}$/', $telLimpo)) $erros[] = "Telefone inválido";

            $statusLinha = false;
            $mensagemErro = "";

            if (empty($erros)) {
                try {
                    $stmt->execute([
                        'nome'     => $nome,
                        'cpf'      => $cpfLimpo,
                        'email'    => $email,
                        'telefone' => $telLimpo
                    ]);
                    $statusLinha = true;
                    $totalSucesso++;
                } catch (Exception $e) {
                    $mensagemErro = "Erro no banco: " . $e->getCode();
                    $totalFalha++;
                }
            } else {
                $mensagemErro = implode(", ", $erros);
                $totalFalha++;
            }

            // Alimenta o relatório para a tabela final
            $relatorio[] = [
                'linha'    => $indice,
                'nome'     => $nome,
                'email'    => $email,
                'status'   => $statusLinha,
                'mensagem' => $mensagemErro
            ];
        }

        // --- EXIBIÇÃO DO RESUMO VISUAL ---
        echo "<h2>Resumo da Importação: $nomeArquivo</h2>";
        echo "<p>✅ Sucesso: <strong>$totalSucesso</strong> | ❌ Falhas: <strong>$totalFalha</strong></p>";

        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-family: sans-serif;'>";
        echo "<tr style='background: #333; color: white;'>
                <th>Linha</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Resultado</th>
                <th>Detalhes</th>
              </tr>";

        foreach ($relatorio as $item) {
            // Cores conforme solicitado: Verde/Branco para sucesso, Vermelho/Branco para erro
            $corFundo = $item['status'] ? "#28a745" : "#dc3545"; 
            $textoStatus = $item['status'] ? "INSERIDO" : "NÃO INSERIDO";

            echo "<tr style='background-color: $corFundo; color: white;'>";
            echo "<td align='center'>{$item['linha']}</td>";
            echo "<td>" . htmlspecialchars($item['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($item['email']) . "</td>";
            echo "<td align='center'><strong>$textoStatus</strong></td>";
            echo "<td>" . ($item['status'] ? "Salvo no Banco" : $item['mensagem']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><a href='upload.php' style='padding: 10px; background: #eee; text-decoration: none; color: black; border: 1px solid #ccc;'>Fazer novo Upload</a>";

    } catch (Exception $e) {
        die("Erro ao processar arquivo: " . $e->getMessage());
    }
} else {
    header("Location: upload.php");
    exit();
}
?>