async function processarPagamento(metodo) {
    try {
        const dadosCliente = JSON.parse(localStorage.getItem('ultimo_cliente'));
        if (!dadosCliente) throw new Error('Dados do cliente não encontrados');
        
        const total = this.calcularTotal();
        if (total <= 0) throw new Error('Valor do pedido inválido');

        if (metodo === 'pix') {
            this.showModal('Aguarde', 'Gerando QR Code PIX...', 'loading');
            
            // 1. Primeiro registrar o pedido para obter ID real
            const pedidoId = await this.registrarPedido({
                ...dadosCliente,
                metodo_pagamento: 'pix',
                status: 'aguardando_pagamento'
            });

            // 2. Gerar PIX com o ID real
            const response = await fetch('gerar_pix.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pedido_id: pedidoId,
                    valor: total,
                    cliente: dadosCliente.nome,
                    telefone: dadosCliente.telefone.replace(/\D/g, '') // Remove não-números
                })
            });

            // Verifica se a resposta é JSON válido
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                throw new Error(`Resposta inválida do servidor: ${text.substring(0, 100)}...`);
            }

            const pixData = await response.json();
            
            if (!response.ok || pixData.erro) {
                throw new Error(pixData.erro || 'Erro ao gerar PIX');
            }

             // 3. Mostrar QR Code - CORREÇÃO NO src DA IMAGEM
            this.showModal(
                'Pagamento via PIX',
                `<div style="text-align: center;">
                    <img src="data:image/png;base64,${pixData.codigo_pix}" style="width: 200px; margin: 0 auto;">
                    <p style="margin: 1rem 0; font-size: 1.2rem;">
                        <strong>Pedido:</strong> ${pedidoId}<br>
                        <strong>Valor:</strong> R$ ${total.toFixed(2)}<br>
                        <strong>Expira em:</strong> ${pixData.expira_em}
                    </p>
                    <button onclick="navigator.clipboard.writeText('${pixData.qrcode}')" 
                            class="btn-copiar-pix">
                        Copiar Código PIX
                    </button>
                </div>`,
                'success'
            );
            // 4. Limpar carrinho após sucesso
            this.itens = [];
            this.salvar();
            this.atualizarUI();
        }
    } catch (error) {
        console.error('Erro no processamento:', error);
         this.showModal('Erro', 'Falha ao processar pagamento. Tente novamente.', 'error');
    }
}