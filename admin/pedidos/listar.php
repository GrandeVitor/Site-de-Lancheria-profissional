<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

// Filtros
$filtro_status = $_GET['status'] ?? 'todos';
$filtro_data = $_GET['data'] ?? '';

// Query base
$sql = "SELECT * FROM pedidos WHERE 1=1";
$params = [];

// Aplica filtros
if ($filtro_status !== 'todos') {
    $sql .= " AND status = ?";
    $params[] = $filtro_status;
}

if (!empty($filtro_data)) {
    $sql .= " AND DATE(data_pedido) = ?";
    $params[] = $filtro_data;
}

$sql .= " ORDER BY data_pedido DESC";

// Executa a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll();

// Contagem por status para o menu
$contagemStatus = [
    'todos' => $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn(),
    'recebido' => $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status = 'recebido'")->fetchColumn(),
    'preparo' => $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status = 'preparo'")->fetchColumn(),
    'entregue' => $pdo->query("SELECT COUNT(*) FROM pedidos WHERE status = 'entregue'")->fetchColumn()
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Gula Frangos</title>
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #3e8e41;
            --secondary: #6c757d;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            color: var(--dark);
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9em;
            transition: background 0.3s;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--secondary);
        }

        .filtros {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .status-filter {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .status-btn {
            padding: 5px 10px;
            border-radius: 20px;
            background: #e9ecef;
            color: #495057;
            text-decoration: none;
            font-size: 0.8em;
            transition: all 0.3s;
        }

        .status-btn.active,
        .status-btn:hover {
            background: var(--primary);
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 500;
            color: var(--secondary);
            text-transform: uppercase;
            font-size: 0.8em;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
            display: inline-block;
        }

        .status-recebido {
            background: #fff3cd;
            color: #856404;
        }

        .status-preparo {
            background: #cce5ff;
            color: #004085;
        }

        .status-entregue {
            background: #d4edda;
            color: #155724;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 0.7em;
            font-weight: bold;
            background: var(--secondary);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--secondary);
        }

        @media (max-width: 768px) {

            .header,
            .filtros {
                flex-direction: column;
                align-items: flex-start;
            }

            th,
            td {
                padding: 8px 10px;
                font-size: 0.9em;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="header">
        <h1>Gerenciar Pedidos</h1>
        <div>
            <a href="../painel.php" class="btn btn-secondary">← Voltar ao Painel</a>
        </div>
    </div>

    <div class="filtros">
        <div class="status-filter">
            <a href="?status=todos" class="status-btn <?= $filtro_status === 'todos' ? 'active' : '' ?>">
                Todos <span class="badge"><?= $contagemStatus['todos'] ?></span>
            </a>
            <a href="?status=recebido" class="status-btn <?= $filtro_status === 'recebido' ? 'active' : '' ?>">
                Recebidos <span class="badge"><?= $contagemStatus['recebido'] ?></span>
            </a>
            <a href="?status=preparo" class="status-btn <?= $filtro_status === 'preparo' ? 'active' : '' ?>">
                Em Preparo <span class="badge"><?= $contagemStatus['preparo'] ?></span>
            </a>
            <a href="?status=entregue" class="status-btn <?= $filtro_status === 'entregue' ? 'active' : '' ?>">
                Entregues <span class="badge"><?= $contagemStatus['entregue'] ?></span>
            </a>
        </div>

        <form method="get" style="display: flex; gap: 10px;">
            <input type="hidden" name="status" value="<?= htmlspecialchars($filtro_status) ?>">
            <input type="date" name="data" value="<?= htmlspecialchars($filtro_data) ?>" class="form-control">
            <button type="submit" class="btn">Filtrar</button>
            <?php if (!empty($filtro_data)): ?>
                <a href="?status=<?= htmlspecialchars($filtro_status) ?>" class="btn btn-secondary">Limpar</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Itens</th>
                <th>Total</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
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
    </table>

   <script>
    let ultimoPedidoId = <?= !empty($pedidos) ? $pedidos[0]['id'] : 0 ?>;
    let notificacaoAtiva = false;
    let carregandoPedidos = false;

    async function verificarNovosPedidos() {
        if (carregandoPedidos) return;
        
        try {
            // 1. Verificar último pedido
            const resUltimo = await fetch('ultimo_pedido.php');
            const dataUltimo = await resUltimo.json();
            
            // 2. Se houver novo pedido
            if (dataUltimo.id && dataUltimo.id > ultimoPedidoId) {
                ultimoPedidoId = dataUltimo.id;
                
                // 3. Mostrar notificação
                if (!notificacaoAtiva) {
                    notificacaoAtiva = true;
                    mostrarNotificacao(`Novo pedido #${dataUltimo.id} recebido!`);
                    setTimeout(() => notificacaoAtiva = false, 5000);
                }
                
                // 4. Atualizar tabela
                await atualizarTabelaCompleta();
            }
            
            // 5. Verificar atualizações de status
            await verificarAtualizacoesStatus();
            
        } catch (err) {
            console.error('Erro ao verificar pedidos:', err);
        }
    }

    async function atualizarTabelaCompleta() {
        carregandoPedidos = true;
        try {
            // Captura todos os parâmetros atuais
            const params = new URLSearchParams(window.location.search);
            
            // Faz a requisição para obter a tabela atualizada
            const response = await fetch(`atualizar_tabela.php?${params.toString()}`);
            const html = await response.text();
            
            // Cria um parser temporário
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = `<table>${html}</table>`;
            
            // Encontra o novo tbody
            const newTbody = tempDiv.querySelector('tbody');
            
            if (newTbody) {
                // Substitui o tbody existente
                const oldTbody = document.querySelector('table tbody');
                if (oldTbody) {
                    oldTbody.replaceWith(newTbody);
                } else {
                    document.querySelector('table').appendChild(newTbody);
                }
            }
        } catch (err) {
            console.error('Erro ao atualizar tabela:', err);
        } finally {
            carregandoPedidos = false;
        }
    }

    async function verificarAtualizacoesStatus() {
        try {
            // Pega todos os IDs visíveis na tabela
            const ids = Array.from(document.querySelectorAll('table tbody tr td:first-child'))
                .map(td => td.textContent.trim().replace('#', ''))
                .filter(id => id);
            
            if (ids.length === 0) return;
            
            // Busca os status atualizados
            const response = await fetch(`verificar_status.php?ids=${ids.join(',')}`);
            const pedidosAtualizados = await response.json();
            
            // Atualiza cada linha que mudou
            pedidosAtualizados.forEach(pedido => {
                const linha = document.querySelector(`table tbody tr td:first-child:contains("#${pedido.id}")`)?.closest('tr');
                if (linha) {
                    const celulaStatus = linha.querySelector('td:nth-child(6) span');
                    if (celulaStatus) {
                        // Remove todas as classes de status
                        celulaStatus.className = 'status';
                        // Adiciona a nova classe
                        celulaStatus.classList.add(`status-${pedido.status}`);
                        // Atualiza o texto
                        celulaStatus.textContent = pedido.status.charAt(0).toUpperCase() + pedido.status.slice(1);
                    }
                }
            });
        } catch (err) {
            console.error('Erro ao verificar status:', err);
        }
    }

    function mostrarNotificacao(mensagem) {
    // Remove notificações existentes
    document.querySelectorAll('.notificacao-pedido').forEach(el => el.remove());
    
    // Cria nova notificação
    const notificacao = document.createElement('div');
    notificacao.className = 'notificacao-pedido';
    notificacao.style.position = 'fixed';
    notificacao.style.top = '20px';
    notificacao.style.left = '50%';
    notificacao.style.transform = 'translateX(-50%)';
    notificacao.style.backgroundColor = '#4CAF50';
    notificacao.style.color = 'white';
    notificacao.style.padding = '15px 25px';
    notificacao.style.borderRadius = '4px';
    notificacao.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
    notificacao.style.zIndex = '1000';
    notificacao.style.animation = 'fadeIn 0.5s';
    notificacao.style.textAlign = 'center';
    notificacao.style.minWidth = '300px';
    notificacao.style.maxWidth = '80%';
    notificacao.textContent = mensagem;
    
    document.body.appendChild(notificacao);
    
    // Remove após 5 segundos
    setTimeout(() => {
        notificacao.style.animation = 'fadeOut 0.5s';
        setTimeout(() => notificacao.remove(), 500);
    }, 5000);
}
    // Adiciona animações CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
    `;
    document.head.appendChild(style);

    // Verifica a cada 3 segundos (pode ajustar)
    setInterval(verificarNovosPedidos, 3000);
    // Verifica imediatamente ao carregar
    verificarNovosPedidos();
</script>
</body>

</html>