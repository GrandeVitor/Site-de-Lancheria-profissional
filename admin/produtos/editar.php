<?php
// Ativar exibição de erros (coloque no TOPO do arquivo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../../config/db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

// Verifica se o ID do produto foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: listar.php?erro=ID inválido');
    exit;
}

$produtoId = $_GET['id'];

// Busca o produto no banco de dados
try {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$produtoId]);
    $produto = $stmt->fetch();

    // Se não encontrar o produto
    if (!$produto) {
        header('Location: listar.php?erro=Produto não encontrado');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar produto: " . $e->getMessage());
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        ':id' => $produtoId,
        ':nome' => $_POST['nome'],
        ':descricao' => $_POST['descricao'],
        ':preco' => str_replace(',', '.', $_POST['preco']),
        ':categoria' => $_POST['categoria']
    ];

    // Atualiza a imagem se uma nova foi enviada
   if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $conteudoImagem = file_get_contents($_FILES['imagem']['tmp_name']);
    $hash = md5($conteudoImagem);
    $nomeImagem = $hash . '.' . $ext;
    $destino = '../../img/produtos/' . $nomeImagem;

    if (!file_exists($destino)) {
        move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);
    }

    $dados[':imagem'] = 'img/produtos/' . $nomeImagem;

    // Exclui a imagem antiga, se for diferente e não for a padrão
    if (!empty($produto['imagem']) &&
        $produto['imagem'] !== 'img/padrao/produto-sem-imagem.webp' &&
        $produto['imagem'] !== $dados[':imagem']) {

        $caminhoAntigo = '../../' . $produto['imagem'];
        if (file_exists($caminhoAntigo)) {
            unlink($caminhoAntigo);
        }
    }

} else {
    if (empty($produto['imagem'])) {
        $dados[':imagem'] = 'img/padrao/produto-sem-imagem.webp';
    } else {
        $dados[':imagem'] = $produto['imagem'];
    }
}

    try {
        $sql = "UPDATE produtos SET 
                nome = :nome,
                descricao = :descricao,
                preco = :preco,
                categoria = :categoria,
                imagem = :imagem
                WHERE id = :id";

        $pdo->prepare($sql)->execute($dados);
        header('Location: listar.php?sucesso=Produto atualizado!');
        exit;
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Editar Produto</title>
    <style>
        body {
            font-family: Arial;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }

        form {
            display: grid;
            gap: 15px;
        }

        input,
        textarea,
        select {
            padding: 10px;
            width: 100%;
        }

        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .erro {
            color: red;
        }

        .imagem-atual {
            max-width: 200px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <h1>Editar Produto</h1>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nome:
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </label>

        <label>Descrição:
            <textarea name="descricao" rows="4"><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </label>

        <label>Preço:
            <input type="text" name="preco" value="<?= number_format($produto['preco'], 2, ',', '.') ?>" required>
        </label>

        <!-- Substitua a parte do select de categorias por: -->
        <label>Categoria:
            <select name="categoria" required>
                <option value="prato" <?= $produto['categoria'] === 'prato' ? 'selected' : '' ?>>Pratos</option>
                <option value="salgado" <?= $produto['categoria'] === 'salgado' ? 'selected' : '' ?>>Salgados</option>
                <option value="doce" <?= $produto['categoria'] === 'doce' ? 'selected' : '' ?>>Doces</option>
                <option value="combo" <?= $produto['categoria'] === 'combo' ? 'selected' : '' ?>>Combos</option>
                <option value="bebida" <?= $produto['categoria'] === 'bebida' ? 'selected' : '' ?>>Bebidas</option>

            </select>
        </label>

        <label>Imagem atual:
            <img src="../../<?= htmlspecialchars($produto['imagem']) ?>" class="imagem-atual"
                onerror="this.src='../../img/padrao/produto-sem-imagem.webp'">
        </label>

        <label>Nova imagem (opcional):
            <input type="file" name="imagem" accept="image/*">
        </label>

        <button type="submit">Salvar Alterações</button>
    </form>

    <p><a href="listar.php">← Voltar para lista</a></p>
</body>

</html>