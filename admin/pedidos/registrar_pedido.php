<?php
require '../../config/db.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validação dos campos obrigatórios
    $required = ['cliente_nome', 'telefone', 'itens', 'total'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Campo obrigatório faltando: {$field}");
        }
    }

    // Prepara os dados para inserção (SEM COMENTÁRIOS NA QUERY)
    $stmt = $pdo->prepare("INSERT INTO pedidos (
        cliente_nome, 
        telefone, 
        itens, 
        total, 
        tipo_entrega, 
        endereco,
        latitude,
        longitude,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $data['cliente_nome'],
        $data['telefone'],
        $data['itens'],
        $data['total'],
        $data['tipo_entrega'] ?? 'retirada',
        $data['endereco'] ?? null,
        $data['latitude'] ?? null,
        $data['longitude'] ?? null,
        $data['status'] ?? 'recebido'
    ]);

    echo json_encode([
        'sucesso' => true,
        'id_pedido' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
?>