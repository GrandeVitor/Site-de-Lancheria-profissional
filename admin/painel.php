<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Estatísticas para o dashboard
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$ultimosPedidos = $pdo->query("SELECT * FROM pedidos ORDER BY data_pedido DESC LIMIT 5")->fetchAll();
$totalDestaques = $pdo->query("SELECT COUNT(*) FROM produtos WHERE destaque = 1")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Painel Administrativo - Gula Frangos</title>
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #3e8e41;
            --secondary: #6c757d;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        .container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            background: var(--dark);
            color: white;
            padding: 20px 0;
        }
        .logo {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .logo h2 {
            color: white;
            margin-bottom: 5px;
        }
        .logo p {
            color: #aaa;
            font-size: 0.9em;
        }
        .nav {
            margin-top: 20px;
        }
        .nav a {
            display: block;
            color: #ddd;
            padding: 12px 20px;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }
        .nav a:hover, .nav a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--primary);
        }
        .nav i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .badge {
            background: var(--primary);
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 0.7em;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        /* Main Content */
        .main-content {
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: var(--dark);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative; /* necessário para posicionar badge de notificação */
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 1em;
            color: var(--secondary);
            font-weight: 500;
        }
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .card-icon.primary {
            background: var(--primary);
        }
        .card-icon.secondary {
            background: var(--secondary);
        }
        .card-value {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-footer {
            margin-top: 15px;
            font-size: 0.9em;
            color: var(--secondary);
        }
        /* Badge de notificação nos cards */
        .card .notification-badge {
            position: absolute;
            top: 12px;
            right: 15px;
            background: var(--danger);
            color: white;
            font-size: 0.7em;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: bold;
            animation: pulse 1.5s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        /* Recent Orders */
        .recent-orders {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .section-title {
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            font-weight: 500;
            color: var(--secondary);
            font-size: 0.9em;
            text-transform: uppercase;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }
        .status.recebido {
            background: #fff3cd;
            color: #856404;
        }
        .status.preparo {
            background: #cce5ff;
            color: #004085;
        }
        .status.entregue {
            background: #d4edda;
            color: #155724;
        }
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            .sidebar {
                display: none;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Gula Frangos</h2>
                <p>Painel Administrativo</p>
            </div>
            <nav class="nav">
                <a href="painel.php" <?= basename($_SERVER['PHP_SELF']) == 'painel.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="produtos/listar.php" <?= basename($_SERVER['PHP_SELF']) == 'listar.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-utensils"></i> Produtos
                </a>
                <a href="destaques.php" <?= basename($_SERVER['PHP_SELF']) == 'destaques.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-star"></i> Destaques
                    <span class="badge"><?= $totalDestaques ?>/4</span>
                </a>
                <a href="pedidos/listar.php" <?= basename($_SERVER['PHP_SELF']) == 'listar.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-shopping-cart"></i> Pedidos
                </a>
                <a href="#"><i class="fas fa-users"></i> Clientes</a>
                <a href="#"><i class="fas fa-chart-bar"></i> Relatórios</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <div class="user-img"><?= substr($_SESSION['usuario_nome'] ?? 'A', 0, 1) ?></div>
                    <span><?= $_SESSION['usuario_nome'] ?? 'Admin' ?></span>
                </div>
            </div>

            <!-- Cards -->
            <div class="cards">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Total de Produtos</span>
                        <div class="card-icon primary"><i class="fas fa-utensils"></i></div>
                    </div>
                    <div class="card-value"><?= $totalProdutos ?></div>
                    <div class="card-footer"><a href="produtos/listar.php">Ver todos</a></div>
                </div>
                <div class="card" id="card-total-pedidos">
                    <div class="card-header">
                        <span class="card-title">Total de Pedidos</span>
                        <div class="card-icon secondary"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                    <div class="card-value"><?= $totalPedidos ?></div>
                    <div class="card-footer"><a href="pedidos/listar.php">Ver todos</a></div>
                </div>
                <div class="card" id="card-pedidos-hoje">
                    <div class="card-header">
                        <span class="card-title">Pedidos Hoje</span>
                        <div class="card-icon primary"><i class="fas fa-calendar-day"></i></div>
                    </div>
                    <div class="card-value">
                        <?= $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(data_pedido) = CURDATE()")->fetchColumn() ?>
                    </div>
                    <div class="card-footer"><a href="pedidos/listar.php?filtro=hoje">Ver detalhes</a></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Produtos em Destaque</span>
                        <div class="card-icon secondary"><i class="fas fa-star"></i></div>
                    </div>
                    <div class="card-value"><?= $totalDestaques ?></div>
                    <div class="card-footer"><a href="destaques.php">Gerenciar</a></div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="recent-orders">
                <div class="section-title">
                    <h2>Últimos Pedidos</h2>
                    <a href="pedidos/listar.php" class="btn">Ver todos</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Entrega</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosPedidos as $pedido): ?>
                        <tr>
                            <td>#<?= $pedido['id'] ?></td>
                            <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                            <td><?= ucfirst($pedido['tipo_entrega']) ?></td>
                            <td>
                                <span class="status <?= $pedido['status'] ?>">
                                    <?= ucfirst($pedido['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="pedidos/detalhes.php?id=<?= $pedido['id'] ?>" class="btn" style="padding: 5px 10px; font-size: 0.8em;">
                                    Detalhes
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ultimosPedidos)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Nenhum pedido recente</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Áudio de alerta -->
    <audio id="alertaSom" src="../uploads/som_notificacao.mp3" preload="auto"></audio>

    <script>
      let pedidosAtuais = [];
      let totalPedidosAntigo = <?= $totalPedidos ?>;
      let pedidosHojeAntigo = <?= $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(data_pedido) = CURDATE()")->fetchColumn() ?>;

      function mostrarBadge(cardId) {
        const card = document.getElementById(cardId);
        if (!card) return;

        // Verifica se já existe badge para não duplicar
        if (card.querySelector('.notification-badge')) return;

        const badge = document.createElement('div');
        badge.classList.add('notification-badge');
        badge.textContent = 'Novo';

        card.appendChild(badge);

        // Remove após 5 segundos
        setTimeout(() => {
          badge.remove();
        }, 5000);
      }

      async function buscarUltimosPedidos() {
        try {
          const res = await fetch('ultimos_pedidos.php');
          const novosPedidos = await res.json();

          const idsAntigos = pedidosAtuais.map(p => p.id);
          const novosPedidosNovos = novosPedidos.filter(p => !idsAntigos.includes(p.id));

          if (JSON.stringify(novosPedidos) !== JSON.stringify(pedidosAtuais)) {
            atualizarTabela(novosPedidos, novosPedidosNovos);
            pedidosAtuais = novosPedidos;
          }

          // Buscar totais atualizados
          const resTotais = await fetch('totais_pedidos.php');
          const totais = await resTotais.json();

          // Atualiza total de pedidos
          if (totais.totalPedidos > totalPedidosAntigo) {
            mostrarBadge('card-total-pedidos');
            totalPedidosAntigo = totais.totalPedidos;
            document.querySelector('#card-total-pedidos .card-value').textContent = totais.totalPedidos;
            document.getElementById('alertaSom').play();
          }

          // Atualiza pedidos hoje
          if (totais.pedidosHoje > pedidosHojeAntigo) {
            mostrarBadge('card-pedidos-hoje');
            pedidosHojeAntigo = totais.pedidosHoje;
            document.querySelector('#card-pedidos-hoje .card-value').textContent = totais.pedidosHoje;
            document.getElementById('alertaSom').play();
          }

        } catch (err) {
          console.error('Erro ao buscar pedidos:', err);
        }
      }

      function atualizarTabela(pedidos, novosPedidosNovos = []) {
        const tbody = document.querySelector('.recent-orders tbody');
        tbody.innerHTML = '';

        if (pedidos.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Nenhum pedido recente</td></tr>';
          return;
        }

        pedidos.forEach(pedido => {
          const isNovo = novosPedidosNovos.some(p => p.id === pedido.id);

          const row = document.createElement('tr');
          row.innerHTML = `
            <td>#${pedido.id} ${isNovo ? '🔔' : ''}</td>
            <td>${pedido.cliente_nome}</td>
            <td>R$ ${parseFloat(pedido.total).toFixed(2).replace('.', ',')}</td>
            <td>${pedido.tipo_entrega.charAt(0).toUpperCase() + pedido.tipo_entrega.slice(1)}</td>
            <td><span class="status ${pedido.status}">${pedido.status.charAt(0).toUpperCase() + pedido.status.slice(1)}</span></td>
            <td>
              <a href="pedidos/detalhes.php?id=${pedido.id}" class="btn" style="padding: 5px 10px; font-size: 0.8em;">
                Detalhes
              </a>
            </td>
          `;
          tbody.appendChild(row);
        });
      }

      setInterval(buscarUltimosPedidos, 5000);
      buscarUltimosPedidos();
    </script>
</body>
</html>
