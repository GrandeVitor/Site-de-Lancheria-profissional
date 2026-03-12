# 🍗 Gula Frangos

Sistema web para uma loja de frangos assados e salgados artesanais, com cardápio online, carrinho de compras, integração de pagamento e área administrativa para gerenciamento de pedidos e produtos.

O projeto foi desenvolvido utilizando **PHP, HTML, CSS e JavaScript**, com **MySQL** para armazenamento de dados e integração com a API de pagamentos do Mercado Pago.

## 📌 Funcionalidades

### Área do cliente

- Página inicial com informações da loja
- Cardápio online de produtos
- Carrinho de compras
- Finalização de pedidos
- Integração com pagamento online
- Verificação automática do status do pagamento
- Página de contato e redes sociais da loja

### Área administrativa

A pasta **/admin** contém o painel administrativo para gerenciamento da loja.

Funcionalidades do painel:

- Login de administrador
- Painel administrativo
- Cadastro de usuários
- Gerenciamento de produtos
- Gerenciamento de pedidos
- Visualização dos últimos pedidos
- Estatísticas e totais de pedidos
- Gerenciamento de produtos em destaque

## 🧰 Tecnologias utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Composer
- API Mercado Pago

## 📂 Estrutura do projeto

gula_frangos_template
│
├── admin/ # Painel administrativo
│ ├── login.php
│ ├── painel.php
│ ├── cadastrar-usuario.php
│ ├── produtos/
│ ├── pedidos/
│ └── logout.php
│
├── config/ # Configurações do sistema
│ └── db.php
│
├── css/ # Estilos do site
├── js/ # Scripts JavaScript
├── img/ # Imagens do site
├── uploads/ # Upload de arquivos
│
├── index.php # Página inicial
├── cardapio.php # Página do cardápio
├── contato.html # Página de contato
├── processar_pagamento.php
├── verificar_pagamento.php
│
├── composer.json # Dependências PHP
└── vendor/ # Bibliotecas instaladas pelo Composer

## 💳 Integração de pagamento

O sistema utiliza a biblioteca oficial do Mercado Pago para PHP, instalada através do Composer.

Dependência utilizada:

mercadopago/dx-php

Arquivos responsáveis pelo processamento e verificação de pagamento:

- processar_pagamento.php
- verificar_pagamento.php

## ⚙️ Como executar o projeto

1. Instalar um servidor local como WAMP, XAMPP ou Laragon.

2. Copiar a pasta do projeto para o diretório do servidor local:

C:\wamp64\www

3. Acessar o projeto no navegador:

http://localhost/gula_frangos_template/

4. Configurar o banco de dados no arquivo:

config/db.php

5. Caso necessário, instalar as dependências do projeto com:

composer install

## 🔑 Área administrativa

A área administrativa pode ser acessada pelo endereço:

http://localhost/gula_frangos_template/admin

## 📱 Informações da loja

O sistema exibe informações da loja como telefone, endereço, redes sociais e horário de funcionamento nas páginas principais do site.

## 👨‍💻 Autor

Projeto desenvolvido por **Vitor Rodrigues**.
