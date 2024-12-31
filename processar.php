<?php
// Configuração do banco de dados


try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter valores das variáveis na URL
    $callid = $_GET['callid'] ?? null;
    $ramal = $_GET['ramal'] ?? null;
    $nome = $_GET['nome'] ?? null;
    $setor = $_GET['setor'] ?? null;

    if ($callid) {
        // Verificar se o callid já existe no banco
        $stmt = $pdo->prepare("SELECT * FROM calldata WHERE callid = :callid");
        $stmt->execute(['callid' => $callid]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Atualizar o registro existente
            $updateStmt = $pdo->prepare("UPDATE calldata SET ramal = :ramal, nome = :nome, setor = :setor WHERE callid = :callid");
            $updateStmt->execute([
                'ramal' => $ramal,
                'nome' => $nome,
                'setor' => $setor,
                'callid' => $callid
            ]);
            echo "Registro atualizado com sucesso.";
        } else {
            // Inserir um novo registro
            $insertStmt = $pdo->prepare("INSERT INTO calldata (callid, ramal, nome, setor) VALUES (:callid, :ramal, :nome, :setor)");
            $insertStmt->execute([
                'callid' => $callid,
                'ramal' => $ramal,
                'nome' => $nome,
                'setor' => $setor
            ]);
            echo "Novo registro inserido com sucesso.";
        }
    } else {
        echo "O parâmetro 'callid' é obrigatório.";
    }
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
}
?>
