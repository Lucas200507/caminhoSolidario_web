// Miranha: https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg
// LULA: https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg/250px-Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg
// Inosuke: https://i.pinimg.com/474x/33/b3/83/33b383395a23f5ce67024a4107e49b88.jpg
//const inosuke = "https://i.pinimg.com/474x/33/b3/83/33b383395a23f5ce67024a4107e49b88.jpg";
//const lula = "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg/250px-Foto_oficial_de_Luiz_In%C3%A1cio_Lula_da_Silva_%28rosto%29.jpg";
//const miranha = "https://i.pinimg.com/474x/e5/75/59/e57559d20114d87fc9d4f2129f5ef414.jpg";
//const pinguim = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8U8LZgGKkmxBC--2NCEZjCASVY5CmgnKRSA&s";
// salva a variável para todas as páginas
let icon = localStorage.getItem('userIcon'); // Carrega do localStorage ou usa o padrão
let iconAlterado = false;

// if(nomeUsuario){
//     let usuario = "";
//     if (icon == lula){
//         usuario = "Ladrão";
//     } else if (icon == miranha){
//         usuario = "Peter Parker";
//     } else if (icon == inosuke){
//         usuario = "Inosuke";
//     } else if (icon == pinguim){
//         usuario = "Lucas";
//     }

//     nomeUsuario.textContent = usuario;
// }

let usuario = 'Usuário'
nomeUsuario.textContent = usuario;

let primeiraLetraUsuario = usuario.charAt(0)
document.write(primeiraLetraUsuario)

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
} else if (usuario == 'Yan Fellippe'){
    icon = 'https://carrefourbr.vtexassets.com/arquivos/ids/180332976/kit-diy-therian-mask-seenelling-mascara-de-gato-em-branco-com-tecido-de-feltro.jpg?v=638680164515130000'
} else if (usuario == 'Raphael'){
    icon = 'https://pm1.aminoapps.com/7566/3a426d105f3dbd3b646f0cc1354bd4c23abfddcer1-258-195v2_uhq.jpg'
} else if (usuario == 'Lucas'){
    icon = 'https://i.pinimg.com/236x/d3/c0/60/d3c0604694d341d039c4d356b7af945f.jpg'
} else if (usuario == 'Rafael'){
    icon = 'https://pagina3.com.br/wp-content/uploads/2025/05/legendaris.png'
} else if (usuario == 'João'){
    icon = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQZrqG8e4cEegsO1_clIqBRxKRiJ6g-RLDcig&s'
} else if (usuario == 'Daiana'){
    icon = 'https://newr7-r7-prod.web.arc-cdn.net/resizer/v2/NTSOW5XUXVIRHB3BNT56G57M7Q.jpg?auth=9ae6515b5cd90e6b94e485d55e6940f5dd3426d6d5a94406058b197c54c2cd96&width=638&height=518'
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

// function alterarIcon(){
//     if (iconUsuario) {
//         if (icon == lula){
//             icon = miranha;
//         } else if (icon == miranha){
//             icon = inosuke;
//         } else if (icon == inosuke){
//             icon = pinguim;
//         } else if (icon == pinguim){
//             icon = lula;
//         }

        
//         localStorage.setItem('userIcon', icon); // Salva o ícone no localStorage
//         aplicarIcone(iconUsuario, icon);
//         aplicarIcone(iconUsuarioPaginas, icon);        
//         iconAlterado = true;
//     }
// }



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
