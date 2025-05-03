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


//////////////////////////////////////////


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