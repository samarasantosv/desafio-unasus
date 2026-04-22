<?php
// 1. Importar o autoload que está na raiz (Opção 1)
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Definir o caminho do arquivo XLSX
$caminhoArquivo = __DIR__ . '/arquivos/alunos_teste.xlsx';

// 1. Verificar se o arquivo existe
if (!file_exists($caminhoArquivo)) {
    die("Erro: O arquivo não foi encontrado: $caminhoArquivo");
}

try {
    // 2. Carregar o arquivo Excel
    $spreadsheet = IOFactory::load($caminhoArquivo);
    $abaAtiva = $spreadsheet->getActiveSheet();
    
    // Converter para array para facilitar o loop (null = celulas vazias, true = formatar valores)
    $dadosExcel = $abaAtiva->toArray(null, true, true, true);

    echo "<h3>Relatório de Processamento (Excel):</h3>";

    // 3. Percorrer os dados (ignorar linha 1 que é o cabeçalho)
    foreach ($dadosExcel as $indice => $coluna) {
        if ($indice == 1) continue; // Pula o cabeçalho Nome;CPF;Email;Telefone

        // Mapear colunas do Excel (A=Nome, B=CPF, C=Email, D=Telefone)
        $nome     = $coluna['A'] ?? '';
        $cpf      = $coluna['B'] ?? '';
        $email    = $coluna['C'] ?? '';
        $telefone = $coluna['D'] ?? '';

        $erros = [];

        // 4. Validações (Mesma lógica do CSV)

        // Validar NOME
        if (empty(trim($nome))) {
            $erros[] = "Nome vazio";
        }

        // Validar CPF (apenas números e 11 dígitos)
        $cpfLimpo = preg_replace('/[^0-9]/', '', (string)$cpf);
        if (strlen($cpfLimpo) !== 11 || !preg_match('/^[0-9]{11}$/', $cpfLimpo)) {
            $erros[] = "CPF inválido ($cpf)";
        }

        // Validar EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = "E-mail inválido ($email)";
        }

        // Validar TELEFONE (apenas números, 10 ou 11 dígitos)
        $telLimpo = preg_replace('/[^0-9]/', '', (string)$telefone);
        if (!preg_match('/^[0-9]{10,11}$/', $telLimpo)) {
            $erros[] = "Telefone inválido ($telefone)";
        }

        // Exibir resultado
        if (empty($erros)) {
            echo "<p style='color: green;'>Linha $indice ($nome): ✅ Válido</p>";
        } else {
            echo "<p style='color: red;'>Linha $indice ($nome): ❌ Erros: " . implode(", ", $erros) . "</p>";
        }
    }

} catch (\Exception $e) {
    die("Erro ao ler o arquivo Excel: " . $e->getMessage());
}
?>