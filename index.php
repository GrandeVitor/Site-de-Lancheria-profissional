<?php
// ===== CONEXÃO COM O BANCO ===== //
require 'config/db.php';

// ===== CONFIGURAÇÕES GERAIS ===== //
$config = [
    'titulo' => "Gula Frangos",
    'descricao' => "Frangos e salgados artesanais temperados no ponto e assados na hora. Delivery rápido na região de Pelotas.",
    'telefone' => "5553997094326",
    'whatsapp_msg' => "Olá! Gostaria de fazer um pedido.",
    'endereco' => "R. Navegantes Holts, 26 - Pelotas/RS",
    'horario_funcionamento' => "Segunda: Fechado | Ter-Dom: 11h às 14h",
    'email' => "contato@gulafrangos.com.br",
    'instagram' => "https://www.instagram.com/_gulafrangos_/",
    'facebook' => "https://www.facebook.com/gula.frangos"
];

// ===== BUSCA DE DADOS ===== //
try {
    // Produtos disponíveis (limitado para a página inicial)
    $destaques = $pdo->query("SELECT * FROM produtos WHERE destaque = 1 AND disponivel = 1 LIMIT 4")->fetchAll();
    
    // Depoimentos ativos
} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $config['descricao'] ?>">
    <title><?= $config['titulo'] ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="uploads/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="uploads/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== VARIÁVEIS E RESET ===== */
        :root {
            /* Cores */
            --primary: #C82525;
            --primary-dark: #9e1d1d;
            --accent: #D4A017;
            --accent-dark: #b38712;
            --neutral-dark: #3A2D28;
            --neutral-light: #F8F5F0;
            --white: #FFFFFF;
            --black: #000000;
            
            /* Tipografia */
            --heading-font: 'Bebas Neue', sans-serif;
            --body-font: 'Montserrat', sans-serif;
            
            /* Espaçamentos */
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 2rem;
            --spacing-lg: 3rem;
            --spacing-xl: 4rem;
            
            /* Sombras */
            --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.2);
            
            /* Transições */
            --transition-fast: 0.2s ease;
            --transition-medium: 0.3s ease;
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: var(--body-font);
            color: var(--neutral-dark);
            background-color: var(--neutral-light);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        a {
            text-decoration: none;
            color: inherit;
            transition: color var(--transition-fast);
        }
        
        button, .btn {
            cursor: pointer;
            border: none;
            font-family: inherit;
            transition: all var(--transition-medium);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
        }
        
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
        
        /* ===== COMPONENTES REUTILIZÁVEIS ===== */
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--white);
        }
        
        /* Estilo do botão WhatsApp */
.btn-whatsapp {
    background-color: #25D366;
    color: white;
    border: none;
    width: 100%;
    padding: 0.8rem;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-whatsapp:hover {
    background-color: #1da851;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Mantenha o .btn-call se ainda for usado em outros lugares */
.btn-call {
    background-color: var(--accent);
    color: var(--neutral-dark);
}
        
        .btn-call:hover {
            background-color: var(--accent-dark);
        }
        
        .section {
            padding: var(--spacing-xl) 0;
        }
        
        .section-title {
            font-family: var(--heading-font);
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: var(--spacing-md);
            color: var(--primary);
            position: relative;
            letter-spacing: 1px;
        }
        
        .section-title::after {
            content: "";
            display: block;
            width: 80px;
            height: 3px;
            background-color: var(--accent);
            margin: var(--spacing-xs) auto 0;
        }
        
        /* ===== HEADER ===== */
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: var(--white);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-medium);
        }
        
        .header.scrolled {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-md);
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-sm) 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
            margin-left: var(--spacing-sm);
        }
        
        .logo-title {
            font-family: var(--heading-font);
            font-size: 2rem;
            color: var(--primary);
            letter-spacing: 1px;
            line-height: 1;
        }
        
        .logo-subtitle {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.2rem;
            gap: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
        }
        
        .nav-list {
            display: flex;
            list-style: none;
            margin-right: var(--spacing-md);
        }
        
        .nav-item {
            margin: 0 var(--spacing-sm);
        }
        
        .nav-link {
            position: relative;
            font-weight: 500;
            padding: 0.5rem 0;
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
        
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }
        
        /* Dropdown de contato */
        .contact-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .cta-header {
            display: flex;
            align-items: center;
            background-color: var(--accent);
            color: var(--neutral-dark);
            padding: 0.5rem var(--spacing-sm);
            border-radius: 50px;
            font-weight: 600;
            transition: all var(--transition-medium);
        }
        
        .cta-header:hover {
            background-color: var(--accent-dark);
            transform: scale(1.05);
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background: var(--white);
            min-width: 200px;
            box-shadow: var(--shadow-md);
            border-radius: 8px;
            z-index: 1000;
            right: 0;
            overflow: hidden;
        }
        
        .dropdown-content a {
            color: var(--neutral-dark);
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .dropdown-content a:hover {
            background: var(--neutral-light);
        }
        
        .dropdown-content a i {
            width: 20px;
            text-align: center;
        }
        
        .contact-dropdown:hover .dropdown-content {
            display: block;
        }
        
        .cta-icon {
            margin-right: 0.5rem;
        }
        
        /* ===== HERO SECTION ===== */
        .hero {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            overflow: hidden;
background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('uploads/gulafrango2.1.png') center/contain no-repeat;
background-color: #FAAB12; /* para evitar fundo branco */
            color: var(--white);
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 650px;
            padding: var(--spacing-xl) 0;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: var(--spacing-sm);
            color: var(--accent);
        }
        
        .hero-title {
            font-family: var(--heading-font);
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            line-height: 1.1;
            margin-bottom: var(--spacing-md);
            letter-spacing: 1px;
        }
        
        .hero-text {
            font-size: 1.1rem;
            margin-bottom: var(--spacing-lg);
            max-width: 600px;
        }
        
        .hero-buttons {
            display: flex;
            gap: var(--spacing-sm);
        }
        
        /* ===== FEATURES SECTION ===== */
        .features {
            background-color: var(--white);
            position: relative;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--spacing-lg);
            margin-top: var(--spacing-lg);
        }
        
        .feature-card {
            background-color: var(--neutral-light);
            border-radius: 12px;
            padding: var(--spacing-lg);
            text-align: center;
            transition: transform var(--transition-medium), box-shadow var(--transition-medium);
            box-shadow: var(--shadow-sm);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-md);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: var(--spacing-sm);
            transition: transform var(--transition-medium);
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .feature-title {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-sm);
            color: var(--primary);
        }
        
        /* ===== MENU SECTION ===== */
.menu {
    background-color: var(--neutral-light);
    position: relative;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--spacing-lg);
    justify-items: center;
}

.menu-item {
    width: 100%;
    max-width: 300px;
    background-color: var(--white);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
}

.menu-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

/* Restante do CSS permanece igual */
.menu-img {
    height: 200px;
    width: 100%;
    object-fit: cover;
    transition: transform var(--transition-medium);
}

.menu-item:hover .menu-img {
    transform: scale(1.05);
}

.menu-content {
    padding: var(--spacing-md);
}

.menu-title {
    font-size: 1.3rem;
    margin-bottom: var(--spacing-xs);
    color: var(--primary);
}

.menu-price {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #4CAF50;
    margin: var(--spacing-sm) 0;
}
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: var(--spacing-sm);
        }
        
        .product-actions a {
            flex: 1;
            padding: 0.6rem;
            border-radius: 6px;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }
        
        .product-actions a i {
            font-size: 1rem;
        }
        
        /* ===== TESTIMONIALS SECTION ===== */
        .testimonials {
            background-color: var(--white);
        }
        
        .testimonials-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-lg);
            margin-top: var(--spacing-lg);
        }
        
        .testimonial-card {
            background-color: var(--neutral-light);
            border-radius: 12px;
            padding: var(--spacing-lg);
            position: relative;
            box-shadow: var(--shadow-sm);
        }
        
        .testimonial-card::before {
            content: "“";
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 5rem;
            color: rgba(200, 37, 37, 0.1);
            font-family: Georgia, serif;
            line-height: 1;
        }
        
        .testimonial-text {
            margin-bottom: var(--spacing-md);
            font-style: italic;
            position: relative;
            z-index: 2;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: var(--spacing-sm);
        }
        
        .author-info h4 {
            color: var(--primary);
        }
        
        .author-info p {
            font-size: 0.9rem;
            color: #777;
        }
        
        /* ===== CTA SECTION ===== */
        .cta {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1556911220-ef412ae179a9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80') center/cover no-repeat;
            color: var(--white);
            text-align: center;
            padding: var(--spacing-xl) 0;
        }
        
        .cta-title {
            font-family: var(--heading-font);
            font-size: 2.5rem;
            margin-bottom: var(--spacing-sm);
        }
        
        .cta-text {
            max-width: 700px;
            margin: 0 auto var(--spacing-lg);
            font-size: 1.1rem;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: var(--spacing-sm);
        }
        
        /* ===== FOOTER ===== */
        .footer {
            background-color: var(--neutral-dark);
            color: var(--white);
            padding: var(--spacing-xl) 0 var(--spacing-md);
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }
        
        .footer-col h3 {
            font-family: var(--heading-font);
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
            background-color: var(--accent);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-link {
            margin-bottom: var(--spacing-xs);
            transition: color var(--transition-fast);
        }
        
        .footer-link:hover {
            color: var(--accent);
        }
        
        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
        }
        
        .contact-icon {
            margin-right: var(--spacing-xs);
            color: var(--accent);
        }
        
        .social-links {
            display: flex;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-sm);
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
        }
        
        .social-link:hover {
            background-color: var(--primary);
        }
        
        .order-methods {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: var(--spacing-sm);
        }
        
        .order-methods a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            transition: background-color var(--transition-fast);
        }
        
        .order-methods a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .order-methods a i {
            width: 20px;
            text-align: center;
        }
        
        .copyright {
            text-align: center;
            padding-top: var(--spacing-md);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* ===== RESPONSIVIDADE ===== */
        @media (max-width: 992px) {
            .section {
                padding: var(--spacing-lg) 0;
            }
            
            .hero {
                min-height: 70vh;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            
            .nav {
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background-color: var(--white);
                box-shadow: var(--shadow-md);
                flex-direction: column;
                align-items: flex-start;
                padding: var(--spacing-md);
                transform: translateY(-150%);
                transition: transform var(--transition-medium);
            }
            
            .nav.active {
                transform: translateY(0);
            }
            
            .nav-list {
                flex-direction: column;
                width: 100%;
                margin-right: 0;
                margin-bottom: var(--spacing-md);
            }
            
            .nav-item {
                margin: var(--spacing-sm) 0;
            }
            
            .contact-dropdown {
                width: 100%;
            }
            
            .cta-header {
                width: 100%;
                justify-content: center;
                margin-top: var(--spacing-sm);
            }
            
            .dropdown-content {
                width: 100%;
                position: static;
                box-shadow: none;
                border-radius: 0;
            }
        }
        
        @media (max-width: 576px) {
            .logo-title {
                font-size: 1.7rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .product-actions {
                flex-direction: column;
            }
        }

        /* ===== MODAL DE PEDIDO ===== */
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
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
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
            color: #666;
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
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-modal-primary {
            background: var(--primary);
            color: white;
        }

        .btn-modal-primary:hover {
            background: var(--primary-dark);
        }

        /* Formulário de pedido */
        .form-container {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
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
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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
            background: #f8d7d7;
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

        .required::after {
            content: ' *';
            color: var(--primary);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        /* PIX */
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
        /* ===== COMO FUNCIONA ===== */
.how-it-works {
    background-color: var(--white);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

/* Ajustes para os novos elementos */
.step-card {
    position: relative;
    padding-bottom: 40px; /* Espaço para o small */
}

.step-card small {
    position: absolute;
    bottom: 15px;
    left: 0;
    right: 0;
    font-size: 0.8rem;
    color: var(--neutral-dark);
    opacity: 0.8;
    padding: 0 var(--spacing-md);
}

/* Para 4 colunas em telas grandes */
@media (min-width: 992px) {
    .steps-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
.step-card:hover {
    transform: translateY(-5px);
}

.step-icon {
    position: relative;
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: var(--spacing-sm);
}

.step-number {
    position: absolute;
    top: -10px;
    right: -10px;
    background: var(--accent);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.step-card h3 {
    color: var(--primary);
    margin-bottom: var(--spacing-xs);
}
/* Ajuste para 3 colunas */
@media (min-width: 768px) {
    .steps-grid {
        grid-template-columns: repeat(3, 1fr);
    }
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
                
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <nav class="nav" id="nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="index.php" class="nav-link active">Home</a></li>
                        <li class="nav-item"><a href="cardapio.php" class="nav-link">Cardápio</a></li>
                        
                    </ul>
                    
                    <div class="contact-dropdown">
                        <button class="cta-header">
                            <i class="fas fa-phone-alt cta-icon"></i>
                            Fazer Pedido
                        </button>
                        <div class="dropdown-content">
                            <a href="https://wa.me/<?= $config['telefone'] ?>">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                            <a href="tel:<?= $config['telefone'] ?>">
                                <i class="fas fa-phone"></i> Ligar
                            </a>
                            <a href="<?= $config['instagram'] ?>">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <p class="hero-subtitle">Sabor que conquista</p>
                <h1 class="hero-title">Frangos e Salgados Artesanais</h1>
                <p class="hero-text">Temperados no ponto e assados na hora, nossos pratos são preparados com ingredientes selecionados e todo o cuidado para garantir uma experiência gastronômica única.</p>
                <div class="hero-buttons">
                    <a href="#menu" class="btn btn-primary">Ver Cardápio</a>
                    <a href="cardapio.php" class="btn btn-outline">Fazer Pedido</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features section">
        <div class="container">
            <h2 class="section-title">Por que escolher a Gula Frangos?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="feature-title">Qualidade Premium</h3>
                    <p>Utilizamos apenas ingredientes frescos e selecionados, garantindo o melhor sabor em cada pedaço.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Preparo Artesanal</h3>
                    <p>Cada prato é preparado com técnicas tradicionais e muito cuidado, respeitando o tempo ideal de preparo.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="feature-title">Entrega Rápida</h3>
                    <p>Entregamos seu pedido ainda quentinho, garantindo que chegue perfeito até sua casa.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu section">
        <div class="container">
            <h2 class="section-title">Destaques do Cardápio</h2>
            
            <div class="menu-grid">
                <?php foreach ($destaques as $item): ?>
                <div class="menu-item">
                    <img src="<?= htmlspecialchars($item['imagem'] ?? 'img/sem-foto.jpg') ?>" 
                         alt="<?= htmlspecialchars($item['nome']) ?>" 
                         class="menu-img">
                    <div class="menu-content">
                        <h3 class="menu-title"><?= htmlspecialchars($item['nome']) ?></h3>
                        <p><?= htmlspecialchars($item['descricao']) ?></p>
                        <span class="menu-price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                        <div class="product-actions">
    <button class="btn-whatsapp fazer-pedido-btn" 
            data-id="<?= $item['id'] ?>"
            data-nome="<?= htmlspecialchars($item['nome']) ?>"
            data-preco="<?= $item['preco'] ?>"
            data-imagem="<?= htmlspecialchars($item['imagem']) ?>">
        <i class="fab fa-whatsapp"></i> Pedir Agora
    </button>
</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: var(--spacing-lg);">
                <a href="cardapio.php" class="btn btn-primary">Ver Cardápio Completo</a>
            </div>
        </div>
    </section>

<!-- Como Funciona Section -->
<section class="how-it-works section">
    <div class="container">
        <h2 class="section-title">Como Funciona</h2>
        
        <div class="steps-grid">
            <!-- Passo 1 - Pedido -->
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-edit"></i>
                    <span class="step-number">1</span>
                </div>
                <h3>Monte seu pedido</h3>
                <p>Escolha no nosso cardápio: frangos assados (nosso especial!), marmitas, salgados, doces e bebidas.</p>
                <small>Pedidos pelo site, WhatsApp ou telefone.</small>
            </div>
            
            <!-- Passo 2 - Confirmação -->
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                    <span class="step-number">2</span>
                </div>
                <h3>Preencha o formulário</h3>
                <p>Informe seus dados, endereço (para delivery) e forma de pagamento diretamente no site.</p>
                <small>Tudo seguro e rápido!</small>
            </div>
            
            <!-- Passo 3 - Entrega (antigo passo 4) -->
            <div class="step-card">
                <div class="step-icon">
                    <i class="fas fa-motorcycle"></i>
                    <span class="step-number">3</span>
                </div>
                <h3>Receba em casa ou retire</h3>
                <p>Delivery rápido na região ou retirada no balcão - você escolhe!</p>
                <small>Pedido confirmado via WhatsApp.</small>
            </div>
        </div>
    </div>
</section>

    <!-- CTA Section -->
    <section class="cta section">
        <div class="container">
            <h2 class="cta-title">Pronto para experimentar?</h2>
            <p class="cta-text">Peça agora e receba em casa não só o melhor frango assado, mas também marmitas, salgados, doces e bebidas – tudo com o sabor e qualidade que você adora!</p>
            <div class="cta-buttons">
                <a href="cardapio.php" class="btn btn-primary">Ver Cardápio Completo</a>
                <a href="tel:<?= $config['telefone'] ?>" class="btn btn-outline">
                    <i class="fas fa-phone"></i> Ligar para Pedir
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Gula Frangos</h3>
                    <p>Frangos e salgados artesanais preparados com ingredientes selecionados e muito amor, entregues na sua porta ainda quentinhos.</p>
                    <div class="social-links">
                        <a href="<?= $config['facebook'] ?>" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?= $config['instagram'] ?>" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/<?= $config['telefone'] ?>" class="social-link" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Links Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="index.php" class="footer-link">Home</a></li>
                        <li><a href="cardapio.php" class="footer-link">Cardápio</a></li>
                        <li><a href="sobre.php" class="footer-link">Sobre Nós</a></li>
                        <li><a href="contato.php" class="footer-link">Contato</a></li>
                    </ul>
                </div>
                <div class="footer-col">
    <h3>Contato</h3>
    <div class="footer-contact">
        <div class="contact-item">
            <i class="fas fa-map-marker-alt contact-icon"></i>
            <span><?= htmlspecialchars($config['endereco']) ?></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-phone contact-icon"></i>
            <span><?= preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $config['telefone']) ?></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-envelope contact-icon"></i>
            <span><?= htmlspecialchars($config['email']) ?></span>
        </div>
    </div>
    
    <!-- Adicione esta parte para o horário de funcionamento -->
    <h3 style="margin-top: var(--spacing-md);">Horário de Funcionamento</h3>
    <ul class="footer-links">
        <li>Segunda: Fechado | Ter-Dom: 11h às 14h</li>
    </ul>
</div>
            </div>
            
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> Gula Frangos. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Modal de Pedido -->
    <div class="modal-overlay" id="pedido-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Finalizar Pedido</h3>
                <button class="modal-close" id="fechar-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <form id="pedido-form">
                        <div class="form-group">
                            <label for="pedido-nome" class="form-label required">Nome</label>
                            <input type="text" id="pedido-nome" class="form-control" required>
                            <div class="invalid-feedback">Por favor, digite seu nome</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="pedido-telefone" class="form-label required">Telefone</label>
                            <input type="tel" id="pedido-telefone" class="form-control" required>
                            <div class="invalid-feedback">Digite um telefone válido</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tipo de Pedido</label>
                            <div class="form-radio-group">
                                <label class="form-radio-label">
                                    <input type="radio" name="tipo-entrega" value="retirada" checked>
                                    Retirada
                                </label>
                                <label class="form-radio-label">
                                    <input type="radio" name="tipo-entrega" value="entrega">
                                    Entrega
                                </label>
                            </div>
                        </div>
                        
                        <div id="endereco-container" class="form-group">
                            <label for="pedido-endereco" class="form-label required">Endereço</label>
                            <textarea id="pedido-endereco" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback">Por favor, informe seu endereço</div>
                            
                            <label for="pedido-referencia" class="form-label">Ponto de Referência</label>
                            <input type="text" id="pedido-referencia" class="form-control" placeholder="Ex: Próximo ao mercado X">
                        </div>
                        
                        <div class="form-group">
                            <label for="pedido-observacoes" class="form-label">Observações</label>
                            <textarea id="pedido-observacoes" class="form-control" rows="2" placeholder="Ex: Sem cebola, alergia a camarão..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="pedido-pagamento" class="form-label">Forma de Pagamento</label>
                            <select id="pedido-pagamento" class="form-control">
                                <option value="">Selecione...</option>
                                <option value="Pix">Pix</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Cartão">Cartão</option>
                            </select>
                            <small id="pagamento-aviso" style="display: none; color: #666; margin-top: 5px;">
                                Para entregas aceitamos apenas Pix
                            </small>
                            <div id="pix-button-container" style="margin-top: 10px;"></div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" id="cancelar-pedido" class="btn-form btn-secondary">Cancelar</button>
                            <button type="submit" class="btn-form btn-primary">Enviar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de PIX -->
    <div class="modal-overlay" id="pix-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Pagamento via PIX</h3>
                <button class="modal-close" id="fechar-pix">&times;</button>
            </div>
            <div class="modal-body">
                <div class="pix-container">
                    <h4 style="margin-bottom: 15px; color: var(--primary);">Faça o pagamento via PIX</h4>
                    <p style="margin-bottom: 20px;">Valor total do pedido: <strong id="pix-total">R$ 0,00</strong></p>

                    <div class="pix-info">
                        <p><strong>Chave PIX:</strong></p>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                            <input type="text" id="chave-pix" value="6ffc8a65-372a-44ff-8512-d55e70b71d8b" readonly 
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
                        <button onclick="voltarParaFormulario()" 
                                style="padding: 10px 20px; background: var(--primary); color: white; 
                                        border: none; border-radius: 5px; cursor: pointer;">
                            <i class="fas fa-arrow-left"></i> Voltar ao formulário
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adicione AQUI o aviso de localização -->
<div id="aviso-localizacao" style="display: none; position: fixed; bottom: 20px; left: 20px; right: 20px; background: #fff3cd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;">
    <i class="fas fa-info-circle"></i> Para entregas, precisamos da sua localização. <strong>Por favor, compartilhe sua localização</strong> quando o navegador solicitar.
    <button id="fechar-aviso" style="float: right; background: none; border: none; cursor: pointer;">×</button>
</div>
<script>
    // Sistema de Pedido
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos do modal
        const pedidoModal = document.getElementById('pedido-modal');
        const pixModal = document.getElementById('pix-modal');
        const fecharModal = document.getElementById('fechar-modal');
        const fecharPix = document.getElementById('fechar-pix');
        const cancelarPedido = document.getElementById('cancelar-pedido');
        const pedidoForm = document.getElementById('pedido-form');
        const tipoEntregaRadios = document.querySelectorAll('input[name="tipo-entrega"]');
        const enderecoContainer = document.getElementById('endereco-container');
        const formaPagamento = document.getElementById('pedido-pagamento');
        const pagamentoAviso = document.getElementById('pagamento-aviso');
        const pixButtonContainer = document.getElementById('pix-button-container');
        
        // Dados do pedido atual
        let pedidoAtual = null;
        
        // Mostrar modal quando clicar em "Pedir Agora"
        document.querySelectorAll('.fazer-pedido-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                pedidoAtual = {
                    id: this.dataset.id,
                    nome: this.dataset.nome,
                    preco: parseFloat(this.dataset.preco),
                    imagem: this.dataset.imagem,
                    quantidade: 1
                };
                
                // Carrega dados do último cliente se existirem
                const ultimoCliente = JSON.parse(localStorage.getItem('ultimo_cliente'));
                if (ultimoCliente) {
                    document.getElementById('pedido-nome').value = ultimoCliente.nome || '';
                    document.getElementById('pedido-telefone').value = ultimoCliente.telefone || '';
                    
                    if (ultimoCliente.tipo_entrega === 'entrega') {
                        document.querySelector('input[name="tipo-entrega"][value="entrega"]').checked = true;
                        enderecoContainer.style.display = 'block';
                        document.getElementById('pedido-endereco').value = ultimoCliente.endereco || '';
                        document.getElementById('pedido-referencia').value = ultimoCliente.referencia || '';
                        
                        // Para entregas, só mostra PIX
                        formaPagamento.innerHTML = '<option value="Pix" selected>Pix (Obrigatório para entrega)</option>';
                        pagamentoAviso.style.display = 'block';
                    } else {
                        document.querySelector('input[name="tipo-entrega"][value="retirada"]').checked = true;
                        enderecoContainer.style.display = 'none';
                        
                        if (ultimoCliente.forma_pagamento) {
                            formaPagamento.value = ultimoCliente.forma_pagamento;
                        }
                    }
                }
                
                pedidoModal.classList.add('active');
            });
        });
        
        // Fechar modal
        fecharModal.addEventListener('click', () => pedidoModal.classList.remove('active'));
        fecharPix.addEventListener('click', () => pixModal.classList.remove('active'));
        cancelarPedido.addEventListener('click', () => pedidoModal.classList.remove('active'));
        
        // Mostrar/ocultar endereço conforme tipo de entrega
        tipoEntregaRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'entrega') {
                    enderecoContainer.style.display = 'block';
                    
                    // Para entregas, só mostra PIX
                    formaPagamento.innerHTML = '<option value="Pix" selected>Pix (Obrigatório para entrega)</option>';
                    pagamentoAviso.style.display = 'block';
                } else {
                    enderecoContainer.style.display = 'none';
                    
                    // Para retirada, mostra todas opções
                    formaPagamento.innerHTML = `
                        <option value="">Selecione...</option>
                        <option value="Pix">Pix</option>
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão">Cartão</option>
                    `;
                    pagamentoAviso.style.display = 'none';
                }
                
                // Atualiza botão PIX
                atualizarBotaoPix();
            });
        });
        
        // Atualiza quando muda a forma de pagamento
        formaPagamento.addEventListener('change', atualizarBotaoPix);
        
        // Função para atualizar o botão da chave PIX
        function atualizarBotaoPix() {
            const isEntrega = document.querySelector('input[name="tipo-entrega"]:checked').value === 'entrega';
            const isPix = formaPagamento.value === 'Pix';
            
            if (isEntrega || isPix) {
                pixButtonContainer.innerHTML = `
                    <button type="button" onclick="mostrarChavePix()" 
                            style="background: none; border: none; color: var(--primary); 
                                   text-decoration: underline; cursor: pointer; padding: 0;">
                        <i class="fas fa-qrcode"></i> Ver chave PIX
                    </button>
                `;
            } else {
                pixButtonContainer.innerHTML = '';
            }
        }
        
        // Enviar pedido
        pedidoForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validação
            let isValid = true;
            const nome = document.getElementById('pedido-nome').value.trim();
            const telefone = document.getElementById('pedido-telefone').value.replace(/\D/g, '');
            const tipoEntrega = document.querySelector('input[name="tipo-entrega"]:checked').value;
            const endereco = document.getElementById('pedido-endereco').value.trim();
            const referencia = document.getElementById('pedido-referencia').value.trim();
            const observacoes = document.getElementById('pedido-observacoes').value.trim();
            const formaPagamento = document.getElementById('pedido-pagamento').value;
            
            // Validações
            if (!nome) {
                document.getElementById('pedido-nome').classList.add('is-invalid');
                document.querySelector('#pedido-nome + .invalid-feedback').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('pedido-nome').classList.remove('is-invalid');
                document.querySelector('#pedido-nome + .invalid-feedback').style.display = 'none';
            }
            
            if (!telefone || telefone.length < 10 || telefone.length > 11) {
                document.getElementById('pedido-telefone').classList.add('is-invalid');
                document.querySelector('#pedido-telefone + .invalid-feedback').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('pedido-telefone').classList.remove('is-invalid');
                document.querySelector('#pedido-telefone + .invalid-feedback').style.display = 'none';
            }
            
            if (tipoEntrega === 'entrega' && !endereco) {
                document.getElementById('pedido-endereco').classList.add('is-invalid');
                document.querySelector('#pedido-endereco + .invalid-feedback').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('pedido-endereco').classList.remove('is-invalid');
                document.querySelector('#pedido-endereco + .invalid-feedback').style.display = 'none';
            }
            
            if (isValid) {
                const dadosCliente = { 
                    nome, 
                    telefone, 
                    tipo_entrega: tipoEntrega, 
                    endereco: tipoEntrega === 'entrega' ? endereco : '',
                    referencia: tipoEntrega === 'entrega' ? referencia : '',
                    observacoes,
                    forma_pagamento: formaPagamento || null
                };
                
                // Salva os dados do cliente para próxima vez
                localStorage.setItem('ultimo_cliente', JSON.stringify(dadosCliente));
                
                // Fecha o modal
                pedidoModal.classList.remove('active');
                
                // Envia o pedido
                await enviarPedido(dadosCliente);
            }
        });
        
        // Função para capturar localização
        function capturarLocalizacao() {
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
        }

        // Envia o pedido para o WhatsApp
        async function enviarPedido(dadosCliente) {
            const total = pedidoAtual.preco * pedidoAtual.quantidade;
            
            let mensagem = `🍗 *PEDIDO - GULA FRANGOS* 🍗\n\n` +
                           `👤 *Cliente:* ${dadosCliente.nome}\n` +
                           `📞 *Telefone:* ${dadosCliente.telefone}\n` +
                           `🚚 *Tipo:* ${dadosCliente.tipo_entrega === 'entrega' ? 'Entrega' : 'Retirada'}\n`;

            // Mostrar aviso de localização para entregas
            if (dadosCliente.tipo_entrega === 'entrega') {
                document.getElementById('aviso-localizacao').style.display = 'block';
                
                // Fechar aviso quando clicar no botão
                document.getElementById('fechar-aviso').addEventListener('click', function() {
                    document.getElementById('aviso-localizacao').style.display = 'none';
                });
            }

            try {
                let coords = null;
                let enderecoManual = null;
                
                // Se for entrega, tenta obter localização
                if (dadosCliente.tipo_entrega === 'entrega') {
                    try {
                        coords = await capturarLocalizacao();
                        mensagem += `📍 *Localização*: https://maps.google.com?q=${coords.latitude},${coords.longitude}\n`;
                    } catch (error) {
                        console.error('Erro ao obter localização:', error);
                        
                        // Se falhar, pede endereço manualmente
                        enderecoManual = await new Promise(resolve => {
                            // Salva o conteúdo atual do modal
                            const modalContent = document.querySelector('.modal-body').innerHTML;
                            const modalTitle = document.querySelector('.modal-title').textContent;
                            
                            // Atualiza o modal para pedir endereço
                            document.querySelector('.modal-title').textContent = 'Informe seu Endereço';
                            document.querySelector('.modal-body').innerHTML = `
                                <div class="form-group">
                                    <label class="form-label required">Endereço Completo</label>
                                    <textarea id="endereco-manual" class="form-control" rows="3" required>${dadosCliente.endereco || ''}</textarea>
                                    <div class="invalid-feedback">Por favor, informe seu endereço completo</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Ponto de Referência</label>
                                    <input type="text" id="referencia-manual" class="form-control" 
                                           placeholder="Ex: Próximo ao mercado X" value="${dadosCliente.referencia || ''}">
                                </div>
                                <div class="form-actions">
                                    <button id="confirmar-endereco" class="btn-form btn-primary">Confirmar</button>
                                </div>
                            `;
                            
                            // Reabre o modal
                            document.getElementById('pedido-modal').classList.add('active');
                            
                            document.getElementById('confirmar-endereco').addEventListener('click', () => {
                                const endereco = document.getElementById('endereco-manual').value.trim();
                                if (endereco) {
                                    // Restaura o modal original
                                    document.querySelector('.modal-title').textContent = modalTitle;
                                    document.querySelector('.modal-body').innerHTML = modalContent;
                                    
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
                        
                        mensagem += `🏠 *Endereço:* ${enderecoManual.endereco}\n`;
                        if (enderecoManual.referencia) {
                            mensagem += `📍 *Referência:* ${enderecoManual.referencia}\n`;
                        }
                    }
                }
                
                // Registra o pedido no banco com as coordenadas ou endereço manual
                const idPedido = await registrarPedidoNoBanco({
                    ...dadosCliente,
                    latitude: coords?.latitude || null,
                    longitude: coords?.longitude || null,
                    endereco: enderecoManual?.endereco || dadosCliente.endereco || null,
                    referencia: enderecoManual?.referencia || dadosCliente.referencia || null
                }, total);
                
                // Adiciona o número do pedido à mensagem
                mensagem = `📋 *Nº do Pedido:* ${idPedido}\n` + mensagem;
                
                if (dadosCliente.observacoes) {
                    mensagem += `📝 *Observações:* ${dadosCliente.observacoes}\n`;
                }
                
                if (dadosCliente.forma_pagamento) {
                    mensagem += `💳 *Pagamento preferencial:* ${dadosCliente.forma_pagamento}\n`;
                }
                
                mensagem += `\n📦 *ITEM:*\n` +
                           `➤ ${pedidoAtual.nome} (${pedidoAtual.quantidade}x) - R$ ${total.toFixed(2)}\n\n` +
                           `💰 *TOTAL: R$ ${total.toFixed(2)}*`;
                
                // Abre o WhatsApp
                window.open(`https://wa.me/<?= $config['telefone'] ?>?text=${encodeURIComponent(mensagem)}`, '_blank');
            } catch (error) {
                console.error('Erro ao processar pedido:', error);
                // Abre o WhatsApp mesmo se falhar em alguma etapa
                window.open(`https://wa.me/<?= $config['telefone'] ?>?text=${encodeURIComponent(mensagem)}`, '_blank');
            }
        }
        
        // Registrar pedido no banco de dados (atualizada)
        function registrarPedidoNoBanco(dadosCliente, total) {
            return new Promise((resolve, reject) => {
                const pedidoData = {
                    cliente_nome: dadosCliente.nome,
                    telefone: dadosCliente.telefone,
                    itens: JSON.stringify([pedidoAtual]),
                    total: total,
                    tipo_entrega: dadosCliente.tipo_entrega,
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
        }
        
        // Máscara para telefone
        const telefoneInput = document.getElementById('pedido-telefone');
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
    });
    
    // Função global para mostrar chave PIX
    function mostrarChavePix() {
        const total = document.querySelector('.fazer-pedido-btn').dataset.preco;
        document.getElementById('pix-total').textContent = `R$ ${parseFloat(total).toFixed(2)}`;
        
        document.getElementById('pedido-modal').classList.remove('active');
        document.getElementById('pix-modal').classList.add('active');
    }
    
    // Função global para copiar chave PIX
    function copiarChavePix() {
        const chaveInput = document.getElementById('chave-pix');
        chaveInput.select();
        document.execCommand('copy');
        
        // Feedback visual
        const btn = document.querySelector('.btn-copiar-pix');
        btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
        }, 2000);
    }
    
    // Função global para voltar ao formulário
    function voltarParaFormulario() {
        document.getElementById('pix-modal').classList.remove('active');
        document.getElementById('pedido-modal').classList.add('active');
    }
    
    // Menu Mobile Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const nav = document.getElementById('nav');
    
    mobileToggle.addEventListener('click', () => {
        nav.classList.toggle('active');
        mobileToggle.querySelector('i').classList.toggle('fa-bars');
        mobileToggle.querySelector('i').classList.toggle('fa-times');
    });
    
    // Sticky Header
    const header = document.getElementById('header');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
</body>
</html>