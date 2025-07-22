const countdownElement = document.getElementById('countdown');
const targetDate = new Date('2025-07-23T10:00:00'); // Data e hora de término da manutenção (exemplo)

function updateCountdown() {
  const now = new Date();
  const diff = targetDate.getTime() - now.getTime();

  if (diff <= 0) {
    countdownElement.textContent = "Manutenção concluída!";
    // Redirecionar ou atualizar a página após a manutenção
    // window.location.href = "index.html"; // Exemplo de redirecionamento
    return;
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((diff % (1000 * 60)) / 1000);

  countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}

setInterval(updateCountdown, 1000); // Atualiza a cada segundo

// Chame a função para exibir a contagem regressiva imediatamente
updateCountdown();