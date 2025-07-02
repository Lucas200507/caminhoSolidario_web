<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
 
    // Inserindo os dados do usuário
    if (isset($_SESSION['usuario'])){
        $cpf_logado = $_SESSION['usuario'];
        $sql = "SELECT * FROM tbUsuarios_web WHERE cpf = $cpf_logado";
        $result = $conexao->query($sql);
        if (mysqli_num_rows($result) > 0){ // verifica se possui alguem com esse cpf
            while ($user_data = mysqli_fetch_assoc($result)){
                $nome = $user_data['voluntario'];
                $funcao = $user_data['situacao'];              
            }
             if($funcao == "A"){
                 $funcao = "Adminstrador";
             } else {
                 $funcao = "Voluntário";
             }          
         } else {
             // Não identificou um cpf cadastrado (MUITO DIFÍCIL)
            header("Location: ../routes/deslogar.php");
        }
    }    
    // if(isset($_GET['cpf'])){
    //     $cpf_logado = $_GET['cpf'];
    //     $sql = "SELECT * FROM tbUsuarios_web WHERE cpf = $cpf_logado";
        
    //     $result = $conexao->query($sql);
    //     // print_r($result);
    //     if (mysqli_num_rows($result) > 0){
    //         while ($user_data = mysqli_fetch_assoc($result)){                
    //             $nome = $user_data['voluntario'];
    //             $funcao = $user_data['situacao'];                
    //             // Na tela usuario, deve pegar os restantes dos dados (email, data_cadastro, senha)
    //         }
    //         if($funcao == "A"){
    //             $funcao = "Adminstrador";
    //         } else {
    //             $funcao = "Voluntário";
    //         }          
    //     } else {
    //         // Não identificou um cpf cadastrado (MUITO DIFÍCIL)
    //         header("Location: ../routes/deslogar.php");
    //     }
    
    // }    
?>