<?php    
    $em_branco = False;
    $login_errado = False;
    // Método GET
    if (isset($_GET['submit']) && !empty($_GET['cpf']) && !empty($_GET['senha'])){
        //print_r($_REQUEST); // RECUPERA TODOS OS DADOS ENVIADOS
        
        // precisa verificar se existe o usuário e senha no banco
        include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
        $usuario = $_GET['cpf'];
        $senha = $_GET['senha'];
        // Para CRIPTOGRAFIA
        // $sql = "SELECT * FROM login WHERE cpf ='$usuario' AND senha = UPPER(MD5('$senha'))";
        $sql = "SELECT * FROM login WHERE cpf = '$usuario' AND senha = '$senha'";

        $result = $conexao->query($sql);
        if(mysqli_num_rows($result) > 0){ // se tiver mais de uma linha de registros iguais ao usuário e senha             
            // PRECISA GUARDAR O USUARIO LOGADO LOCALMENTE
            session_start(); // Iniciando as sessões
            $_SESSION['usuario'] = $usuario;
            $_SESSION['senha'] = $senha;
            header('Location: ../php/Servicos.php'); // navega para o home            
        } else {
            // Login errado
            $login_errado = True;                
        }
    } else if (isset($_GET['submit']) && (empty($_GET['cpf']) || empty($_GET['senha']))){
        // Está vazio
        $em_branco = True;        
    }
?> 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/index.css">
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">    
</head>
<body id="sem_tema">      
    <div class="container_login d-flex align-items-center justify-content-center flex-column">
        <img src="../img/logodolado.png" alt="">
        <form class="d-block p-3 form_login" id="formulario_login" method="get" action="">
            <div class="form-group d-flex flex-column mt-3">
                <label for="">
                    <input type="text" placeholder="CPF:" class="form-control border-primary text-dark w-100" name="cpf" id="cpf">
                </label>
                <label for="">
                    <input type="password" placeholder="Senha:" class="form-control border-primary text-dark w-100" name="senha" id="senha" >
                </label>                
            </div>            
            <div class="form-group justify-content-between d-flex justify-content-between links_login" style="font-size: 90%;">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="cbLembrar_senha">
                    <label for="" class="form-check-label">Lembrar Senha</label>
                </div>
                <a href="esqueceu_senha.php" class="text-primary">Esqueceu sua senha?</a>
            </div>            
            <input type="submit" class="btn btn-block btn-light btn-outline-primary btn-lg" name="submit" value="Entrar">                         
                <div class="text-center mt-3 mb-2 border-bottom border-dark pb-3">
                    <a href="tela_esperaVoluntario.php" class="h6 text-decoration-none">Cadastrar como voluntário</a> 
                </div>
        </form>       
        <?php if($em_branco): ?>        
                <div id="mensagem_erro2" class="container_mensagem_erro">
                    Todos os campos devem ser preenchidos.
                </div>
        <?php elseif($login_errado): ?>           
                <div id="mensagem_erro" class="container_mensagem_erro">
                    Usuário ou senha inválidos, tente novamente.
                </div>
        <?php endif; ?>
    </div>

    <div id="rodapeI">

    </div>
    <script src="../js/login.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<!-- 
// Método POST
    /* if(isset($_POST['submit'])){
        // print_r('Nome: '.$_POST['usuario']);
        // print_r('<br>Senha: '.$_POST['senha']);
        // INCLUIR A CONEXÃO 
        include_once('server.php');
    
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
    
        $result = mysqli_query($conexao, "INSERT INTO login(user, senha) VALUES('$usuario', '$senha')");
    }    
    */     -->