
/********************************************************************************************************* */
/*                    -------------------  Usuário  -------------------                       */
// salva a variável para todas as páginas
let icon = localStorage.getItem('userIcon'); // Carrega do localStorage ou usa o padrão]
let usuario = localStorage.getItem('usuario') || 'Usuário';
let usuarioCompleto = localStorage.getItem('usuarioCompl');
let iconAlterado = false;



function Salvar_user(){
    let nomeCompleto_usuario = document.getElementById('nomeCompleto_user').value;
    localStorage.setItem('usuarioCompl', nomeCompleto_usuario);

    let formataNome = nomeCompleto_usuario.split(' ');    
    let primeiroNome = formataNome[0];
    localStorage.setItem('usuario', primeiroNome);
    nomeUsuario.textContent = usuario;    
}

let primeiraLetraUsuario = usuario.charAt(0)
document.write(primeiraLetraUsuario)

// ARRAY COM LINKS DAS IMAGENS

if (primeiraLetraUsuario == 'A' || primeiraLetraUsuario == 'a' || primeiraLetraUsuario == 'Á'|| primeiraLetraUsuario == 'á'){
    icon = 'https://cdn-icons-png.flaticon.com/512/3665/3665909.png'
} else if (primeiraLetraUsuario == 'B' || primeiraLetraUsuario == 'b'){
    icon = 'https://cdn-icons-png.flaticon.com/512/6819/6819083.png'
} else if (primeiraLetraUsuario == 'C' || primeiraLetraUsuario == 'c'){
    icon = 'https://cdn-icons-png.flaticon.com/512/6819/6819089.png'
} else if (primeiraLetraUsuario == 'D' || primeiraLetraUsuario == 'd'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSf_RmlVEBmWG69ljwh9gSbWsE-H8AH3zXfbA&s'
} else if (primeiraLetraUsuario == 'E' || primeiraLetraUsuario == 'e' || primeiraLetraUsuario == 'é' || primeiraLetraUsuario == 'É'){
    icon = 'https://cdn-icons-png.flaticon.com/512/3665/3665930.png'
} else if (primeiraLetraUsuario == 'F' || primeiraLetraUsuario == 'f'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTnMswzFU3CLX4FD2_i2KO5vjwXTjsSgQ2CDA&s'    
} else if (primeiraLetraUsuario == 'G' || primeiraLetraUsuario == 'g'){
    icon = 'https://cdn-icons-png.flaticon.com/512/7199/7199629.png'
} else if (primeiraLetraUsuario == 'H' || primeiraLetraUsuario == 'h'){
    icon = 'https://cdn-icons-png.flaticon.com/512/3600/3600921.png'
} else if (primeiraLetraUsuario == 'I' || primeiraLetraUsuario == 'i' || primeiraLetraUsuario == 'í' || primeiraLetraUsuario == 'Í'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0QCqVmoN1tj2yBXniaSgFVqAKVAlVCaxcKQ&s'
} else if (primeiraLetraUsuario == 'J' || primeiraLetraUsuario == 'j'){
    icon = 'https://cdn-icons-png.flaticon.com/512/8142/8142725.png'
} else if (primeiraLetraUsuario == 'K' || primeiraLetraUsuario == 'k'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTjoFs7HCk71ujxOIk7wAtqdIg46Qlr41o6g&s'
} else if (primeiraLetraUsuario == 'L' || primeiraLetraUsuario == 'l'){
    icon = 'https://cdn-icons-png.flaticon.com/512/9313/9313212.png'
}  else if (primeiraLetraUsuario == 'M' || primeiraLetraUsuario == 'm'){
    icon = 'https://cdn-icons-png.flaticon.com/512/8142/8142749.png'
}  else if (primeiraLetraUsuario == 'N' || primeiraLetraUsuario == 'n'){
    icon = 'https://cdn-icons-png.flaticon.com/512/7297/7297825.png'
}  else if (primeiraLetraUsuario == 'O' || primeiraLetraUsuario == 'o'|| primeiraLetraUsuario == 'ó' || primeiraLetraUsuario == 'Ó'){
    icon = 'https://cdn-icons-png.freepik.com/512/5540/5540741.png'
}  else if (primeiraLetraUsuario == 'P' || primeiraLetraUsuario == 'p'){
    icon = 'https://cdn-icons-png.flaticon.com/512/6819/6819255.png'
}  else if (primeiraLetraUsuario == 'Q' || primeiraLetraUsuario == 'q'){
    icon = 'https://cdn-icons-png.flaticon.com/512/6819/6819264.png'
}  else if (primeiraLetraUsuario == 'R' || primeiraLetraUsuario == 'r'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8hOAEBdJTpUGqV3oyS4i4kO8Al2i6rZPNeQ&s'
} else if (primeiraLetraUsuario == 'S' || primeiraLetraUsuario == 's'){
    icon = 'https://cdn-icons-png.flaticon.com/512/3666/3666227.png'
} else if (primeiraLetraUsuario == 'T' || primeiraLetraUsuario == 't'){
    icon = 'https://cdn-icons-png.flaticon.com/512/3666/3666228.png'
} else if (primeiraLetraUsuario == 'U' || primeiraLetraUsuario == 'u' || primeiraLetraUsuario == 'ú' || primeiraLetraUsuario == 'Ú'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqTjsvcTAHAipYQiAZ9bX43i35NDTvP0lzgA&s'
} else if (primeiraLetraUsuario == 'V' || primeiraLetraUsuario == 'v'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSwbhDsB7ltxVWITROj3s6Af3Ra_YquEXwVwg&s'
} else if (primeiraLetraUsuario == 'W' || primeiraLetraUsuario == 'w'){
    icon = 'https://cdn-icons-png.flaticon.com/512/5540/5540846.png'
} else if (primeiraLetraUsuario == 'X' || primeiraLetraUsuario == 'x'){
    icon = 'https://cdn-icons-png.flaticon.com/512/10101/10101130.png'
} else if (primeiraLetraUsuario == 'Y' || primeiraLetraUsuario == 'y'){
    icon = 'https://cdn-icons-png.freepik.com/256/3097/3097132.png?semt=ais_hybrid'
} else if (primeiraLetraUsuario == 'Z' || primeiraLetraUsuario == 'z'){
    icon = 'https://cdn-icons-png.flaticon.com/512/6646/6646557.png'
} 



const iconUsuario = document.getElementById('IconUsuarioU');
const iconUsuarioPaginas = document.getElementById('IconUsuarioPaginas');


localStorage.setItem('userIcon', icon); // Salva o ícone no localStorage
localStorage.setItem('userIcon', icon);
aplicarIcone(iconUsuarioPaginas, icon);        
iconAlterado = true;
aplicarIcone(iconUsuario, icon);

function aplicarIcone(elemento, url) {
    if (elemento) {
        elemento.style.backgroundImage = `url(${url})`;
    }
}
// Aplica o ícone carregado do localStorage (ou o padrão) ao carregar a página
aplicarIcone(iconUsuario, icon);
aplicarIcone(iconUsuarioPaginas, icon);

/*************************************************************************************************************************************************** */

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
    window.alert('hello');
}
