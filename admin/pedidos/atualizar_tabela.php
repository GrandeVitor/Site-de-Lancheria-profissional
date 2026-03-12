<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    exit;
}

$filtro_status = $_GET['status'] ?? 'todos';
$filtro_data = $_GET['data'] ?? '';

$sql = "SELECT * FROM pedidos WHERE 1=1";
$params = [];

if ($filtro_status !== 'todos') {
    $sql .= " AND status = ?";
    $params[] = $filtro_status;
}
if (!empty($filtro_data)) {
    $sql .= " AND DATE(data_pedido) = ?";
    $params[] = $filtro_data;
}
$sql .= " ORDER BY data_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll();

?>

<tbody>
<?php foreach ($pedidos as $pedido):
    $itens = json_decode($pedido['itens'], true);
?>
    <tr>
        <td>#<?= $pedido['id'] ?></td>
        <td>
            <strong><?= htmlspecialchars($pedido['cliente_nome']) ?></strong><br>
            <small><?= htmlspecialchars($pedido['telefone']) ?></small>
        </td>
        <td>
            <?php if ($itens): ?>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.9em;">
                    <?php foreach ($itens as $item): ?>
                        <li><?= htmlspecialchars($item['nome']) ?>
                            (x<?= $item['quantidade'] ?? $item['qtd'] ?? 1 ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <span style="color: var(--secondary);">Nenhum item</span>
            <?php endif; ?>
        </td>
        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
        <td>
            <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?>
        </td>
        <td>
            <span class="status status-<?= $pedido['status'] ?>">
                <?= ucfirst($pedido['status']) ?>
            </span>
        </td>
        <td>
            <div style="display: flex; gap: 5px;">
                <a href="detalhes.php?id=<?= $pedido['id'] ?>" class="btn" style="padding: 5px 10px;">
                    <i class="fas fa-eye"></i>
                </a>
                <form method="post" action="atualizar-status.php" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                    <select name="status" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd;">
                        <option value="recebido" <?= $pedido['status'] === 'recebido' ? 'selected' : '' ?>>Recebido</option>
                        <option value="preparo" <?= $pedido['status'] === 'preparo' ? 'selected' : '' ?>>Preparo</option>
                        <option value="entregue" <?= $pedido['status'] === 'entregue' ? 'selected' : '' ?>>Entregue</option>
                    </select>
                </form>
            </div>
        </td>
    </tr>
<?php endforeach; ?>

<?php if (empty($pedidos)): ?>
    <tr>
        <td colspan="7" class="empty-state">
            <i class="fas fa-shopping-cart" style="font-size: 2em; margin-bottom: 10px;"></i>
            <p>Nenhum pedido encontrado</p>
        </td>
    </tr>
<?php endif; ?>
</tbody>
