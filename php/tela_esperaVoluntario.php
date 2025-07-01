<?php   
    $campo_vazio = False;
    $cadastrado = False;
     if(isset($_POST['submit']) && !empty($_POST['nome']) && !empty($_POST['email']) && !empty($_POST['telefone']) && !empty($_POST['cpf']) && !empty($_POST['estado'])){
         // Conectando com o banco: 
         include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
         
         $nome = $_POST['nome'];
         $email = $_POST['email'];
         $telefone = $_POST['telefone'];
         $cpf = $_POST['cpf'];
         $estado = $_POST['estado'];
        
        $sql = "INSERT INTO espera_voluntario(nome_completo, email, telefone, cpf, estado) VALUES('$nome','$email','$telefone','$cpf','$estado')";
        $cadastrado = True;        
        
     }  else if(isset($POST['submit']) && (empty($POST['nome']) || empty($POST['email']) || empty($POST['telefone']) || empty($POST['cpf']) || empty($POST['estado']))){
        $campo_vazio = True;
     }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar criaçao de conta</title>
    <link rel="stylesheet" href="../css/index.css">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    
    <!-- Conexao com JS -->
    <script src="../js/index.js" defer></script> 
</head>
<body id="sem_tema">    
<div class="container_login d-flex justify-content-start pt-5 flex-column align-items-center">   
    <img src="../img/logo_semnome.png" alt="">
       <p class="h3 text-center p-1">Coloque seus dados para entrarmos em contato</p>
        <form class="d-block p-0 form_login" method="post" action="">
            <div class="form-group d-flex flex-column mt-3">
                <label for=""><input type="text" placeholder="Nome:" class="mb-2 form-control border-primary text-dark w-100" name="nome" required></label>
                <label for=""><input type="email" placeholder="Email:" class="mb-2 form-control border-primary text-dark w-100" name="email" required></label>
                <label for=""><input type="tel" placeholder="Telefone:" class="mb-2 form-control border-primary text-dark w-100" name="telefone" required></label>
                <label for=""><input type="text" placeholder="CPF:" class="mb-2 form-control border-primary text-dark w-100" name="cpf" required></label>
                <select name="estado"  class="form-select form-select-md w-100 mb-2 border-primary" required>
                    <option value="">Estado: </option>
                    <option value="DF">Distrito Federal</option>
                    <option value="outros">Outros</option>
                </select>              
            </div>            
            <input type="submit" name="submit" value="Enviar" class="d-block w-100 btn btn-light btn-outline-primary btn-block btn-lg mt-2 mb-2">   
            <!-- BACK END PARA CADASTRAR NA LISTA DE ESPERA -->
            <?php if($cadastrado): ?>               
                <script>         
                    window.alert('Você está na lista de espera. Por favor, aguarde o recebimento do email.');                                                   
                    window.location.href = "login.php";
                    
                </script>                
            <?php elseif($campo_vazio): ?>
                <div id="mensagem_campoVazio_esperaVoluntario" class="container_mensagem_esperaVoluntario">
                    É necessário preencher todos os campos.
                </div>
             <?php endif; ?>  
            <a href="login.php">
                <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
            </a>
        </form>        
    </div>
    <div id="rodapeI">
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
         <!-- IONICONS -->
         <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
         <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
