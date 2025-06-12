// FUNÇÃO PARA VERIFICAR O USUÁRIO - LOGIN
async function verificarLogin(){
    const usuario = document.getElementById('cpf').value;
    const senha = document.getElementById('senha').value;

    const resposta = await fetch('http://localhost:3307/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }, 
        body: JSON.stringify({usuario, senha})
    });

    const dados = await resposta.json();

    if (dados.sucesso){
        window.location.href = "Servicos.html";        
    } else {
        document.getElementById('mensagemErro').textContent = dados.mensagem;
    }
}