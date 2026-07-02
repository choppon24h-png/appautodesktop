<?php $pageTitle = 'Assistente IA'; require_once __DIR__ . '/../../layout/portal_header.php'; ?>

<div class="mb-4">
    <h4 class="fw-bold mb-1"><i class="fas fa-robot me-2 text-dark"></i>Assistente IA Automotivo</h4>
    <p class="text-muted mb-0">Tire dúvidas sobre seu veículo com inteligência artificial</p>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="stat-card" style="height:500px;display:flex;flex-direction:column">
            <!-- Chat -->
            <div id="chatBox" style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:12px">
                <div class="d-flex gap-2">
                    <div style="width:36px;height:36px;background:#1e293b;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="fas fa-robot text-white" style="font-size:.8rem"></i>
                    </div>
                    <div style="background:#f1f5f9;border-radius:12px 12px 12px 0;padding:12px 16px;max-width:80%">
                        <p class="mb-0">Olá! Sou o assistente IA do <strong>AppAuto</strong>. Posso ajudar com dúvidas sobre manutenção, diagnóstico de problemas, dicas de economia de combustível e muito mais. O que você gostaria de saber?</p>
                    </div>
                </div>
            </div>
            <!-- Input -->
            <div style="border-top:1px solid #e2e8f0;padding:12px">
                <div class="d-flex gap-2">
                    <input type="text" id="perguntaInput" class="form-control" placeholder="Ex: Qual o intervalo ideal para troca de óleo?" onkeypress="if(event.key==='Enter') enviarPergunta()">
                    <button class="btn btn-dark px-3" onclick="enviarPergunta()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card mb-3">
            <h6 class="fw-bold mb-3">Perguntas Frequentes</h6>
            <div class="d-flex flex-column gap-2">
                <?php
                $perguntas = [
                    'Qual o intervalo para troca de óleo?',
                    'Como saber se o freio precisa de revisão?',
                    'Meu carro está consumindo mais combustível, por quê?',
                    'Quando devo trocar a correia dentada?',
                    'Como calibrar os pneus corretamente?',
                    'O que significa a luz do motor acesa?',
                ];
                foreach ($perguntas as $p): ?>
                <button class="btn btn-outline-secondary btn-sm text-start" onclick="usarPergunta('<?= htmlspecialchars($p, ENT_QUOTES) ?>')">
                    <?= htmlspecialchars($p) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="stat-card">
            <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Sobre o Assistente</h6>
            <p class="text-muted small mb-0">O assistente usa IA para responder perguntas automotivas. As respostas são informativas e não substituem a avaliação de um mecânico profissional.</p>
        </div>
    </div>
</div>

<script>
function enviarPergunta() {
    const input = document.getElementById('perguntaInput');
    const pergunta = input.value.trim();
    if (!pergunta) return;

    adicionarMensagem(pergunta, 'user');
    input.value = '';

    adicionarMensagem('Analisando sua pergunta...', 'bot', true);

    fetch('/portal/ia/chat', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'pergunta=' + encodeURIComponent(pergunta)
    })
    .then(r => r.json())
    .then(data => {
        removerDigitando();
        adicionarMensagem(data.resposta || 'Não foi possível obter resposta.', 'bot');
    })
    .catch(() => {
        removerDigitando();
        adicionarMensagem('Erro ao conectar com o assistente. Tente novamente.', 'bot');
    });
}

function adicionarMensagem(texto, tipo, digitando = false) {
    const chat = document.getElementById('chatBox');
    const div = document.createElement('div');
    div.className = 'd-flex gap-2' + (tipo === 'user' ? ' flex-row-reverse' : '');
    div.id = digitando ? 'digitando' : '';

    const avatar = document.createElement('div');
    avatar.style.cssText = 'width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;' + (tipo === 'user' ? 'background:#3b82f6' : 'background:#1e293b');
    avatar.innerHTML = tipo === 'user' ? '<i class="fas fa-user text-white" style="font-size:.8rem"></i>' : '<i class="fas fa-robot text-white" style="font-size:.8rem"></i>';

    const bubble = document.createElement('div');
    bubble.style.cssText = 'border-radius:' + (tipo === 'user' ? '12px 12px 0 12px' : '12px 12px 12px 0') + ';padding:12px 16px;max-width:80%;' + (tipo === 'user' ? 'background:#3b82f6;color:#fff' : 'background:#f1f5f9');
    bubble.innerHTML = '<p class="mb-0">' + texto + '</p>';

    div.appendChild(avatar);
    div.appendChild(bubble);
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}

function removerDigitando() {
    const el = document.getElementById('digitando');
    if (el) el.remove();
}

function usarPergunta(p) {
    document.getElementById('perguntaInput').value = p;
    enviarPergunta();
}
</script>

<?php require_once __DIR__ . '/../../layout/portal_footer.php'; ?>
