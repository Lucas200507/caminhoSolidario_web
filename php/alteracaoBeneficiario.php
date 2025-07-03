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
    <title>Cadastro Beneficiário</title>
    <!-- icone no titulo da pagina -->
    <link rel="icon" href="../img/logo_semnome.png" >

    <link rel="stylesheet" href="../css/index.css">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    
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
    <!-- TÍTULO -->
    <div class="d-flex justify-content-between w-100" id="titulo_home">
        <span class="text-start w-25">
            <h3 style="color: var(--cor_letras);">Alteração Beneficiário</h3>
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
    <main class="mt-5 d-flex flex-column container">
        <div class="d-flex justify-content-around w-100 input-group mb-4">
            <select class="form-select form-select-md w-50">
                <option value="">CPF do(a) Beneficiário(a)</option>
            </select>
            <button id="buttonPesquisarCPF">
                Pesquisar
            </button>
        </div>
        <!-- DADOS PESSOAIS -->
        <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
            Dados Pessoais
        </h3>
        <div class="d-flex flex-column container">
            <label class="form-label">Nome Completo:</label>
            <input type="text" class="form-control border" disabled>            
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-3">
                <label for="">CPF</label>
                <input type="text" class="form-control" disabled>
            </span>
            <span class="col-lg-3">
                <label for="">Data Nascimento</label>
                <input type="date" class="form-control">
            </span>
            <span class="col-lg-4">
                <label for="">Estado Civil</label>
                <select class="form-select form-select-md">
                    <option value=""></option>
                    <option value="">Solteiro</option>
                    <option value="">Casado</option>
                    <option value="">Viúvo</option>
                </select>
            </span>
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-5 col-sm-5 col-xs-12">
                <label>Telefone:</label>
                <input type="number" class="form-control">
            </span>
            <span class="col-lg-6 col-sm-6 col-xs-12">
                <label>Email:</label>
                <input type="text" class="form-control form-control-xl">
            </span>
        </div>
            <!-- ENDEREÇO -->
        <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
                Endereço        
        </h3>
        <div class="d-flex flex-column container">
            <label class="form-label">Endereço Completo:</label>
            <input type="text" class="form-control border">            
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-3 col-sm-3 col-md-4">
                <label for="">Cidade</label>
                <input type="text" class="form-control">
            </span>
            <span class="col-lg-3 col-sm-4 col-md-4">
                <label for="">Estado</label>
                <select class="form-select form-select-md">
                    <option value=""></option>
                    <option value="">AC - Acre</option>
                    <option value="">AL - Alagoas</option>
                    <option value="">AP - Amapá</option>
                    <option value="">AM - Amazonas</option>
                    <option value="">BA - Bahia</option>
                    <option value="">CE - Ceará</option>
                    <option value="">DF - Distrito Federal</option>
                    <option value="">ES - Espírito Santo</option>
                    <option value="">GO - Goiás</option>
                    <option value="">MA - Maranhão</option>
                    <option value="">MT - Mato Grosso</option>
                    <option value="">MS - Mato Grosso do Sul</option>
                    <option value="">MG - Minas Gerais</option>
                    <option value="">PA - Pará</option>
                    <option value="">PB - Paraíba</option>
                    <option value="">PR - Paraná</option>
                    <option value="">PE - Pernambuco</option>
                    <option value="">PI - Piauí</option>
                    <option value="">RJ - Rio de Janeiro</option>
                    <option value="">RN - Rio Grande do Norte</option>
                    <option value="">RS - Rio Grande do Sul</option>
                    <option value="">RO - Rondônia</option>
                    <option value="">RR - Roraima</option>
                    <option value="">SC - Santa Catarina</option>
                    <option value="">SE - Sergipe</option>
                    <option value="">TO - Tocantis</option>                    
                </select>
            </span>
            <span class="col-lg-4 col-sm-4 col-md-3">
                <label for="">Situação da moradia</label>
                <select class="form-select form-select-md">
                    <option value=""></option>
                    <option value="">Solteiro</option>
                    <option value="">Casado</option>
                    <option value="">Viúvo</option>
                </select>
            </span>
        </div>
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-12">
                <label>Valor do Aluguel + Despesas (Água e luz):</label>
                <input type="number" class="form-control">
            </span>            
        </div>
        <!-- SITUAÇÃO -->
        <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
            Situação
        </h3>
        <div class="d-flex justify-content-between formularios_Beneficiario  mt-3 container">
            <div class="d-flex flex-row col-lg-8 col-sm-9 container justify-content-between ps-0">
                <span class="col-lg-4 col-md-5 col-sm-5 container p-0">
                    <label for="">Possui Benefício?</label>
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Sim</label>                        
                        </div> 
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Não</label>
                        </div>               
                    </div>
                </span>
                <span class="col-lg-8 col-md-7 col-sm-7">
                    <label for="">Qual o nome do Benefício?</label>
                    <input type="text" class="form-control">
                </span> 
            </div>           
            <span class="col-lg-4 col-sm-3">
                <label for="">Valor</label>
                <input type="text" class="form-control">
            </span> 
        </div>
        <div class="d-flex flex-row justify-content-between container formularios_Beneficiario mt-3">        
            <span class="col-lg-4 col-md-4 col-sm-5 justify-content-between">
                <label for="">Possui Filhos?</label>
                <div class="d-flex container justify-content-start p-0">
                    <div class="form-check col-6">
                        <input type="radio" class="form-check-input" name="rbPossuiBenf">
                        <label for="">Sim</label>                        
                    </div> 
                    <div class="form-check col-6">
                        <input type="radio" class="form-check-input" name="rbPossuiBenf">
                        <label for="">Não</label>
                    </div>               
                </div>
            </span>
            <span class="col-lg-7 col-md-6 col-sm-6">
                <label for="">Quantos menores?</label>
                <input type="number" class="form-control">
            </span> 
        </div>
        <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
            <div class="d-flex col-lg-6 col-md-7 col-sm-8 flex-row container justify-content-between p-0">
                <span class="col-lg-6 col-md-6 col-sm-6 justify-content-start">
                    <label for="">PCD?</label>
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Sim</label>                        
                        </div> 
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Não</label>
                        </div>               
                    </div>
                </span>
                <span class="col-lg-6 col-md-6 col-sm-6 justify-content-start">
                    <label for="">Possui Laudo Médico?</label>
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Sim</label>                        
                        </div> 
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiBenf">
                            <label for="">Não</label>
                        </div>               
                    </div>
                </span>                 
            </div>           
            <span class="col-lg-6 col-md-5 col-sm-4">
                <label for="">Nome da doença</label>
                <input type="text" class="form-control">
            </span> 
        </div> 
        <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
            <span class="col-lg-5 col-sm-5 col-xs-12">
                <label>Quantos trabalham em casa:</label>
                <input type="number" class="form-control">
            </span>
            <span class="col-lg-6 col-sm-6 col-xs-12">
                <label>Renda Familiar total:</label>
                <input type="text" class="form-control">
            </span>
        </div>
        <div class="mt-3 container">
            <label for="">Data do Cadastro</label>
            <input type="date" class="form-control" disabled>
        </div>
        <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
            <span class="align-items-center text-center">
                <a href="alteracao.php" class="text-decoration-none">
                    <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                </a>
                <p>Voltar</p>
            </span>
            <span class="align-items-center text-center">
                <ion-icon name="close-circle-outline" id="btCancelar"></ion-icon>  
                <p>Cancelar</p>          
            </span>
            <span class="align-items-center text-center">
                <ion-icon name="cloud-done-outline" id="btSalvar"></ion-icon>
                <p>Salvar</p>
            </span>
        </div>       
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    <!-- IONICONS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
