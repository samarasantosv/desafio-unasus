<?php
// 1. Definir o caminho do arquivo (dentro do container)
$caminhoArquivo = __DIR__ . '/arquivos/alunos_teste.csv';

// Verificar se o arquivo existe
if (!file_exists($caminhoArquivo)) {
    die("Erro: O arquivo não foi encontrado no caminho: $caminhoArquivo");
}

// 2. Abrir o arquivo para leitura
if (($handle = fopen($caminhoArquivo, "r")) !== FALSE) {
    
    // Ignorar a primeira linha (cabeçalho: Nome;CPF;Email;Telefone)
    fgetcsv($handle, 1000, ";");

    echo "<h3>Relatório de Processamento:</h3>";

    // 3. Fazer o parse de cada linha separando por ';'
    $linha = 2; // Contador para saber em qual linha estamos
    while (($dados = fgetcsv($handle, 1000, ";")) !== FALSE) {
        
        // Salvar cada coluna em uma variável
        $nome     = $dados[0] ?? '';
        $cpf      = $dados[1] ?? '';
        $email    = $dados[2] ?? '';
        $telefone = $dados[3] ?? '';

        $erros = [];

        // 4. Validações
        
        // Checar se a coluna NOME está vazia ou nula
        if (empty(trim($nome))) {
            $erros[] = "Nome vazio";
        }

        // Validar CPF: Apenas números, 11 dígitos e Regex
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpfLimpo) !== 11 || !preg_match('/^[0-9]{11}$/', $cpfLimpo)) {
            $erros[] = "CPF inválido ($cpf)";
        }

        // Validar EMAIL: Filtro nativo do PHP (checa @ e domínio)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = "E-mail inválido ($email)";
        }

        // Validar TELEFONE: Regex para formato (98) 99999-0000 ou similar
        // Aceita apenas números com 10 ou 11 dígitos
        $telLimpo = preg_replace('/[^0-9]/', '', $telefone);
        if (!preg_match('/^[0-9]{10,11}$/', $telLimpo)) {
            $erros[] = "Telefone inválido ($telefone)";
        }

        // Exibir resultado da linha
        if (empty($erros)) {
            echo "<p style='color: green;'>Linha $linha ($nome): Dados válidos e prontos para o banco!</p>";
        } else {
            echo "<p style='color: red;'>Linha $linha ($nome): Erros encontrados: " . implode(", ", $erros) . "</p>";
        }

        $linha++;
    }

    // Fechar o arquivo
    fclose($handle);
} else {
    echo "Erro ao abrir o arquivo para leitura.";
}
?>