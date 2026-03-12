<?php
require 'config/db.php';

require 'vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;


MercadoPagoConfig::setAccessToken('APP_USR-65443282432-071219-djoodjgoiozodskplkk21323k3212'); // Substitua pelo seu Access Token real

$config = [
    'titulo_pagina' => "Cardápio - Gula Frangos",
    'telefone' => "5553997094326",
    'mp_public_key' => 'SUA_PUBLIC_KEY' 
];

try {
    // Busca produtos marcados como disponíveis
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE disponivel = 1 ORDER BY categoria, nome");
    $stmt->execute();
    $cardapio = $stmt->fetchAll();
    
    // Busca categorias de produtos disponíveis
    $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM produtos WHERE disponivel = 1 ORDER BY categoria");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    die("Erro ao carregar cardápio: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <style>
        :root {
    /* Cores principais (atualizadas para nomes semânticos) */
    --primary: #C82525;
    --primary-dark: #9e1d1d;
    --primary-light: #f8d7d7;
    --secondary: #D4A017;
    --secondary-dark: #b38712;
    --dark: #3A2D28;
    --light: #F8F5F0;
    --white: #FFFFFF;
    --gray: #6c757d;
    
    /* Espaçamentos */
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 2rem;
    --spacing-lg: 3rem;
    --spacing-xl: 4rem;
    
    /* Bordas */
    --border-radius: 8px;
    --border-radius-lg: 12px;
    
    /* Sombras */
    --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.2);
    
    /* Transições */
    --transition-fast: 0.2s ease;
    --transition-medium: 0.3s ease;
}

/* Reset básico */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Montserrat', sans-serif;
    line-height: 1.6;
    color: var(--dark);
    background-color: var(--light);
    overflow-x: hidden;
}

/* Container principal */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* Header */
.header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: var(--white);
    box-shadow: var(--shadow-sm);
    padding: var(--spacing-sm) 0;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo-text {
    display: flex;
    flex-direction: column;
    margin-left: var(--spacing-sm);
}

.logo-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2rem;
    color: var(--primary);
    letter-spacing: 1px;
    line-height: 1;
}

.logo-subtitle {
    display: flex;
    justify-content: space-between;
    font-size: 0.7rem;
    color: #D4A017; /* Mantém a cor dourada/amarela */
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0.2rem;
    gap: 0.5rem;
}

/* Navegação */
.nav {
    margin-left: auto; /* Alinha a navegação à direita */
    display: flex;
    align-items: center;
}

.nav-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin: 0 var(--spacing-sm);
}

.nav-link {
    position: relative;
    font-weight: 500;
    padding: var(--spacing-xs) 0;
    color: var(--dark);
    text-decoration: none;
}

.nav-link::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary);
    transition: width var(--transition-medium);
}

.nav-link:hover::after,
.nav-link.active::after {
    width: 100%;
}

/* Botão Mobile - Escondido por padrão */
.mobile-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--primary);
    cursor: pointer;
    padding: 0.5rem;
    position: absolute;
    right: var(--spacing-md);
    top: 50%;
    transform: translateY(-50%);
}

/* Banner do Cardápio */
.cardapio-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/frango-banner.jpg') center/cover;
    color: var(--white);
    padding: var(--spacing-xl) 0;
    text-align: center;
    margin-bottom: var(--spacing-lg);
}

.cardapio-header h1 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    margin-bottom: var(--spacing-sm);
    letter-spacing: 2px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.cardapio-header p {
    max-width: 600px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Filtro de Categorias */
.categorias {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
    margin: var(--spacing-lg) 0;
}

.categoria-btn {
    padding: 0.7rem 1.5rem;
    border: 2px solid var(--primary);
    border-radius: 50px;
    background: transparent;
    color: var(--primary);
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-medium);
}

.categoria-btn.active,
.categoria-btn:hover {
    background: var(--primary);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Grid de Itens do Cardápio */
.cardapio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-md);
    padding: var(--spacing-md) 0;
}

.cardapio-item {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-medium);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.cardapio-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.cardapio-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform var(--transition-medium);
}

.cardapio-item:hover .cardapio-img {
    transform: scale(1.03);
}

.cardapio-info {
    padding: var(--spacing-md);
}

.cardapio-info h3 {
    color: var(--primary);
    font-size: 1.3rem;
    margin-bottom: var(--spacing-xs);
}

.cardapio-info p {
    color: var(--gray);
    margin-bottom: var(--spacing-sm);
    font-size: 0.95rem;
}

.cardapio-preco {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--secondary);
    margin: var(--spacing-sm) 0;
}

.cardapio-btn {
    display: block;
    width: 100%;
    padding: 0.8rem;
    background: var(--primary);
    color: var(--white);
    text-align: center;
    border-radius: var(--border-radius);
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all var(--transition-medium);
}

.cardapio-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* Carrinho */
.carrinho-flutuante {
    position: fixed;
    top: 0;
    right: -400px;
    width: 380px;
    height: 100vh;
    background: var(--white);
    box-shadow: var(--shadow-lg);
    transition: right var(--transition-medium);
    z-index: 1100;
    display: flex;
    flex-direction: column;
}

.carrinho-flutuante.ativo {
    right: 0;
}

.carrinho-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.carrinho-header h3 {
    color: var(--primary);
}

#fechar-carrinho {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray);
    transition: color var(--transition-fast);
}

#fechar-carrinho:hover {
    color: var(--primary);
}

.carrinho-itens {
    flex: 1;
    overflow-y: auto;
    padding: var(--spacing-md);
}

.carrinho-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.carrinho-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--border-radius);
}

.carrinho-item-info {
    flex: 1;
    padding: 0 var(--spacing-sm);
}

.carrinho-item-info h4 {
    font-size: 1rem;
    color: var(--dark);
}

.carrinho-item-info p {
    font-size: 0.9rem;
    color: var(--gray);
}

.carrinho-item-remover {
    color: #ff4d4d;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    transition: transform var(--transition-fast);
}

.carrinho-item-remover:hover {
    transform: scale(1.1);
}

.carrinho-total {
    padding: var(--spacing-md);
    font-size: 1.2rem;
    text-align: right;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

#finalizar-pedido {
    margin: var(--spacing-md);
}

.carrinho-icone {
    position: fixed;
    bottom: var(--spacing-lg);
    right: var(--spacing-lg);
    background: var(--primary);
    color: var(--white);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: var(--shadow-md);
    z-index: 1050;
    transition: all var(--transition-medium);
}

.carrinho-icone:hover {
    transform: scale(1.1);
}

.carrinho-icone i {
    font-size: 1.5rem;
}

#carrinho-contador {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--secondary);
    color: var(--dark);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 0.8rem;
    font-weight: bold;
}

/* Footer */
.footer {
    background-color: var(--dark);
    color: var(--white);
    padding: var(--spacing-xl) 0 var(--spacing-md);
    margin-top: var(--spacing-xl);
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.footer-col h3 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    margin-bottom: var(--spacing-md);
    position: relative;
    padding-bottom: var(--spacing-xs);
}

.footer-col h3::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background-color: var(--secondary);
}

.footer-links {
    list-style: none;
}

.footer-link {
    margin-bottom: var(--spacing-xs);
    transition: color var(--transition-fast);
    color: rgba(255, 255, 255, 0.7);
}

.footer-link:hover {
    color: var(--secondary);
}

.social-links {
    display: flex;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
    transition: background-color var(--transition-fast);
    color: var(--white);
}

.social-link:hover {
    background-color: var(--primary);
    transform: scale(1.1);
}

.copyright {
    text-align: center;
    padding-top: var(--spacing-md);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Responsividade */
@media (max-width: 992px) {
    .cardapio-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .mobile-toggle {
        display: block;
    }
    
    /* Ajustes para o menu mobile */
    .nav {
        display: none;
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        background-color: var(--white);
        box-shadow: var(--shadow-md);
        flex-direction: column;
        align-items: flex-start;
        padding: var(--spacing-md);
        z-index: 999;
    }
    
    .nav.active {
        display: flex;
    }
    
    .nav-list {
        flex-direction: column;
        width: 100%;
        margin-right: 0;
        margin-bottom: var(--spacing-md);
    }
    
    .nav-item {
        margin: var(--spacing-sm) 0;
        width: 100%;
    }
    
    .nav-link {
        display: block;
        padding: var(--spacing-sm) 0;
    }
    
    /* Ajuste para o header em mobile */
    .header-container {
        position: relative;
        flex-direction: row;
        align-items: center;
        padding: 0 var(--spacing-sm);
    }
    
    /* Overlay para quando o menu estiver aberto */
    .nav-overlay {
        display: none;
        position: fixed;
        top: 80px;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 998;
    }
    
    .nav.active + .nav-overlay {
        display: block;
    }
    
    .cardapio-header {
        padding: var(--spacing-lg) 0;
    }
    
    .cardapio-header h1 {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .cardapio-grid {
        grid-template-columns: 1fr;
    }
    
    .carrinho-flutuante {
        width: 100%;
    }
    
    .carrinho-icone {
        bottom: var(--spacing-md);
        right: var(--spacing-md);
    }
}

/* Animação do ícone do carrinho */
.carrinho-icone.animate {
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Adicione ao style.css */
.cardapio-btn.adicionado {
    background-color: var(--accent);
    animation: btnPop 0.5s ease;
}

@keyframes btnPop {
    0% { transform: scale(1); }
    50% { transform: scale(0.95); }
    100% { transform: scale(1); }
}

/* Adicione estas regras ao final do seu style.css */

/* Melhorias para o Carrinho */
.carrinho-item-qtd {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
}

.qtd-btn {
    width: 25px;
    height: 25px;
    border: 1px solid var(--primary);
    background: white;
    border-radius: 50%;
    cursor: pointer;
    font-weight: bold;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
}

.qtd-btn:hover {
    background: var(--primary);
    color: white;
}

.qtd-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Melhor feedback visual para itens do carrinho */
.carrinho-item {
    transition: all var(--transition-fast);
}

.carrinho-item:hover {
    background-color: var(--primary-light);
}

/* Estilo para o botão de finalizar pedido */
#finalizar-pedido {
    background-color: var(--secondary);
    color: var(--dark);
    border: none;
    padding: 1rem;
    border-radius: var(--border-radius);
    font-weight: bold;
    cursor: pointer;
    transition: all var(--transition-medium);
    width: calc(100% - 2rem);
    margin: 1rem auto;
    text-align: center;
}

#finalizar-pedido:hover {
    background-color: var(--secondary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Feedback visual para quando o carrinho está vazio */
.carrinho-vazio {
    text-align: center;
    padding: 2rem;
    color: var(--gray);
}

/* Melhorias para os botões de categoria */
.categoria-btn {
    position: relative;
    overflow: hidden;
}

.categoria-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%, -50%);
    transform-origin: 50% 50%;
}

.categoria-btn:focus:not(:active)::after {
    animation: ripple 0.6s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    100% {
        transform: scale(20, 20);
        opacity: 0;
    }
}

/* Efeito de loading para o botão de finalizar pedido */
.loading {
    position: relative;
    pointer-events: none;
    color: transparent !important;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid var(--dark);
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Melhorias para responsividade do carrinho */
@media (max-width: 576px) {
    .carrinho-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .carrinho-item img {
        margin-bottom: 1rem;
    }
    
    .carrinho-item-info {
        width: 100%;
        padding: 0;
    }
    
    .carrinho-item-remover {
        align-self: flex-end;
    }
}

/* Adicione ao :root (se ainda não tiver) */
:root {
    --accent: #4CAF50; /* Cor para feedback positivo */
}

/* Efeito quando um item é adicionado ao carrinho */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.carrinho-item {
    animation: slideIn 0.3s ease-out forwards;
}

/* Modal de confirmação */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius-lg);
    width: 90%;
    max-width: 500px;
    box-shadow: var(--shadow-lg);
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.modal-overlay.active .modal-content {
    transform: translateY(0);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-title {
    color: var(--primary);
    font-size: 1.5rem;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray);
}

.modal-body {
    margin-bottom: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
}

.btn-modal {
    padding: 0.7rem 1.5rem;
    border-radius: var(--border-radius);
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all var(--transition-medium);
}

.btn-modal-primary {
    background: var(--primary);
    color: white;
}

.btn-modal-primary:hover {
    background: var(--primary-dark);
}

/* Estilo para mensagens de erro */
.error-message {
    color: #dc3545;
    font-size: 0.9rem;
    margin-top: 0.5rem;
    display: none;
}

/* Estilos para o formulário de finalização */
.form-container {
    padding: 20px;
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--dark);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(200, 37, 37, 0.2);
}

.form-radio-group {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.form-radio-input {
    appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid var(--primary);
    border-radius: 50%;
    position: relative;
    cursor: pointer;
}

.form-radio-input:checked::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 10px;
    height: 10px;
    background: var(--primary);
    border-radius: 50%;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.btn-form {
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-medium);
}

.btn-primary {
    background: var(--primary);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-secondary {
    background: white;
    color: var(--primary);
    border: 1px solid var(--primary);
}

.btn-secondary:hover {
    background: var(--primary-light);
}

#endereco-container {
    display: none;
    margin-top: 1rem;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Estilo para campos obrigatórios */
.required::after {
    content: ' *';
    color: var(--primary);
}

/* Validação */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}
    
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .logo-title {
        font-size: 1.5rem;
    }

    .container {
        padding: 0 1rem;
    }

    .card {
        flex-direction: column;
        text-align: center;
    }

    .card img {
        width: 100%;
        height: auto;
        margin-bottom: 1rem;
    }

    .preco {
        font-size: 1.2rem;
    }

    .btn-comprar {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
    }
}

</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['titulo_pagina']) ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="uploads/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="uploads/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos específicos do cardápio (mantidos iguais) */
        .cardapio-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/frango-banner.jpg') center/cover;
            color: white;
            padding: 5rem 0;
            text-align: center;
        }
        
        .cardapio-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .cardapio-header p {
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.2rem;
        }
        
        .categorias {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .categoria-btn {
            padding: 0.7rem 1.5rem;
            border: 2px solid var(--primary);
            border-radius: 50px;
            background: transparent;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .categoria-btn.active,
        .categoria-btn:hover {
            background: var(--primary);
            color: white;
        }
        
        .cardapio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }
        
        .cardapio-item {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .cardapio-item:hover {
            transform: translateY(-10px);
        }
        
        .cardapio-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .cardapio-info {
            padding: 1.5rem;
        }
        
        .cardapio-info h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }
        
        .cardapio-info p {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .cardapio-preco {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
            margin: 1rem 0;
        }
        
        .cardapio-btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background: var(--primary);
            color: white;
            text-align: center;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .cardapio-btn:hover {
            background: var(--primary-dark);
        }
        
        /* Animação para feedback ao adicionar item */
        .cardapio-btn.adicionado {
            background-color: #4CAF50;
            animation: pulse 0.5s;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }

        .pix-container {
            text-align: center;
            padding: 20px;
        }

        .pix-container img {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            display: block;
            border: 1px solid #eee;
        }

        .pix-info {
            margin: 15px 0;
            text-align: left;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .btn-copiar-pix {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-copiar-pix:hover {
            background: var(--primary-dark);
        }

        /* Botões de pagamento */
        .modal-pagamento {
            text-align: center;
        }

        .botoes-pagamento {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-mercado-pago {
            background: #009ee3;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-mercado-pago:hover {
            background: #0078b3;
        }

        .btn-whatsapp {
            background: #25D366;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-whatsapp:hover {
            background: #1da851;
        }

        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-container">
                <a href="index.php" class="logo">
                    <div class="logo-text">
                        <h1 class="logo-title">GULA FRANGOS</h1>
                        <div class="logo-subtitle">
                            <span>DESDE 2022</span>
                            <span>Assando com paixão</span>
                        </div>
                    </div>
                </a>
                
                <button class="mobile-toggle" id="mobileToggle" aria-label="Abrir menu de navegação">
                    <i class="fas fa-bars"></i>
                </button>
                
                <nav class="nav" id="nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="cardapio.php" class="nav-link active">Cardápio</a></li>
                        
                    </ul>
                </nav>
                <div class="nav-overlay" id="navOverlay"></div>
            </div>
        </div>
    </header>

    <!-- Banner do Cardápio -->
    <section class="cardapio-header">
        <div class="container">
            <div class="cardapio-header-content">
                <h1>Nosso Cardápio</h1>
                <p>Descubra uma explosão de sabores! No Gula Frangos, você encontra frango assado suculento, marmitas caseiras, salgados crocantes, doces irresistíveis – tudo feito com ingredientes frescos e o carinho que só nós temos!</p>
            </div>
        </div>
    </section>

    <!-- Categorias -->
    <div class="container">
        <div class="categorias">
            <button class="categoria-btn active" data-categoria="todos">Todos</button>
            <?php foreach ($categorias as $categoria): ?>
                <?php if (!empty($categoria)): ?>
                    <button class="categoria-btn" data-categoria="<?= htmlspecialchars($categoria) ?>">
                        <?= ucfirst(htmlspecialchars($categoria)) ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Itens do Cardápio -->
    <div class="container">
        <div class="cardapio-grid" id="cardapio-grid">
            <?php foreach ($cardapio as $item): ?>
                <div class="cardapio-item" data-categoria="<?= htmlspecialchars($item['categoria']) ?>" data-id="<?= $item['id'] ?>">
                    <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="cardapio-img">
                    <div class="cardapio-info">
                        <h3><?= htmlspecialchars($item['nome']) ?></h3>
                        <p><?= htmlspecialchars($item['descricao']) ?></p>
                        <span class="cardapio-preco">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                        <button class="cardapio-btn adicionar-carrinho"
                                data-id="<?= $item['id'] ?>"
                                data-nome="<?= htmlspecialchars($item['nome']) ?>"
                                data-preco="<?= $item['preco'] ?>"
                                data-imagem="<?= htmlspecialchars($item['imagem']) ?>">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Carrinho Flutuante -->
    <div id="carrinho-flutuante" class="carrinho-flutuante">
        <div class="carrinho-header">
            <h3>Seu Pedido</h3>
            <button id="fechar-carrinho">&times;</button>
        </div>
        <div class="carrinho-itens" id="carrinho-itens">
            <!-- Itens serão inseridos aqui via JS -->
        </div>
        <div class="carrinho-total">
            <span>Total: <strong id="carrinho-total">R$ 0,00</strong></span>
        </div>
        <button id="finalizar-pedido" class="btn btn-primary">Finalizar Pedido</button>
    </div>

    <!-- Ícone do Carrinho -->
    <div id="carrinho-icone" class="carrinho-icone">
        <i class="fas fa-shopping-cart"></i>
        <span id="carrinho-contador">0</span>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Gula Frangos</h3>
                    <p>Frangos e salgados artesanais preparados com ingredientes selecionados e muito amor.</p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/gula.frangos" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/_gulafrangos_/" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/<?= htmlspecialchars($config['telefone']) ?>" class="social-link" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Horário de Funcionamento</h3>
                    <ul class="footer-links">
                        <li>Segunda: Fechado | Ter-Dom: 11h às 14h</li>
                        
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contato</h3>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt contact-icon"></i>
                            <span>R. Navegantes Holts, 26 - Pelotas/RS</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone contact-icon"></i>
                            <span><?= preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', htmlspecialchars($config['telefone'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> Gula Frangos. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Adicione AQUI o aviso de localização -->
    <div id="aviso-localizacao" style="display: none; position: fixed; bottom: 20px; left: 20px; right: 20px; background: #fff3cd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;">
        <i class="fas fa-info-circle"></i> Para entregas, precisamos da sua localização. <strong>Por favor, compartilhe sua localização</strong> quando o navegador solicitar.
        <button id="fechar-aviso" style="float: right; background: none; border: none; cursor: pointer;">×</button>
    </div>
    
    <!-- Modal de confirmação -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title">Pedido Confirmado!</h3>
                <button class="modal-close" id="modal-close">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <p id="modal-message"></p>
                <div id="modal-details"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-primary" id="modal-confirm">OK</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        dadosClienteAtual: null,

   // Sistema de Carrinho Completo com Geolocalização
document.addEventListener('DOMContentLoaded', function() {
    const carrinho = {
        itens: JSON.parse(localStorage.getItem('carrinho')) || [],
        dadosClienteAtual: null,
        
        // ============= FUNÇÕES BÁSICAS DO CARRINHO =============
        adicionarItem: function(item) {
            const existente = this.itens.find(i => i.id === item.id);
            existente ? existente.quantidade++ : this.itens.push({...item, quantidade: 1});
            this.salvar();
            this.atualizarUI();
            this.mostrarFeedback(item.id);
        },
        
        removerItem: function(id) {
            this.itens = this.itens.filter(item => item.id !== id);
            this.salvar();
            this.atualizarUI();
        },
        
        atualizarQuantidade: function(id, novaQuantidade) {
            const item = this.itens.find(i => i.id === id);
            if (item) {
                if (novaQuantidade <= 0) {
                    this.removerItem(id);
                } else {
                    item.quantidade = novaQuantidade;
                    this.salvar();
                    this.atualizarUI();
                }
            }
        },
        
        salvar: function() {
            localStorage.setItem('carrinho', JSON.stringify(this.itens));
        },
        
        calcularTotal: function() {
            return this.itens.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
        },
        
        // ============= INTERFACE DO USUÁRIO =============
        atualizarUI: function() {
            const totalItens = this.itens.reduce((acc, item) => acc + item.quantidade, 0);
            document.getElementById('carrinho-contador').textContent = totalItens;
            
            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');
            
            if (this.itens.length > 0) {
                carrinhoItens.innerHTML = this.itens.map(item => `
                    <div class="carrinho-item" data-id="${item.id}">
                        <img src="${item.imagem}" alt="${item.nome}">
                        <div class="carrinho-item-info">
                            <h4>${item.nome}</h4>
                            <div class="carrinho-controle">
                                <button class="qtd-btn" onclick="carrinho.atualizarQuantidade('${item.id}', ${item.quantidade - 1})">-</button>
                                <span class="qtd-valor">${item.quantidade}</span>
                                <button class="qtd-btn" onclick="carrinho.atualizarQuantidade('${item.id}', ${item.quantidade + 1})">+</button>
                            </div>
                            <span class="item-total">R$ ${(item.preco * item.quantidade).toFixed(2)}</span>
                        </div>
                        <button class="carrinho-item-remover" onclick="carrinho.removerItem('${item.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
                
                carrinhoTotal.innerHTML = `R$ ${this.calcularTotal().toFixed(2)}`;
            } else {
                carrinhoItens.innerHTML = '<p class="carrinho-vazio">Seu carrinho está vazio</p>';
                carrinhoTotal.innerHTML = 'R$ 0,00';
                document.getElementById('carrinho-flutuante').classList.remove('aberto');
            }
        },
        
        mostrarFeedback: function(id) {
            const btn = document.querySelector(`.cardapio-btn[data-id="${id}"]`);
            if (btn) {
                btn.classList.add('adicionado');
                setTimeout(() => btn.classList.remove('adicionado'), 500);
            }
            document.getElementById('carrinho-icone').classList.add('animate');
            setTimeout(() => document.getElementById('carrinho-icone').classList.remove('animate'), 500);
        },
        
        // ============= GEOLOCALIZAÇÃO =============
        capturarLocalizacao: function() {
            return new Promise((resolve, reject) => {
                const options = {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                };

                const watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        navigator.geolocation.clearWatch(watchId);
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        navigator.geolocation.clearWatch(watchId);
                        reject(error);
                    },
                    options
                );

                setTimeout(() => {
                    navigator.geolocation.clearWatch(watchId);
                    reject(new Error("Tempo limite excedido para obter localização"));
                }, 20000);
            });
        },

        // ============= FINALIZAÇÃO DE PEDIDO =============
        finalizarPedido: function() {
            if (this.itens.length === 0) {
                this.showModal('Carrinho Vazio', 'Adicione itens ao carrinho para continuar.', 'error');
                return;
            }

            this.coletarDadosCliente()
                .then(dadosCliente => {
                    this.enviarParaWhatsapp(dadosCliente);
                })
                .catch(error => {
                    if (error.message !== 'Usuário cancelou') {
                        this.showModal('Erro', error.message, 'error');
                    }
                });
        },

        // ============= FUNÇÕES DE REGISTRO E WHATSAPP =============
        registrarPedido: function(dadosCliente) {
            return new Promise((resolve, reject) => {
                if (!dadosCliente.nome || !dadosCliente.telefone) {
                    return reject(new Error('Dados insuficientes para registrar o pedido'));
                }

                const pedidoData = {
                    cliente_nome: dadosCliente.nome,
                    telefone: dadosCliente.telefone,
                    itens: JSON.stringify(this.itens),
                    total: this.calcularTotal(),
                    tipo_entrega: dadosCliente.tipo_entrega || 'retirada',
                    endereco: dadosCliente.endereco || null,
                    referencia: dadosCliente.referencia || null,
                    observacoes: dadosCliente.observacoes || null,
                    forma_pagamento: dadosCliente.forma_pagamento || null,
                    latitude: dadosCliente.latitude || null,
                    longitude: dadosCliente.longitude || null,
                    status: 'recebido'
                };

                fetch('admin/pedidos/registrar_pedido.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(pedidoData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        resolve(data.id_pedido);
                    } else {
                        reject(new Error(data.erro || 'Erro ao registrar pedido'));
                    }
                })
                .catch(error => reject(error));
            });
        },

        enviarParaWhatsapp: async function(dadosCliente) {
            try {
                let coords = null;
                
                if (dadosCliente.tipo_entrega === 'entrega') {
                    try {
                        this.showModal('Localização', 'Obtendo sua localização...', 'loading');
                        coords = await this.capturarLocalizacao();
                        document.getElementById('modal-overlay').classList.remove('active');
                    } catch (error) {
                        document.getElementById('modal-overlay').classList.remove('active');
                        const endereco = await new Promise(resolve => {
                            const modalContent = `
                                <div class="form-group">
                                    <label class="form-label required">Endereço Completo</label>
                                    <textarea id="endereco-manual" class="form-control" rows="3" required></textarea>
                                    <div class="invalid-feedback">Por favor, informe seu endereço completo</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ponto de Referência</label>
                                    <input type="text" id="referencia-manual" class="form-control" placeholder="Ex: Próximo ao mercado X">
                                </div>
                                <div class="form-actions">
                                    <button id="confirmar-endereco" class="btn-form btn-primary">Confirmar</button>
                                </div>
                            `;
                            
                            this.showModal('Informe seu Endereço', modalContent, 'info');
                            
                            document.getElementById('confirmar-endereco').addEventListener('click', () => {
                                const endereco = document.getElementById('endereco-manual').value.trim();
                                if (endereco) {
                                    document.getElementById('modal-overlay').classList.remove('active');
                                    resolve({
                                        endereco,
                                        referencia: document.getElementById('referencia-manual').value.trim()
                                    });
                                } else {
                                    document.getElementById('endereco-manual').classList.add('is-invalid');
                                    document.querySelector('#endereco-manual + .invalid-feedback').style.display = 'block';
                                }
                            });
                        });
                        
                        dadosCliente.endereco = endereco.endereco;
                        dadosCliente.referencia = endereco.referencia;
                    }
                }

                const pedidoId = await this.registrarPedido({
                    ...dadosCliente,
                    latitude: coords?.latitude || null,
                    longitude: coords?.longitude || null
                });

                let mensagem = `🍗 *NOVO PEDIDO - GULA FRANGOS* 🍗\n\n` +
                               `📋 *Nº do Pedido:* ${pedidoId}\n` +
                               `👤 *Cliente:* ${dadosCliente.nome}\n` +
                               `📞 *Telefone:* ${dadosCliente.telefone}\n` +
                               `🚚 *Tipo:* ${dadosCliente.tipo_entrega === 'entrega' ? 'Entrega' : 'Retirada'}\n`;

                if (dadosCliente.tipo_entrega === 'entrega') {
                    if (coords) {
                        mensagem += `📍 *Localização*: https://maps.google.com?q=${coords.latitude},${coords.longitude}\n`;
                    } else if (dadosCliente.endereco) {
                        mensagem += `🏠 *Endereço:* ${dadosCliente.endereco}\n`;
                        if (dadosCliente.referencia) {
                            mensagem += `📍 *Referência:* ${dadosCliente.referencia}\n`;
                        }
                    }
                }

                if (dadosCliente.observacoes) {
                    mensagem += `📝 *Observações:* ${dadosCliente.observacoes}\n`;
                }

                if (dadosCliente.forma_pagamento) {
                    mensagem += `💳 *Pagamento preferencial:* ${dadosCliente.forma_pagamento}\n`;
                }

                mensagem += `\n📦 *ITENS:*\n${this.itens.map(item => 
                    `➤ ${item.nome} (${item.quantidade}x) - R$ ${(item.preco * item.quantidade).toFixed(2)}`
                ).join('\n')}\n\n` +
                `💰 *TOTAL: R$ ${this.calcularTotal().toFixed(2)}*`;
                    
                window.open(`https://wa.me/5553997094326?text=${encodeURIComponent(mensagem)}`, '_blank');
                this.limparCarrinho();
            } catch (error) {
                this.showModal('Erro', error.message, 'error');
            }
        },

        // ============= COLETA DE DADOS DO CLIENTE =============
        coletarDadosCliente: function(dadosPrePreenchidos = null) {
    return new Promise((resolve, reject) => {
        const ultimoCliente = dadosPrePreenchidos || JSON.parse(localStorage.getItem('ultimo_cliente'));

                
                const formHTML = `
                    <div class="form-container" style="max-height: 70vh; overflow-y: auto; padding-right: 10px;">
                        <form id="cliente-form" style="display: grid; gap: 12px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="nome" class="form-label required">Nome</label>
                                <input type="text" id="nome" class="form-control" value="${ultimoCliente?.nome || ''}" required>
                                <div class="invalid-feedback">Por favor, digite seu nome</div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="telefone" class="form-label required">Telefone</label>
                                <input type="tel" id="telefone" class="form-control" value="${ultimoCliente?.telefone || ''}" required>
                                <div class="invalid-feedback">Digite um telefone válido</div>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label required">Tipo de Pedido</label>
                                <div class="form-radio-group" style="display: flex; gap: 15px;">
                                    <label class="form-radio-label">
                                        <input type="radio" name="tipo_entrega" value="retirada" ${(!ultimoCliente || ultimoCliente.tipo_entrega === 'retirada') ? 'checked' : ''}>
                                        Retirada
                                    </label>
                                    <label class="form-radio-label">
                                        <input type="radio" name="tipo_entrega" value="entrega" ${(ultimoCliente && ultimoCliente.tipo_entrega === 'entrega') ? 'checked' : ''}>
                                        Entrega
                                    </label>
                                </div>
                            </div>
                            
                            <div id="endereco-container" class="form-group" style="margin-bottom: 0; display: ${(ultimoCliente && ultimoCliente.tipo_entrega === 'entrega') ? 'grid' : 'none'}; gap: 8px;">
                                <label for="endereco" class="form-label required">Endereço</label>
                                <textarea id="endereco" class="form-control" rows="2" style="min-height: 60px;">${ultimoCliente?.endereco || ''}</textarea>
                                <div class="invalid-feedback">Informe seu endereço</div>
                                
                                <label for="referencia" class="form-label">Referência (Opcional)</label>
                                <input type="text" id="referencia" class="form-control" placeholder="Ex: Próximo ao mercado X" value="${ultimoCliente?.referencia || ''}">
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="observacoes" class="form-label">Observações (Opcional)</label>
                                <textarea id="observacoes" class="form-control" rows="1" style="min-height: 40px;" placeholder="Ex: Sem cebola, alergia a camarão...">${ultimoCliente?.observacoes || ''}</textarea>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
                                <select id="forma_pagamento" class="form-control" style="padding: 8px 12px;">
                                    ${(!ultimoCliente || ultimoCliente.tipo_entrega === 'retirada') ? `
                                        <option value="">Selecione...</option>
                                        <option value="Pix" ${(ultimoCliente?.forma_pagamento === 'Pix') ? 'selected' : ''}>Pix</option>
                                        <option value="Dinheiro" ${(ultimoCliente?.forma_pagamento === 'Dinheiro') ? 'selected' : ''}>Dinheiro</option>
                                        <option value="Cartão" ${(ultimoCliente?.forma_pagamento === 'Cartão') ? 'selected' : ''}>Cartão</option>
                                    ` : `
                                        <option value="Pix" selected>Pix (Obrigatório para entrega)</option>
                                    `}
                                </select>
                                <small id="pagamento-aviso" style="display: ${(ultimoCliente && ultimoCliente.tipo_entrega === 'entrega') ? 'block' : 'none'}; color: #666; margin-top: 5px;">
                                    Para entregas aceitamos apenas Pix
                                </small>
                                <div id="pix-button-container" style="margin-top: 10px;"></div>
                            </div>
                            
                            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 10px;">
                                <button type="button" id="cancelar-form" class="btn-form btn-secondary" style="flex: 1;">Cancelar</button>
                                <button type="submit" class="btn-form btn-primary" style="flex: 1;">Enviar Pedido</button>
                            </div>
                        </form>
                    </div>
                `;
                
                this.showModal('Informações do Pedido', formHTML, 'info');
                
                setTimeout(() => {
                    const form = document.getElementById('cliente-form');
                    const tipoEntregaRadios = document.querySelectorAll('input[name="tipo_entrega"]');
                    const enderecoContainer = document.getElementById('endereco-container');
                    const formaPagamento = document.getElementById('forma_pagamento');
                    const pagamentoAviso = document.getElementById('pagamento-aviso');
                    const pixButtonContainer = document.getElementById('pix-button-container');
                    const cancelarBtn = document.getElementById('cancelar-form');
                    
                    // Função para atualizar o botão da chave PIX
                    const atualizarBotaoPix = () => {
                        const isEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value === 'entrega';
                        const isPix = formaPagamento.value === 'Pix';
                        
                        if (isEntrega || isPix) {
                            pixButtonContainer.innerHTML = `
                                <button type="button" onclick="carrinho.mostrarChavePix()" 
                                        style="background: none; border: none; color: var(--primary); 
                                               text-decoration: underline; cursor: pointer; padding: 0;">
                                    <i class="fas fa-qrcode"></i> Ver chave PIX
                                </button>
                            `;
                        } else {
                            pixButtonContainer.innerHTML = '';
                        }
                    };
                    
                    // Atualiza campos quando muda o tipo de entrega
                    tipoEntregaRadios.forEach(radio => {
                        radio.addEventListener('change', () => {
                            const isEntrega = radio.value === 'entrega';
                            
                            // Mostra/oculta endereço
                            enderecoContainer.style.display = isEntrega ? 'grid' : 'none';
                            
                            // Atualiza opções de pagamento
                            if (isEntrega) {
                                formaPagamento.innerHTML = '<option value="Pix" selected>Pix (Obrigatório para entrega)</option>';
                                pagamentoAviso.style.display = 'block';
                            } else {
                                formaPagamento.innerHTML = `
                                    <option value="">Selecione...</option>
                                    <option value="Pix">Pix</option>
                                    <option value="Dinheiro">Dinheiro</option>
                                    <option value="Cartão">Cartão</option>
                                `;
                                pagamentoAviso.style.display = 'none';
                                
                                // Restaura seleção anterior se existir
                                if (ultimoCliente?.forma_pagamento) {
                                    formaPagamento.value = ultimoCliente.forma_pagamento;
                                }
                            }
                            
                            // Atualiza botão PIX
                            atualizarBotaoPix();
                            
                            // Limpa endereço se for retirada
                            if (!isEntrega) {
                                document.getElementById('endereco').value = '';
                                document.getElementById('referencia').value = '';
                            }
                        });
                    });
                    
                    // Atualiza quando muda a forma de pagamento
                    formaPagamento.addEventListener('change', atualizarBotaoPix);
                    
                    // Validação do formulário
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        
                        let isValid = true;
                        const nome = document.getElementById('nome').value.trim();
                        const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
                        const tipo_entrega = document.querySelector('input[name="tipo_entrega"]:checked').value;
                        const endereco = document.getElementById('endereco').value.trim();
                        const referencia = document.getElementById('referencia').value.trim();
                        const observacoes = document.getElementById('observacoes').value.trim();
                        const forma_pagamento = document.getElementById('forma_pagamento').value;
                        
                        // Validações
                        if (!nome) {
                            document.getElementById('nome').classList.add('is-invalid');
                            document.querySelector('#nome + .invalid-feedback').style.display = 'block';
                            isValid = false;
                        } else {
                            document.getElementById('nome').classList.remove('is-invalid');
                            document.querySelector('#nome + .invalid-feedback').style.display = 'none';
                        }
                        
                        if (!telefone || telefone.length < 10 || telefone.length > 11) {
                            document.getElementById('telefone').classList.add('is-invalid');
                            document.querySelector('#telefone + .invalid-feedback').style.display = 'block';
                            isValid = false;
                        } else {
                            document.getElementById('telefone').classList.remove('is-invalid');
                            document.querySelector('#telefone + .invalid-feedback').style.display = 'none';
                        }
                        
                        if (tipo_entrega === 'entrega' && !endereco) {
                            document.getElementById('endereco').classList.add('is-invalid');
                            document.querySelector('#endereco + .invalid-feedback').style.display = 'block';
                            isValid = false;
                        } else {
                            document.getElementById('endereco').classList.remove('is-invalid');
                            document.querySelector('#endereco + .invalid-feedback').style.display = 'none';
                        }
                        
                        if (isValid) {
                            const dadosCliente = { 
                                nome, 
                                telefone, 
                                tipo_entrega, 
                                endereco: tipo_entrega === 'entrega' ? endereco : '',
                                referencia: tipo_entrega === 'entrega' ? referencia : '',
                                observacoes,
                                forma_pagamento: forma_pagamento || null
                            };
                            
                            localStorage.setItem('ultimo_cliente', JSON.stringify(dadosCliente));
                            document.getElementById('modal-overlay').classList.remove('active');
                            resolve(dadosCliente);
                        }
                    });
                    
                    // Botão cancelar
                    cancelarBtn.addEventListener('click', () => {
                        document.getElementById('modal-overlay').classList.remove('active');
                        reject(new Error('Usuário cancelou'));
                    });
                    
                    // Máscara para telefone
                    const telefoneInput = document.getElementById('telefone');
                    telefoneInput.addEventListener('input', function(e) {
                        let value = this.value.replace(/\D/g, '');
                        if (value.length > 11) value = value.substring(0, 11);
                        
                        if (value.length > 2) {
                            value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
                        }
                        if (value.length > 10) {
                            value = `${value.substring(0, 10)}-${value.substring(10)}`;
                        }
                        
                        this.value = value;
                    });
                    
                    // Atualiza o botão PIX inicialmente
                    atualizarBotaoPix();
                    
                }, 50);
            });
        },

        // ============= FUNÇÃO PARA MOSTRAR CHAVE PIX =============
       mostrarChavePix: function() {
    this.dadosClienteAtual = {
        nome: document.getElementById('nome')?.value.trim(),
        telefone: document.getElementById('telefone')?.value.replace(/\D/g, ''),
        tipo_entrega: document.querySelector('input[name="tipo_entrega"]:checked')?.value,
        endereco: document.getElementById('endereco')?.value.trim(),
        referencia: document.getElementById('referencia')?.value.trim(),
        observacoes: document.getElementById('observacoes')?.value.trim(),
        forma_pagamento: document.getElementById('forma_pagamento')?.value
    };

    const chavePix = '6ffc8a65-372a-44ff-8512-d55e70b71d8b'; // Substitua pela sua chave real
    const totalPedido = this.calcularTotal().toFixed(2);

    this.showModal('Pagamento via PIX', `
        <div class="pix-container">
            <h4 style="margin-bottom: 15px; color: var(--primary);">Faça o pagamento via PIX</h4>
            <p style="margin-bottom: 20px;">Valor total do pedido: <strong>R$ ${totalPedido}</strong></p>

            <div class="pix-info">
                <p><strong>Chave PIX:</strong></p>
                <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                    <input type="text" id="chave-pix" value="${chavePix}" readonly 
                        style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-weight: bold;">
                    <button class="btn-copiar-pix" onclick="copiarChavePix()">
                        <i class="fas fa-copy"></i> Copiar
                    </button>
                </div>
                <p style="margin-top: 15px; font-size: 0.9em; color: #666;">
                    Após realizar o pagamento, envie o comprovante pelo WhatsApp para agilizar seu pedido.
                </p>
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: center;">
                <button onclick="carrinho.voltarParaFormulario()" 
                        style="padding: 10px 20px; background: var(--primary); color: white; 
                                border: none; border-radius: 5px; cursor: pointer;">
                    <i class="fas fa-arrow-left"></i> Voltar ao formulário
                </button>
            </div>
        </div>
    `, 'info');
}
,

       voltarParaFormulario: function() {
    document.getElementById('modal-overlay').classList.remove('active');

    // Usa os dados salvos para preencher o formulário novamente
    const clienteAnterior = this.dadosClienteAtual;
    this.dadosClienteAtual = null; // Limpa para a próxima vez

    this.coletarDadosCliente(clienteAnterior).then(dados => {
        this.enviarParaWhatsapp(dados); // Continua processo de finalização
    }).catch(() => {});
},


        limparCarrinho: function() {
            this.itens = [];
            this.salvar();
            this.atualizarUI();
        },
        
        showModal: function(title, message, type = 'info', callback = null) {
            const modal = document.getElementById('modal-overlay');
            const modalTitle = document.getElementById('modal-title');
            const modalBody = document.getElementById('modal-body');
            const modalConfirm = document.getElementById('modal-confirm');
            const modalClose = document.getElementById('modal-close');
            
            const colors = {
                info: 'var(--primary)',
                error: '#dc3545',
                success: '#28a745',
                loading: '#17a2b8',
                warning: '#ffc107'
            };
            
            modalTitle.style.color = colors[type] || colors.info;
            modalTitle.textContent = title;
            
            // Limpa o conteúdo anterior
            while (modalBody.firstChild) {
                modalBody.removeChild(modalBody.firstChild);
            }
            
            // Adiciona o novo conteúdo
            const contentDiv = document.createElement('div');
            contentDiv.innerHTML = message;
            modalBody.appendChild(contentDiv);
            
            // Configura o botão de confirmação
            if (type === 'loading') {
                modalConfirm.style.display = 'none';
                modalClose.style.display = 'none';
            } else if (type === 'info') {
                // Esconde os botões padrão para modais do tipo info (incluindo o formulário)
                modalConfirm.style.display = 'none';
                modalClose.style.display = 'none';
            } else {
                modalConfirm.style.display = 'block';
                modalClose.style.display = 'block';
                
                modalConfirm.textContent = type === 'error' ? 'Entendi' : 'OK';
                modalConfirm.onclick = () => {
                    modal.classList.remove('active');
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                };
            }
            
            modalClose.onclick = () => {
                modal.classList.remove('active');
            };
            
            modal.classList.add('active');
        }
    };

    // ============= INICIALIZAÇÃO =============
    carrinho.atualizarUI();
    
    // Event Listeners
    document.getElementById('carrinho-icone').addEventListener('click', function() {
        document.getElementById('carrinho-flutuante').classList.toggle('aberto');
    });
    
    document.getElementById('fechar-carrinho').addEventListener('click', function() {
        document.getElementById('carrinho-flutuante').classList.remove('aberto');
    });
    
    document.getElementById('finalizar-pedido').addEventListener('click', function() {
        carrinho.finalizarPedido();
    });

    document.querySelectorAll('.adicionar-carrinho').forEach(btn => {
        btn.addEventListener('click', function() {
            carrinho.adicionarItem({
                id: this.dataset.id,
                nome: this.dataset.nome,
                preco: parseFloat(this.dataset.preco),
                imagem: this.dataset.imagem,
                categoria: this.dataset.categoria
            });
        });
    });

    document.querySelectorAll('.categoria-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.categoria-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const categoria = this.dataset.categoria;
            document.querySelectorAll('.cardapio-item').forEach(item => {
                item.style.display = (categoria === 'todos' || item.dataset.categoria === categoria) 
                    ? 'block' 
                    : 'none';
            });
        });
    });

    // Função global para copiar a chave PIX
    window.copiarChavePix = function() {
        const chaveInput = document.getElementById('chave-pix');
        chaveInput.select();
        document.execCommand('copy');
        
        // Feedback visual
        const btn = document.querySelector('.btn-copiar-pix');
        btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
        }, 2000);
    };

    window.carrinho = carrinho;
});

// Menu Mobile Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const nav = document.getElementById('nav');
    const navOverlay = document.getElementById('navOverlay');
    
    // Toggle do menu mobile
    mobileToggle.addEventListener('click', function() {
        nav.classList.toggle('active');
        navOverlay.style.display = nav.classList.contains('active') ? 'block' : 'none';
        this.querySelector('i').classList.toggle('fa-bars');
        this.querySelector('i').classList.toggle('fa-times');
    });
    
    // Fechar menu ao clicar no overlay
    navOverlay.addEventListener('click', function() {
        nav.classList.remove('active');
        this.style.display = 'none';
        mobileToggle.querySelector('i').classList.add('fa-bars');
        mobileToggle.querySelector('i').classList.remove('fa-times');
    });
    
    // Fechar menu ao redimensionar a tela para tamanho maior
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            nav.classList.remove('active');
            navOverlay.style.display = 'none';
            mobileToggle.querySelector('i').classList.add('fa-bars');
            mobileToggle.querySelector('i').classList.remove('fa-times');
        }
    });
});
    </script>
</body>
</html>