<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados básicos (sem 'disponivel')
    $dados = [
        ':nome' => $_POST['nome'],
        ':descricao' => $_POST['descricao'],
        ':preco' => str_replace(',', '.', $_POST['preco']),
        ':categoria' => $_POST['categoria']
    ];

    // Upload da imagem
    if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid().'.'.$ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../../img/produtos/'.$nomeImagem);
        $dados[':imagem'] = 'img/produtos/'.$nomeImagem;
    } else {
    // Se NÃO enviou imagem, usa a padrão
    $dados[':imagem'] = 'img/padrao/produto-sem-imagem.webp';
}

    try {
        // Query sem a coluna 'disponivel'
        $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, imagem) 
                VALUES (:nome, :descricao, :preco, :categoria, :imagem)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($dados);
        
        header('Location: listar.php?sucesso=Produto cadastrado!');
        exit;
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Produto</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 20px auto; padding: 20px; }
        form { display: grid; gap: 15px; }
        input, textarea, select { padding: 10px; width: 100%; }
        button { background: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer; }
        .erro { color: red; }
    </style>
</head>
<body>
    <h1>Cadastrar Produto</h1>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    
    <form method="post" enctype="multipart/form-data">
        <label>Nome: <input type="text" name="nome" required></label>
        <label>Descrição: <textarea name="descricao" rows="4"></textarea></label>
        <label>Preço: <input type="text" name="preco" placeholder="0.00" required></label>
       <!-- Substitua a parte do select de categorias por: -->
<label>Categoria:
    <select name="categoria" required>
        <option value="prato">Pratos</option>
        <option value="salgado">Salgados</option>
        <option value="doce">Doces</option>
        <option value="combo">Combos</option>
        <option value="bebida">Bebidas</option>
    </select>
</label>
        <label>Imagem: <input type="file" name="imagem" accept="image/*"></label>
        <!-- REMOVIDO: Checkbox de disponível -->
        <button type="submit">Salvar</button>
    </form>
    
    <p><a href="listar.php">← Voltar para lista</a></p>
</body>
</html>