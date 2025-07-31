<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO   
    session_start(); // Iniciando as sessões       
    $erro = False;    
    if (!isset($_SESSION['inserindo_senha'])) {
        $_SESSION['inserindo_senha'] = false;
    }
    $inserindo_senha = $_SESSION['inserindo_senha'];
    // PRECISA VERIFICAR SE O USUÁRIO ESTÁ DESLOGADO PARA RESETAR SUA SENHA
    if (isset($_POST['enviar']) && !$erro) {
        if (!empty($_POST['cpf']) && !empty($_POST['email'])){
            // PRECISA DESLOGAR PARA RESETAR SENHA
            if ((isset($_SESSION['usuario']) && isset($_SESSION['senha']))){
                echo "<script>window.alert('Para resetar sua senha, primeiro é necessário deslogar de sua conta');</script>";
                $erro = True;
            } else {
                // VERIFICAR SE POSSUI ESSE CPF E EMAIL NO BANCO
                $cpf_formatado = $_POST['cpf'];
                $_SESSION['cpf_resetarSenha'] = $cpf_formatado;
                $cpf_formatado = str_replace(['.', '-'], '', $cpf_formatado); 
                $email = $_POST['email'];            
                $sqlSelect_Usuarios_web = "SELECT * FROM tbUsuarios_web WHERE email = ? AND cpf = ?";            
                $stmt = mysqli_prepare($conexao, $sqlSelect_Usuarios_web);            
                mysqli_stmt_bind_param($stmt, "ss", $email, $cpf_formatado); // "ss" significa que são dois parâmetros tipo string            
                mysqli_stmt_execute($stmt); // EXECUTA O PREPAREDSTATEMENT           
                $resultSelect_Usuarios_web = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                // Verifica se possui o usuário no banco
                if ($row = mysqli_fetch_assoc($resultSelect_Usuarios_web)) {
                    $_SESSION['inserindo_senha'] = true;                   
                    $inserindo_senha = $_SESSION['inserindo_senha'];
                } else {                    
                    echo "<script>window.alert('Não foi encontrado nenhum usuário com este email ou cpf');</script>";
                    $erro = True;
                }
            }            
        } else {
            echo "<script>window.alert('Todos os campos devem ser preenchidos');</script>";
            $erro = True;
        }
    }

    if (isset($_POST['conf_resetSenha']) && isset($_SESSION['inserindo_senha']) && $_SESSION['inserindo_senha'] && !$erro){
        // Verificar campos vazios
        if (empty($_POST['Confsenha']) || empty($_POST['senha'])){
            echo "<script>window.alert('Todos os campos devem ser preenchidos!');</script>";
        } else {
            // VERIFICAR SE SÃO IGUAIS
            $confirmar_senha = $_POST['Confsenha'];
            $senha = $_POST['senha'];
            if ($senha == $confirmar_senha){
                $cpf = $_SESSION['cpf_resetarSenha'];
                $sqlUpdate_Senha = "UPDATE login SET senha = UPPER(MD5(?)), lembrar_senha = 0 WHERE cpf = ?";
                $stmt = mysqli_prepare($conexao, $sqlUpdate_Senha);
                mysqli_stmt_bind_param($stmt, "ss", $confirmar_senha, $cpf);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>window.alert('Senha atualizada com sucesso');</script>";
                    $_SESSION['inserindo_senha'] = False;
                    $inserindo_senha = $_SESSION['inserindo_senha'];                    
                } else {
                    echo "Erro ao atualizar a senha: " . mysqli_error($conexao);
                    $erro = true;
                }
                mysqli_stmt_close($stmt);            
            } else {
                echo "<script>window.alert('Os campos não estão de acordo');</script>";
                $erro = true;
            }
            unset($_SESSION['cpf_resetarSenha']);
        }
    } else if (isset($_POST['cancelar_resetSenha'])){
        $_SESSION['inserindo_senha'] = False;
        $inserindo_senha = $_SESSION['inserindo_senha'];
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu senha</title>
    <link rel="stylesheet" href="../css/index.css">

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body id="sem_tema">    
    <div class="container_login d-flex align-items-center justify-content-center flex-column">        
            <div class="container_resetSenha" id="container_reset">
                <div class="confDelete">  
                    <form action="" method="post">
                        <p style="color: black;" class="h4">Insira os campos senha e Confirmar senha.</p>
                        <span class="d-flex flex-column mt-1 align-items-center">
                            <input type="password" placeholder="Senha:" class="form-control border-primary text-dark w-100 mb-2" name="senha">
                            <input type="password" placeholder="Confirmar senha:" class="form-control border-primary text-dark w-100 mb-2" name="Confsenha">
                            <button name="conf_resetSenha" class="btn btn-sm w-50 btn-success mt-2" type="submit">Confirmar</button>
                            <button name="cancelar_resetSenha" class="btn btn-sm w-50 btn-danger mt-2" type="submit">Cancelar</button>
                        </span>
                    </form>                  
                </div>
            </div>        
        <img src="../img/logo_nomeembaixo.png" alt="" class="mb-5 logo_esqueceu_senha">
        <h3 class="texto_esqueceuSenha">ESQUECEU A SENHA ?</h3>
        <form class="d-block p-3 form_login" method="post" action="">
            <div class="form-group d-flex flex-column mt-3">
                <label for=""><input type="text" placeholder="CPF:" name="cpf" id="cpf" class="form-control border-primary text-dark w-100"></label>
                <label for=""><input type="text" placeholder="Email:" name="email" id="email" class="form-control border-primary text-dark w-100"></label>
            </div>
           <button class="btn btn-primary btn-block btn-lg mb-3" <?= $_SESSION['inserindo_senha'] ? 'disabled' : '' ?> value="1" name="enviar" type="submit">Enviar</button>
           <a href="login.php">
            <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
           </a>           
        </form>
    </div>

    <div id="rodapeI">
    </div>
    <!-- PARA O CONTAINER RESETAR SENHA -->
    <script>
    const inserindo_senha = <?= json_encode($inserindo_senha ?? false) ?>;
    if (inserindo_senha) {
        document.getElementById('container_reset').style.display = 'block';
        document.body.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    } else {
        document.getElementById('container_reset').style.display = 'none';
        document.body.style.backgroundColor = '';
    }
    </script>

    <script src="../js/mascaras.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- IONICONS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
