document.addEventListener('DOMContentLoaded', () => {
    const fields = document.querySelectorAll('.nota-input');
    const regular = document.getElementById('regularPreview');
    const situacao = document.getElementById('situacaoPreview');

    function update() {
        if (!regular) return;
        const av1 = Math.min(3, Math.max(0, parseFloat(document.querySelector('[name="av1"]')?.value || 0)));
        const av2 = Math.min(3, Math.max(0, parseFloat(document.querySelector('[name="av2"]')?.value || 0)));
        const av3 = Math.min(4, Math.max(0, parseFloat(document.querySelector('[name="av3"]')?.value || 0)));
        const recValue = document.querySelector('[name="rec"]')?.value;
        const rec = recValue === '' ? null : parseFloat(recValue);
        const total = av1 + av2 + av3;
        regular.textContent = total.toFixed(2).replace('.', ',') + ' / 10';
        if (total >= 6) situacao.textContent = 'Aprovado';
        else if (rec !== null && rec >= 6) situacao.textContent = 'Aprovado pela REC';
        else if (rec !== null) situacao.textContent = 'Reprovado';
        else situacao.textContent = 'Aguardando / Reprovado';
    }
    fields.forEach(f => f.addEventListener('input', update));
    update();
});
