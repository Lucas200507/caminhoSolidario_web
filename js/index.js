// Miranha: https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg
// LULA: https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg/250px-Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg
// Inosuke: https://i.pinimg.com/474x/33/b3/83/33b383395a23f5ce67024a4107e49b88.jpg
const inosuke = "https://i.pinimg.com/474x/33/b3/83/33b383395a23f5ce67024a4107e49b88.jpg";
const lula = "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg/250px-Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg";
const miranha = "https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg";
const pinguim = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8U8LZgGKkmxBC--2NCEZjCASVY5CmgnKRSA&s";
// salva a variável para todas as páginas
let icon = localStorage.getItem('userIcon') || pinguim; // Carrega do localStorage ou usa o padrão
let iconAlterado = false;

function mostrarMensagemEditIcon(){
    let mensagem = document.getElementById('mensagemEditIcon');
    mensagem.style.display = 'block';
}

if(nomeUsuario){
    let usuario = "";
    if (icon == lula){
        usuario = "Ladrão";
    } else if (icon == miranha){
        usuario = "Peter Parker";
    } else if (icon == inosuke){
        usuario = "Inosuke";
    } else if (icon == pinguim){
        usuario = "Lucas";
    }

    nomeUsuario.textContent = usuario;
}

const iconUsuario = document.getElementById('IconUsuarioU');
const iconUsuarioPaginas = document.getElementById('IconUsuarioPaginas');

function aplicarIcone(elemento, url) {
    if (elemento) {
        elemento.style.backgroundImage = `url(${url})`;
    }
}

function alterarIcon(){
    if (iconUsuario) {
        if (icon == lula){
            icon = miranha;
        } else if (icon == miranha){
            icon = inosuke;
        } else if (icon == inosuke){
            icon = pinguim;
        } else if (icon == pinguim){
            icon = lula;
        }

        localStorage.setItem('userIcon', icon); // Salva o ícone no localStorage
        aplicarIcone(iconUsuario, icon);
        aplicarIcone(iconUsuarioPaginas, icon);
        iconAlterado = true;
    }
}

// Aplica o ícone carregado do localStorage (ou o padrão) ao carregar a página
aplicarIcone(iconUsuario, icon);
aplicarIcone(iconUsuarioPaginas, icon);
// NAVEGAÇÃO DE PÁGINAS
function navegar_cadastrarBeneficiario(){
    window.location.href = "/html/cadastro/cadastroBeneficiario.html";
    
}

function navegar_cadastrarVoluntario(){
    window.location.href = "/html/cadastro/cadastroVoluntario.html";
    
}

function navegar_cadastrarDependente(){
    window.location.href = "/html/cadastro/cadastroDependente.html";
    
}

function navegar_alterarBeneficiario(){
    window.location.href = "/html/alteracao/alteracaoBeneficiario.html";
}

function navegar_alterarVoluntario(){
    window.location.href = "/html/alteracao/alteracaoVoluntario.html";
}

function navegar_alterarDependente(){
    window.location.href = "/html/alteracao/alteracaoDependente.html";
}

function navegar_lancarFrequencia(){
    window.location.href = "/html/frequencia/lancarFrequencia.html";
}

function navegar_verificarFrequencia(){
    window.location.href = "/html/frequencia/verificarFrequencia.html";
}

function navegar_documentos(){
    window.location.href = "/html/documentos.html";
}

function navegar_documentosDependente(){
    window.location.href = "/html/documentos/documentosDependente.html";
}

function navegar_documentosBeneficiario(){
    window.location.href = "/html/documentos/documentosBeneficiario.html";
}

function navegar_usuario(){        
    window.location.href = "/html/usuario.html#resetarSenha";
}

function cancelarCadastro(){
    window.alert("Cancelou meu nobre");
}

function Cadastrar_Voluntario(){
    senhaTemporaria = document.getElementById('senhaTemporaria').value;
    confirmarSenha = document.getElementById('confirmarSenha').value;
    if (senhaTemporaria == confirmarSenha){
        window.alert('As senhas são enguais');
    } else {
        window.alert('As senhas são deferentes');
    }
}

 /************************************************ */
// Usuário
let FotoU = document.getElementById('imgFotoU');
let iconU = document.getElementById('IconUsuarioU');
function iconHoverIn(){
    if(iconU){
        iconU.style.opacity = 0.2;
        iconU.style.zIndex = 0; 

        // colocando um delay de 1s para aparecer a mensagem
        setTimeout(function(){
            let mensagem = document.getElementById('mensagemEditIcon');
            mensagem.style.display = 'flex';
        }, 750);        
    }
}

function iconHoverOut(){
    if(iconU){
        iconU.style.opacity = 1;
        iconU.style.zIndex = 2;

        // colocando um delay de 1s para desaparecer a mensagem
        setTimeout(function(){
            let mensagem = document.getElementById('mensagemEditIcon');
            mensagem.style.display = 'none';
        }, 750);  
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
    window.alert('hello');
}
