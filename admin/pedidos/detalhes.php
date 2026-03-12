<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}

$pedido = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$pedido->execute([$_GET['id']]);
$pedido = $pedido->fetch();

if (!$pedido) {
    header('Location: listar.php?erro=Pedido não encontrado');
    exit;
}

$itens = json_decode($pedido['itens'], true);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Detalhes do Pedido #<?= $pedido['id'] ?></title>
    <style>
        body {
            font-family: Arial;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
            margin-top: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .info-value {
            padding: 8px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .map-link {
            color: #0066cc;
            text-decoration: none;
        }
        .map-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>

    <div class="card">
        <h3>Informações do Cliente</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nome</div>
                <div class="info-value"><?= htmlspecialchars($pedido['cliente_nome']) ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Telefone</div>
                <div class="info-value"><?= htmlspecialchars($pedido['telefone']) ?></div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Tipo de Entrega</div>
                <div class="info-value"><?= ucfirst($pedido['tipo_entrega']) ?></div>
            </div>
            
            <?php if ($pedido['tipo_entrega'] === 'entrega'): ?>
                <?php if (!empty($pedido['latitude']) && !empty($pedido['longitude'])): ?>
                <div class="info-item">
                    <div class="info-label">Localização</div>
                    <div class="info-value">
                        <a href="https://maps.google.com?q=<?= $pedido['latitude'] ?>,<?= $pedido['longitude'] ?>" 
                           target="_blank" class="map-link">
                            Ver no Google Maps
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($pedido['endereco'])): ?>
                <div class="info-item">
                    <div class="info-label">Endereço</div>
                    <div class="info-value"><?= htmlspecialchars($pedido['endereco']) ?></div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if (!empty($pedido['observacoes'])): ?>
            <div class="info-item" style="grid-column: span 2;">
                <div class="info-label">Observações</div>
                <div class="info-value"><?= htmlspecialchars($pedido['observacoes']) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3>Itens do Pedido</h3>
        <?php foreach ($itens as $item): ?>
            <div class="item">
                <span><?= htmlspecialchars($item['nome']) ?>
                    (x<?= $item['quantidade'] ?? $item['qtd'] ?? 1 ?>)
                </span>
                <span>R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
            </div>
        <?php endforeach; ?>
        <div class="total">
            Total: R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
        </div>
    </div>

    <div class="card">
        <h3>Status do Pedido</h3>
        <form method="post" action="atualizar-status.php">
            <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
            <select name="status" style="padding: 8px; margin-right: 10px;">
                <option value="recebido" <?= $pedido['status'] === 'recebido' ? 'selected' : '' ?>>Recebido</option>
                <option value="preparo" <?= $pedido['status'] === 'preparo' ? 'selected' : '' ?>>Em Preparo</option>
                <option value="entregue" <?= $pedido['status'] === 'entregue' ? 'selected' : '' ?>>Entregue</option>
            </select>
            <button type="submit" class="btn">Atualizar Status</button>
        </form>
    </div>

    <a href="listar.php" class="btn">← Voltar para lista</a>
</body>
</html>