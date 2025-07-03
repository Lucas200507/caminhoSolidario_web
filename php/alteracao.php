<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteracao - Caminho Solidario</title>
    <!-- icone no titulo da pagina -->
    <link rel="icon" href="../img/logo_semnome.png">

    <link rel="stylesheet" href="../css/index.css">

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- Conexao com JS -->
    <script src="../js/index.js" defer></script>        
</head>

<body id="telaBody">

    <body id="telaBody">
        <nav class="navbar navbar-expand-lg navbar-dark barraNav" style="padding: 0.8em;">
            <!-- logo -->
            <a href="#" class="navbar-brand p-0 d-block" id="container_logoHome">
                <img src="../img/logodolado - Copia.png" alt="" id="logo_navegacaoHome" class=" m-0">
            </a>

            <!-- Menu Hamburguer -->
            <button class="navbar-toggler" data-toggle="collapse" data-target="#navegacaoHome" id="botaoHamburguer">
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
                        <ion-icon name="create-outline" class="icones_nav" id="linksNavegacaoSelecionado"></ion-icon>
                        <a href="#" class="nav-link" id="linksNavegacaoSelecionado">Alteração</a>
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
        <!-- TITULO -->
        <div class="d-flex justify-content-between w-100" id="titulo_home">
            <span class="text-start w-25">
                <h3 style="color: var(--cor_letras);">Alteração</h3>
            </span>
            <div class="container_userPaginas">
                <span class="mr-3 d-none d-md-block" id="usuario_home">
                    <div id="nome-titulo_home" class="d-flex flex-row">
                        <p class="d-block pe-1">Nome: </p>
                        <p><?php echo $nome ?></p>
                    </div>
                    <div id="funcao-titulo_home" class="d-flex flex-row">
                        <p class="mr-2 pe-1">Função: </p>
                        <p> <?php echo $funcao ?></p>                    
                    </div>
                </span>
                <div class="container_iconPaginas">
                    <span id="IconUsuarioPaginas" style="background-image: url('<?php echo $icone ?>');"></span>
                    <!-- Criando um DropDown com o icon -->
                    <div id="dropdownUser" class="container_dropDownUser">
                        <a href="usuario.php">Sua conta</a>
                        <a href="../routes/deslogar.php" style="color: rgb(139, 10, 10);" onclick="logout()">Sair</a>
                    </div>
                </div>
            </div>
        </div>
        <main style="height: 700px;" id="main_cadastro">
            <div class="container_icones_cadastro d-flex align-items-center flex-column"
                onclick="navegar_alterarBeneficiario()">
                <span class="person_alterar" style="position: relative;">
                    <ion-icon name="person-outline" class="icones_cadastro"></ion-icon>
                    <ion-icon name="pencil-outline" class="icones_pen"></ion-icon>
                </span>
                <p style="font-size: 25px; font-weight: bold;">Alterar Beneficiário</p>
            </div>
            <div class="container_icones_cadastro d-flex align-items-center flex-column"
                onclick="navegar_alterarVoluntario()">
                <span class="person_alterar" style="position: relative;">
                    <ion-icon name="person-outline" class="icones_cadastro"></ion-icon>
                    <ion-icon name="pencil-outline" class="icones_pen"></ion-icon>
                </span>
                <p style="font-size: 25px; font-weight: bold;">Alterar Voluntário</p>
            </div>
            <div class="container_icones_cadastro d-flex align-items-center flex-column"
                onclick="navegar_alterarDependente()">
                <ion-icon name="people-outline" class="icones_cadastro"></ion-icon>
                <p style="font-size: 25px; font-weight: bold;">Alterar Dependente(s)</p>
            </div>
        </main>



        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
        <!-- IONICONS -->
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        
    </body>

</html>
