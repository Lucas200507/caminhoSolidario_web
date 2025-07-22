
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

// Quando a página carregar, aplica o usuário e ícone salvos
document.addEventListener('DOMContentLoaded', aplicarUser);
/*************************************************************************************************************************************************** */
/*                    -------------------  Navegação de páginas  -------------------                       */
function navegar_cadastrarBeneficiario(){
    window.location.href = "cadastroBeneficiario.php";
    
}

function navegar_cadastrarVoluntario(){
    // window.location.href = "cadastroVoluntario.php";
     window.location.href = "em_manutencao.html";
    
}

function navegar_cadastrarDependente(){
    window.location.href = "cadastroDependente.php";
    
}

function navegar_alterarBeneficiario(){
    window.location.href = "alteracaoBeneficiario.php";
}

function navegar_alterarVoluntario(){
    // window.location.href = "alteracaoVoluntario.php";
    window.location.href = "em_manutencao.html";
}

function navegar_alterarDependente(){
   // window.location.href = "alteracaoDependente.php";
   window.location.href = "em_manutencao.html";
}

function navegar_lancarFrequencia(){
    window.location.href = "lancarFrequencia.php";
}

function navegar_verificarFrequencia(){
    window.location.href = "verificarFrequencia.php";
}

function navegar_documentos(){
    // window.location.href = "documentos.php";
    window.location.href = "em_manutencao.html";
}

function navegar_documentosDependente(){
   //  window.location.href = "documentosDependente.php";
   window.location.href = "em_manutencao.html";
}

function navegar_documentosBeneficiario(){
   // window.location.href = "documentosBeneficiario.php";
   window.location.href = "em_manutencao.html";
}

function navegar_usuario(){        
    window.location.href = "usuario.php#resetarSenha";
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
