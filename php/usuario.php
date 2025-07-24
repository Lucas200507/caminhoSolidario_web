<?php
    // PRECIAS COLOCAR AS MÁSCARAS MANUALMENTE COM PHP

    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

    $cpf_logado = substr($cpf_logado, 0, 3) . "." . substr($cpf_logado, "3");
    $cpf_logado = substr($cpf_logado, 0, 7) . "." . substr($cpf_logado, "7");
    $cpf_logado = substr($cpf_logado, 0, 11) . "-" . substr($cpf_logado, "11");

    $telefone = substr($telefone, 0, 0) . "(" . substr($telefone, "0");
    $telefone = substr($telefone, 0, 3) . ")" . substr($telefone, "3");  
    
    // Resetar_senha
    if (isset($_POST['conf_Senha'])){
        if ()
    }
    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario - Caminho Solidario</title>
    <!-- icone no titulo da pagina -->
    <link rel="icon" href="../img/logo_semnome.png">

    <link rel="stylesheet" href="../css/index.css">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- LINKANDO JS EXTERNO -->
    <script src="../js/index.js" defer></script>
</head>

<body id="telaBody">
    <nav class="navbar navbar-expand-lg navbar-dark justify-content-between" style="padding: 0.8em;">
        <!-- logo -->
        <a href="#" class="navbar-brand p-0 d-block" id="container_logoHome">
            <img src="../img/logodolado - Copia.png" alt="" id="logo_navegacaoHome" class=" m-0">
        </a>

        <!-- Menu Hamburguer -->
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navegacaoHome" id="botaoHamburguer"
            type="button">
            <span
                class="navbar-toggler-icon"></span><!-- Aparece quado você diminui o tamanho da tela para o tamanho definido em "nav>navbar-expand-(md)" -->
        </button>

        <!-- navegação -->
        <div class="collapse navbar-collapse justify-content-end" id="navegacaoHome">
            <ul class="navbar-nav ml-1 justify-content-around" id="navegadores_links">
                <li class="nav-item itens_navegadores">
                    <ion-icon name="person-add-outline" class="icones_nav"></ion-icon>
                    <a href="cadastro.php" class="nav-link" id="linksNavegacao">Cadastro</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="create-outline" class="icones_nav"></ion-icon>
                    <a href="alteracao.php" class="nav-link" id="linksNavegacao">Alteração</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="document-text-outline" class="icones_nav"></ion-icon>
                    <a href="documentos.php" class="nav-link" id="linksNavegacao">Documentos</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="checkbox-outline" class="icones_nav"></ion-icon>
                    <a href="frequenciaBeneficiario.php" class="nav-link" id="linksNavegacao">Frequência</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="list-outline" class="icones_nav"></ion-icon>
                    <a href="Servicos.php" class="nav-link" id="linksNavegacao">Serviços</a>
                </li>
            </ul>
        </div>       
    </nav>
    <div class="p-2 d-flex w-100 justify-content-end">
        <div class="container_mudarTema">
            <div id="icon_mudarTema_moon" class="icon_mudarTema" onclick="Mudar_tema()">
                <ion-icon name="moon-outline"></ion-icon>
            </div>
            <div id="icon_mudarTema_sun" class="icon_mudarTema" onclick="Mudar_tema()">
                <ion-icon name="sunny-outline"></ion-icon>
            </div>
        </div>
    </div>
    <main class="main_usuario w-100">
        <div class="containerIcon mb-5">                 
            <span id="editIcon">
                <a href="#" id="imgFotoU"  class="iconImg"><ion-icon name="image-outline"></ion-icon></a>                              
            </span>
            <span id="IconUsuarioU" class="mb-4" style="background-image: url('<?php echo $icone ?>');"></span>           
            <h2 id="nomeUsuario"><?php echo $primeiro_nome ?></h2>
        </div>
        <!-- DADOS DO USUÁRIO -->
        <div class="dadosUsuario mb-5 w-100">
            <form action="">
                <div class="d-flex flex-column container">
                    <label class="form-label">Nome Completo:</label>
                    <input type="text" required class="form-control border" id="nomeCompleto_user" value="<?php echo $nome_completo ?>">
                </div>
                <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                    <span class="col-12">
                        <label for="">CPF</label>
                        <input type="text" disabled class="form-control" value="<?php echo $cpf_logado ?>" id="cpf">
                    </span>
                </div>
                <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                    <span class="col-lg-5  col-xs-12">
                        <label>Telefone:</label>
                        <input type="tel" required class="form-control" value="<?php echo $telefone?>" id="telefone">
                    </span>
                    <span class="col-lg-6  col-xs-12">
                        <label>Email:</label>
                        <input type="email" required class="form-control form-control-xl" value="<?php echo $email_logado?>">
                    </span>
                </div>               
                <div class="d-flex container justify-content-around w-100 align-items-center mb-5"
                    style="margin-top: 3em;">
                    <span class="align-items-center text-center">
                        <a href="Servicos.php" class="text-decoration-none">
                            <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                        </a>
                        <p>Voltar</p>
                    </span>                   
                    <span class="align-items-center text-center" onclick="Salvar_user()">
                        <ion-icon name="cloud-done-outline" id="btSalvar" id="btSalvar_user"></ion-icon>
                        <p>Salvar</p>
                    </span>
                </div>
            </form>
            <div class="d-flex flex-column align-items-center">
                <button class="btn mb-3" id="resetarSenha_usuario" onclick="resetar_senha()">Resetar
                    Senha
                </button>
                <div id="formResetarSenha">
                    <form action="" method="post">
                        <div class=" mt-3 container">
                            <input type="password" required class="form-control" id="senhaTemporaria"
                                placeholder="Nova senha" name="nova_senha">
                        </div>
                        <div class=" mt-3 container">
                            <input type="password" required class="form-control" id="confirmarSenha"
                                placeholder="Confirmar senha" name="conf_senha">
                        </div>
                        <span class="d-flex justify-content-center mt-4 mb-3">
                            <button value="Confirmar" name="conf_senha" type="input" class="btn" id="ConfSenhaU">Confirmar</button>                        
                        </span>
                        </div>
                        <a href="../routes/deslogar.php" class="btn mt-3" id="logout_usuario" onclick="logout()">Logout</a>
                    </form>
            </div>

    </main>    
    <!-- Conexão com Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js"
        integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+"
        crossorigin="anonymous"></script>
    <!-- IONICONS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
