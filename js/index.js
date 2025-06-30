
/********************************************************************************************************* */
/*                    -------------------  Usuário  -------------------                       */

// Função para aplicar o tema salvo ao carregar a página
function AplicarTemaSalvo() {
    // Não aplique o tema
    if(document.body.id === "sem_tema"){
        return;
    }

    const tema = localStorage.getItem('tema');
    const corpo_pagina = document.body;
    const logo = document.getElementById('logo_navegacaoHome');

    if (tema === 'escuro') {
        corpo_pagina.classList.add('dark');
        corpo_pagina.style.background = '#161616e8';
        if (logo) logo.src = '../img/logo_sem_nome-Branco.png';
    } else {
        corpo_pagina.classList.remove('dark');
        corpo_pagina.style.background = 'white';
        if (logo) logo.src = '../img/logo_semnome.png';
    }
}

// Função chamada no onclick para alternar o tema
function Mudar_tema() {
    const corpo_pagina = document.body;
    const logo = document.getElementById('logo_navegacaoHome');

    if (corpo_pagina.classList.contains('dark')) {
        corpo_pagina.classList.remove('dark');
        corpo_pagina.style.background = 'white';
        if (logo) logo.src = '../img/logo_semnome.png';
        localStorage.setItem('tema', 'claro');
    } else {
        corpo_pagina.classList.add('dark');
        corpo_pagina.style.background = '#161616e8';
        if (logo) logo.src = '../img/logo_sem_nome-Branco.png';
        localStorage.setItem('tema', 'escuro');
    }
}
// Aplica o tema salvo automaticamente ao carregar a página
document.addEventListener('DOMContentLoaded', AplicarTemaSalvo);

// Função para salvar o usuário no localStorage
function Salvar_user() {
    let usuario = document.getElementById('nomeCompleto_user').value.trim();
    if (usuario) {
        let usuarioFormatado = usuario.split(' ')[0]; // Primeiro nome
        localStorage.setItem('nome_usuario', usuarioFormatado);
        localStorage.setItem('usuario_completo', usuario);

        // Define o ícone de acordo com a primeira letra e salva no localStorage
        let primeiraLetra = usuario.charAt(0).toUpperCase();
        let icon = definirIcone(primeiraLetra);
        localStorage.setItem('userIcon', icon);

        // Atualiza na página atual
        aplicarUser();
    }
}

// nomeCompleto_user

// Função para aplicar o nome do usuário e o ícone
function aplicarUser() {
    const nome_usuario = localStorage.getItem('nome_usuario') || 'Usuário';
    const icon = localStorage.getItem('userIcon');

    // Aplica o nome do usuário nas páginas
    let nomeUsuarioElemento = document.getElementById('nomeUsuario');
    if (nomeUsuarioElemento) {
        nomeUsuarioElemento.textContent = nome_usuario;
    }

    // Aplica o ícone em todos os lugares necessários
    aplicarIcone(document.getElementById('IconUsuarioU'), icon);
    aplicarIcone(document.getElementById('IconUsuarioPaginas'), icon);
}

// Função para definir o ícone baseado na primeira letra
function definirIcone(letra) {
    switch (letra) {
        case 'A': case 'Á':
            return 'https://cdn-icons-png.flaticon.com/512/3665/3665909.png';
        case 'B':
            return 'https://cdn-icons-png.flaticon.com/512/6819/6819083.png';
        case 'C':
            return 'https://cdn-icons-png.flaticon.com/512/6819/6819089.png';
        case 'D':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSf_RmlVEBmWG69ljwh9gSbWsE-H8AH3zXfbA&s';
        case 'E': case 'É':
            return 'https://cdn-icons-png.flaticon.com/512/3665/3665930.png';
        case 'F':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTnMswzFU3CLX4FD2_i2KO5vjwXTjsSgQ2CDA&s';
        case 'G':
            return 'https://cdn-icons-png.flaticon.com/512/7199/7199629.png';
        case 'H':
            return 'https://cdn-icons-png.flaticon.com/512/3600/3600921.png';
        case 'I': case 'Í':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0QCqVmoN1tj2yBXniaSgFVqAKVAlVCaxcKQ&s';
        case 'J':
            return 'https://cdn-icons-png.flaticon.com/512/8142/8142725.png';
        case 'K':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTjoFs7HCk71ujxOIk7wAtqdIg46Qlr41o6g&s';
        case 'L':
            return 'https://cdn-icons-png.flaticon.com/512/9313/9313212.png';
        case 'M':
            return 'https://cdn-icons-png.flaticon.com/512/8142/8142749.png';
        case 'N':
            return 'https://cdn-icons-png.flaticon.com/512/7297/7297825.png';
        case 'O': case 'Ó':
            return 'https://cdn-icons-png.freepik.com/512/5540/5540741.png';
        case 'P':
            return 'https://cdn-icons-png.flaticon.com/512/6819/6819255.png';
        case 'Q':
            return 'https://cdn-icons-png.flaticon.com/512/6819/6819264.png';
        case 'R':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8hOAEBdJTpUGqV3oyS4i4kO8Al2i6rZPNeQ&s';
        case 'S':
            return 'https://cdn-icons-png.flaticon.com/512/3666/3666227.png';
        case 'T':
            return 'https://cdn-icons-png.flaticon.com/512/3666/3666228.png';
        case 'U': case 'Ú':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqTjsvcTAHAipYQiAZ9bX43i35NDTvP0lzgA&s';
        case 'V':
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSwbhDsB7ltxVWITROj3s6Af3Ra_YquEXwVwg&s';
        case 'W':
            return 'https://cdn-icons-png.flaticon.com/512/5540/5540846.png';
        case 'X':
            return 'https://cdn-icons-png.flaticon.com/512/10101/10101130.png';
        case 'Y':
            return 'https://cdn-icons-png.freepik.com/256/3097/3097132.png?semt=ais_hybrid';
        case 'Z':
            return 'https://cdn-icons-png.flaticon.com/512/6646/6646557.png';
        default:
            return 'https://cdn-icons-png.flaticon.com/512/847/847969.png'; // Um ícone padrão
    }
}

// Aplica o ícone em um elemento
function aplicarIcone(elemento, url) {
    if (elemento && url) {
        elemento.style.backgroundImage = `url(${url})`;
    }
}

// Quando a página carregar, aplica o usuário e ícone salvos
document.addEventListener('DOMContentLoaded', aplicarUser);
/*************************************************************************************************************************************************** */
/*                    -------------------  Navegação de páginas  -------------------                       */
function navegar_cadastrarBeneficiario(){
    window.location.href = "cadastroBeneficiario.php";
    
}

function navegar_cadastrarVoluntario(){
    window.location.href = "cadastroVoluntario.php";
    
}

function navegar_cadastrarDependente(){
    window.location.href = "cadastroDependente.php";
    
}

function navegar_alterarBeneficiario(){
    window.location.href = "alteracaoBeneficiario.php";
}

function navegar_alterarVoluntario(){
    window.location.href = "alteracaoVoluntario.php";
}

function navegar_alterarDependente(){
    window.location.href = "alteracaoDependente.php";
}

function navegar_lancarFrequencia(){
    window.location.href = "lancarFrequencia.php";
}

function navegar_verificarFrequencia(){
    window.location.href = "verificarFrequencia.php";
}

function navegar_documentos(){
    window.location.href = "documentos.php";
}

function navegar_documentosDependente(){
    window.location.href = "documentosDependente.php";
}

function navegar_documentosBeneficiario(){
    window.location.href = "documentosBeneficiario.php";
}

function navegar_usuario(){        
    window.location.href = "usuario.php#resetarSenha";
}

function cancelarCadastro(){
    window.alert("Cancelou");
}


 /************************************************ */
// Usuário
let FotoU = document.getElementById('imgFotoU');
let iconU = document.getElementById('IconUsuarioU');
function iconHoverIn(){
    if(iconU){
        iconU.style.opacity = 0.2;
        iconU.style.zIndex = 0;                
    }
}

function iconHoverOut(){
    if(iconU){
        iconU.style.opacity = 1;
        iconU.style.zIndex = 2;
    }
}


function resetarSenhaU(){
    let container = document.getElementById('formResetarSenha');
    if (container.style.display == 'none'){
        container.style.display =  'block';
    } else {
        container.style.display = 'none';
        }
}

function logout(){
    window.alert('Saindo!!!');
}
