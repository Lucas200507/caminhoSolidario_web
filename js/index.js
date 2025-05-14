const usuario = "Lucas";
/////////////////////////////////////////////////////////////////////
const nomeUsuario = document.getElementById('nomeUsuario');
nomeUsuario.textContent = usuario;


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

//////////////////////////////////////////
/*
function MudarImagem(){
    let imagem = document.getElementById('foto_voluntarioU');
        if (imagem.src == "https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg"){
            imagem.src = "https://i.pinimg.com/736x/a6/25/b3/a625b305349cf814110c6b521feb6ab5.jpg";
        } else {
            imagem.src = "https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg";
        }
}

function MudarImg(){
    let fotoOriginal = true;
    let foto = document.getElementById('foto');
    if(foto.style.backgroundImage.includes('https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg')){        
        foto.style.backgroundImage = "url('https://i.pinimg.com/736x/a6/25/b3/a625b305349cf814110c6b521feb6ab5.jpg')";        
    } else { 
        foto.style.backgroundImage = "url('https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg')";
        
    }
}
*/
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