class Carrinho {
    constructor() {
        this.itens = JSON.parse(localStorage.getItem('carrinho')) || [];
        this.total = 0;
        this.initElements();
        this.initEventos();
        this.render();
    }

    initElements() {
        this.dom = {
            carrinhoFloat: document.getElementById('carrinho-flutuante'),
            carrinhoIcon: document.getElementById('carrinho-icone'),
            carrinhoItens: document.getElementById('carrinho-itens'),
            carrinhoTotal: document.getElementById('carrinho-total'),
            carrinhoContador: document.getElementById('carrinho-contador'),
            fecharCarrinho: document.getElementById('fechar-carrinho'),
            finalizarPedido: document.getElementById('finalizar-pedido')
        };
    }

    initEventos() {
        this.dom.carrinhoIcon.addEventListener('click', () => this.toggleCarrinho());
        this.dom.fecharCarrinho.addEventListener('click', () => this.toggleCarrinho());
        this.dom.finalizarPedido.addEventListener('click', () => this.finalizarPedido());
        
        this.dom.carrinhoItens.addEventListener('click', (e) => {
            if (e.target.classList.contains('qtd-btn')) {
                const id = parseInt(e.target.dataset.id);
                const action = e.target.dataset.action;
                this.alterarQuantidade(id, action);
            }
            if (e.target.classList.contains('carrinho-item-remover')) {
                const id = parseInt(e.target.dataset.id);
                this.removerItem(id);
            }
        });
    }

    toggleCarrinho() {
        this.dom.carrinhoFloat.classList.toggle('ativo');
    }

    adicionarItem(produto) {
        const itemExistente = this.itens.find(item => item.id === produto.id);

        if (itemExistente) {
            itemExistente.quantidade += 1;
        } else {
            this.itens.push({
                ...produto,
                quantidade: 1
            });
        }

        this.salvar();
        this.mostrarFeedback();
        
        // Mostra o carrinho apenas no primeiro item
        if (this.itens.length === 1) {
            this.toggleCarrinho();
        }
    }

    mostrarFeedback() {
        this.dom.carrinhoIcon.classList.add('animate');
        setTimeout(() => this.dom.carrinhoIcon.classList.remove('animate'), 500);
    }

    alterarQuantidade(id, action) {
        const item = this.itens.find(item => item.id === id);
        
        if (action === 'increase') {
            item.quantidade += 1;
        } else if (action === 'decrease' && item.quantidade > 1) {
            item.quantidade -= 1;
        } else if (action === 'decrease' && item.quantidade === 1) {
            // Remove o item se a quantidade for 1 e o usuário clicar em diminuir
            this.removerItem(id);
            return;
        }
        
        this.salvar();
    }

    removerItem(id) {
        this.itens = this.itens.filter(item => item.id !== id);
        this.salvar();
    }

    calcularTotal() {
        this.total = this.itens.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
    }

    salvar() {
        localStorage.setItem('carrinho', JSON.stringify(this.itens));
        this.calcularTotal();
        this.render();
    }

    render() {
        this.dom.carrinhoContador.textContent = this.itens.reduce((acc, item) => acc + item.quantidade, 0);
        
        this.dom.carrinhoItens.innerHTML = this.itens.map(item => `
            <div class="carrinho-item">
                <img src="${item.imagem}" alt="${item.nome}">
                <div class="carrinho-item-info">
                    <h4>${item.nome}</h4>
                    <div class="carrinho-item-qtd">
                        <button class="qtd-btn" data-id="${item.id}" data-action="decrease">-</button>
                        <span>${item.quantidade}</span>
                        <button class="qtd-btn" data-id="${item.id}" data-action="increase">+</button>
                    </div>
                    <p>R$ ${(item.preco * item.quantidade).toFixed(2)}</p>
                </div>
                <button class="carrinho-item-remover" data-id="${item.id}">
                    &times;
                </button>
            </div>
        `).join('');

        this.dom.carrinhoTotal.textContent = `R$ ${this.total.toFixed(2)}`;
    }

    finalizarPedido() {
        if (this.itens.length === 0) {
            alert('Adicione itens ao carrinho primeiro!');
            return;
        }

        const mensagem = this.gerarMensagemWhatsApp();
        const url = `https://wa.me/5511999999999?text=${encodeURIComponent(mensagem)}`;
        window.open(url, '_blank');
    }

    gerarMensagemWhatsApp() {
        const itensFormatados = this.itens.map(item =>
            `- ${item.nome} (${item.quantidade}x): R$ ${(item.preco * item.quantidade).toFixed(2)}`
        ).join('\n');

        return `Olá, Gula Frangos! Gostaria de fazer um pedido:\n\n${itensFormatados}\n\n*Total: R$ ${this.total.toFixed(2)}*\n\nNome: \nEndereço: \nTelefone:`;
    }
}

// Inicialização correta
document.addEventListener('DOMContentLoaded', () => {
    window.carrinho = new Carrinho();
});