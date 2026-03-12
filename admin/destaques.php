<?php
session_start();
require '../config/db.php';

// Verificação de login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Busca produtos com informação de destaque
$produtos = $pdo->query("
    SELECT id, nome, preco, destaque, imagem 
    FROM produtos 
    ORDER BY nome
")->fetchAll();

// Conta quantos produtos estão destacados atualmente
$total_destaques = $pdo->query("SELECT COUNT(*) FROM produtos WHERE destaque = 1")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Destaques - Painel Admin</title>
    <style>
        :root {
            --primary: #C82525;
            --accent: #D4A017;
            --dark: #3A2D28;
            --light: #F8F5F0;
            --white: #FFFFFF;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
        h1 {
            color: var(--primary);
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 1px;
        }
        
        .alert {
            padding: 12px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .alert-success {
            background-color: #e6ffed;
            color: #1a7a37;
            border-left: 4px solid #1a7a37;
        }
        
        .info-box {
            background-color: var(--light);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .info-box strong {
            color: var(--primary);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            background-color: var(--white);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: var(--light);
            font-weight: 600;
            color: var(--dark);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background-color: rgba(200, 37, 37, 0.03);
        }
        
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .btn {
            padding: 10px 18px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.95rem;
        }
        
        .btn:hover {
            background-color: #a51d1d;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(200, 37, 37, 0.2);
        }
        
        .btn-voltar {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-voltar:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .checkbox-container {
            display: flex;
            justify-content: center;
        }
        
        .checkbox-container input {
            transform: scale(1.5);
            cursor: pointer;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
        }
        
        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
            }
            
            th, td {
                padding: 12px 10px;
            }
            
            .product-img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="page-header">
            <h1>
                <i class="fas fa-star"></i>
                Gerenciar Destaques
            </h1>
            <a href="painel.php" class="btn btn-voltar">
                <i class="fas fa-arrow-left"></i>
                Voltar ao Painel
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Destaques atualizados com sucesso!
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            Atualmente <strong><?= $total_destaques ?></strong> produtos em destaque (máximo recomendado: 4)
        </div>
        
        <form action="salvar_destaques.php" method="POST" id="destaquesForm">
            <table>
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th class="checkbox-container">Destacar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td>
                            <img src="../<?= htmlspecialchars($produto['imagem']) ?>" 
                                 alt="<?= htmlspecialchars($produto['nome']) ?>" 
                                 class="product-img">
                        </td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td class="checkbox-container">
                            <input type="checkbox" 
                                   name="destaques[]" 
                                   value="<?= $produto['id'] ?>"
                                   <?= $produto['destaque'] ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="form-actions">
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Salvar Destaques
                </button>
            </div>
        </form>
    </div>

    <script>
        // Limita a seleção de no máximo 4 destaques
        document.getElementById('destaquesForm').addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('input[name="destaques[]"]:checked').length;
            if (checked > 4) {
                e.preventDefault();
                alert('Selecione no máximo 4 produtos para destaque!');
            }
        });

        // Limitação em tempo real
        const checkboxes = document.querySelectorAll('input[name="destaques[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll('input[name="destaques[]"]:checked').length;
                if (checked > 4) {
                    this.checked = false;
                    alert('Limite de 4 destaques atingido! Desmarque algum produto para selecionar este.');
                }
            });
        });
    </script>
</body>
</html>