<?php
    //  TEM QUE VERIFICAR QUAL É A FUNCÃO DO USUÁRIO LOGADO - ADM: todas as telas / Voluntário: limitado

    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
 
    // Inserindo os dados do usuário
    if (isset($_SESSION['usuario'])){
        $cpf_logado = $_SESSION['usuario'];
        $sql = "SELECT * FROM tbUsuarios_web WHERE cpf = $cpf_logado";
        $result = $conexao->query($sql);
            if (mysqli_num_rows($result) > 0){ // verifica se possui alguem com esse cpf
                while ($user_data = mysqli_fetch_assoc($result)){
                    $primeiro_nome = strtok($user_data['usuario'], " "); // separa a array antes do primeiro espaço da string
                    $nome_completo = $user_data['usuario']; 
                    $funcao = $user_data['situacao']; 
                    $email_logado = $user_data['email'];   
                    $telefone = $user_data['telefone'];
                    $senha = $user_data['senha'];
                    $data_cadastro_usuario = $user_data['data_cadastro'];
                }
                if($funcao == "A"){
                    $funcao = "Administrador";
                } else {
                    $funcao = "Voluntário";
                }    
                
                // Aplicar ícone do usuário
                $icone = obter_background_icone($primeiro_nome); 
                $salario_minimo = 1518;                                               
            }
         } else {
             // Não identificou um cpf cadastrado (MUITO DIFÍCIL)
            header("Location: ../routes/deslogar.php");
        }   

    function obter_background_icone($variavel){
        // strtoupper - converte para uma letra maiúscula
        $primeira_letra = strtoupper(substr($variavel, 0, 1));// Pega a primeira letra do nome do usuário. 0 - posição na array / 1 - tamanho da string
        switch($primeira_letra) { 
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
?>