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
    <title>Alteração Voluntário</title>
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
                    <a href="alteracao.php" class="nav-link" id="linksNavegacaoSelecionado">Alteração</a>
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

    <div class="d-flex justify-content-between w-100" id="titulo_home">
        <span class="text-start w-25">
            <h3 style="color: var(--cor_letras);">Alteração Voluntário</h3>
        </span>
        <div class="container_userPaginas">
            <span class="mr-3 d-none d-md-block" id="usuario_home">
                <div id="nome-titulo_home" class="d-flex flex-row">
                    <p class="d-block pe-1">Nome: </p>
                    <p><?php echo $primeiro_nome ?></p>
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
    <main class="mt-5 d-flex flex-column container">
        <div class="d-flex justify-content-around w-100 input-group mb-4">
            <!-- SELECT DE TODOS AS PESSOAS DA TABELA DE ESPERA -->
            <select class="form-select form-select-md w-50">
                <option value="">CPF do(a) Voluntário(a)</option>
            </select>
            <button id="buttonPesquisarCPF">
                Pesquisar
            </button>
        </div>
        <!-- DADOS PESSOAIS -->
        <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
            Dados Voluntário
        </h3>
        <div class="d-flex flex-column container">
            <label class="form-label">Nome Completo:</label>
            <input type="text" class="form-control border" id="nome" disabled>
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-12 col-md-12">
                <label for="">CPF</label>
                <input type="text" class="form-control" id="cpf" disabled>
            </span>
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-5 col-md-5">
                <label>Telefone:</label>
                <input type="number" class="form-control" id="telefone">
            </span>
            <span class="col-lg-6 col-md-6">
                <label>Email:</label>
                <input type="text" class="form-control form-control-xl" id="email">
            </span>
        </div>
        <div class=" mt-3 container">
            <label for="floatingPassword">Cadastrar senha temporária</label>
            <input type="password" class="form-control" id="senhaTemporaria">
        </div>
        <div class=" mt-3 container">
            <label for="floatingPassword">Confirmar senha</label>
            <input type="password" class="form-control" id="confirmarSenha">
        </div>       
        <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
            <span class="align-items-center text-center">
                <a href="alteracao.php" class="text-decoration-none">
                    <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                </a>
                <p>Voltar</p>
            </span>
            <span class="align-items-center text-center" onclick="cancelarCadastro()">
                <ion-icon name="close-circle-outline" id="btCancelar"></ion-icon>
                <p>Deletar</p>
            </span>
            <span class="align-items-center text-center" onclick="Cadastrar_Voluntario()">
                <ion-icon name="cloud-done-outline" id="btSalvar"></ion-icon>
                <p>Salvar</p>
            </span>
        </div>
    </main>
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
