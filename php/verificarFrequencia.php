<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

    // FAZER OS SELECTS
    if (isset($_POST['filtrar'])){
        $resultFiltra_mes_ano = NULL;
        $resultFiltra_ano = NULL;
        $resultFiltra_mes = NULL;
        $resultFiltra_tudo = NULL;
        // FILTRA O MÊS E O ANO SELECIONADO
        if (!empty($_POST['mes']) && !empty($_POST['ano'])){
            $mes = $_POST['mes'];
            $ano = $_POST['ano'];
            $sqlFiltra_mes_ano = "SELECT * FROM tbRelatorio WHERE MES = '$mes' AND ANO = '$ano' AND REGISTRO = 'P';";
            $resultFiltra_mes_ano = mysqli_query($conexao, $sqlFiltra_mes_ano);            
        }
        // FILTRAR O ANO
        elseif (!empty($_POST['ano']) && empty($_POST['mes'])){
            $ano = $_POST['ano'];
            $sqlFiltra_ano = "SELECT * FROM tbRelatorio WHERE ANO = '$ano' AND REGISTRO = 'P';";
            $resultFiltra_ano = mysqli_query($conexao, $sqlFiltra_ano);                       
        }
        // FILTRAR PELO MES
        elseif (!empty($_POST['mes']) && empty($_POST['ano'])){
            $mes = $_POST['mes'];
            $sqlFiltra_mes = "SELECT * FROM tbRelatorio WHERE MES = '$mes' AND REGISTRO = 'P';";
            $resultFiltra_mes = mysqli_query($conexao, $sqlFiltra_mes);            
        }    
        // FILTRA TUDO
        elseif (empty($_POST['mes']) && empty($_POST['ano'])){
            // SELECIONA TODOS OS DADOS DE tbRelatorio
            $sqlFiltra_tudo = "SELECT * FROM tbRelatorio WHERE REGISTRO = 'P';";
            $resultFiltra_tudo = mysqli_query($conexao, $sqlFiltra_tudo);            
        }
    } else {
        $sqlFiltra_mes_ano = NULL;
        $resultFiltra_ano = NULL;
        $resultFiltra_mes = NULL;        
        // Não clicou em filtrar
        // SELECIONA TODOS OS DADOS DE tbRelatorio
        $sqlFiltra_tudo = "SELECT * FROM tbRelatorio WHERE REGISTRO = 'P';";
        $resultFiltra_tudo = mysqli_query($conexao, $sqlFiltra_tudo);   
    }

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Frequência</title>
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
                    <ion-icon name="create-outline" class="icones_nav"></ion-icon>
                    <a href="alteracao.php" class="nav-link" id="linksNavegacao">Alteração</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="document-text-outline" class="icones_nav"></ion-icon>
                    <a href="documentos.php" class="nav-link" id="linksNavegacao">Documentos</a>
                </li>
                <li class="nav-item itens_navegadores">
                    <ion-icon name="checkbox-outline" class="icones_nav" id="linksNavegacaoSelecionado"></ion-icon>
                    <a href="frequenciaBeneficiario.php" class="nav-link" id="linksNavegacaoSelecionado">Frequência</a>
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
            <h3 style="color: var(--cor_letras);">Verificar Frequência</h3>
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
                    <a href="login.php" style="color: rgb(139, 10, 10);" onclick="logout()">Sair</a>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="post">
        <main class="mt-5 container d-flex flex-column align-items-center">
            <h2 style="text-align: center;" class="mb-4" id="subtitulos_paginas">
                Aqui você consegue ver as frequências dos beneficiários
            </h2>
            <h2 style="text-align: center; color: #17a2b9;" class="mb-4">
                Ano
            </h2>
            <select name="ano" id="" class="form-select form-select-md w-50">
                <option value="" selected></option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
            <h2 style="text-align: center; color: #17a2b9;" class="mb-4 mt-2">
                Mês
            </h2>
            <select name="mes" id="" class="form-select form-select-md w-50">
                <option value="" selected></option>
                <option value="JAN">Janeiro</option>
                <option value="FEV">Fevereiro</option>
                <option value="MAR">Março</option>
                <option value="ABR">Abril</option>
                <option value="MAI">Maio</option>
                <option value="JUN">Junho</option>
                <option value="JUL">Julho</option>
                <option value="AGO">Agosto</option>
                <option value="SET">Setembro</option>
                <option value="OUT">Outubro</option>
                <option value="NOV">Novembro</option>
                <option value="DEZ">Dezembro</option>
            </select>
            <button class="text-light mt-4" id="botao_pesquisarVF" name="filtrar">
                <div class="d-flex p-0 align-items-center w-100 justify-content-between">                    
                    <span style="font-size: 18px;">Filtrar</span>
                    <ion-icon name="filter-outline" style="font-size: 25px;"></ion-icon>
                </div>
            </button>
            <div class=" w-100 p-1 d-flex flex-row m-5 tabelaVF">
                <table class="my-table">
                    <thead class="w-100">
                        <tr> 
                            <td class="titulo_colunasVF">ANO</td>
                            <td class="titulo_colunasVF">MES</td>
                            <td class="titulo_colunasVF">NOME</td>
                            <td class="titulo_colunasVF">CPF</td>
                        </tr>
                    </thead>
                    <tbody id="frequencia">
                        <!-- FILTRANDO ANO E MES -->
                        <?php if(isset($_POST['filtrar']) && ($resultFiltra_mes_ano && mysqli_num_rows($resultFiltra_mes_ano) > 0)): ?>
                            <?php while ($dados_Filtrado = mysqli_fetch_assoc($resultFiltra_mes_ano)): ?>
                                <tr>
                                    <td><?= $dados_Filtrado['ANO'] ?></td>
                                    <td><?= $dados_Filtrado['MES'] ?></td>
                                    <td><?= $dados_Filtrado['nome'] ?></td>
                                    <td><?= $dados_Filtrado['cpf'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- // FILTRANDO SOMENTE ANO -->
                        <?php elseif (isset($_POST['filtrar']) && ($resultFiltra_ano && mysqli_num_rows($resultFiltra_ano) > 0)) : ?>
                            <?php while ($dados_Filtrado = mysqli_fetch_assoc($resultFiltra_ano)): ?>
                                <tr>
                                    <td><?= $dados_Filtrado['ANO'] ?></td>
                                    <td><?= $dados_Filtrado['MES'] ?></td>
                                    <td><?= $dados_Filtrado['nome'] ?></td>
                                    <td><?= $dados_Filtrado['cpf'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- FILTRANDO SOMENTE MES -->
                        <?php elseif(isset($_POST['filtrar']) && ($resultFiltra_mes && mysqli_num_rows($resultFiltra_mes) > 0)): ?>
                            <?php while ($dados_Filtrado = mysqli_fetch_assoc($resultFiltra_mes)): ?>
                                <tr>
                                    <td><?= $dados_Filtrado['ANO'] ?></td>
                                    <td><?= $dados_Filtrado['MES'] ?></td>
                                    <td><?= $dados_Filtrado['nome'] ?></td>
                                    <td><?= $dados_Filtrado['cpf'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- FILTRANDO TUDO -->
                        <?php elseif((empty($_POST['filtrar']) && !empty($resultFiltra_tudo)) || (isset($_POST['filtrar']) && !empty($resultFiltra_tudo) && mysqli_num_rows($resultFiltra_tudo) > 0)): ?>
                            <?php while ($dados_Filtrado = mysqli_fetch_assoc($resultFiltra_tudo)): ?>
                                <tr>
                                    <td><?= $dados_Filtrado['ANO'] ?></td>
                                    <td><?= $dados_Filtrado['MES'] ?></td>
                                    <td><?= $dados_Filtrado['nome'] ?></td>
                                    <td><?= $dados_Filtrado['cpf'] ?></td>
                                </tr>
                            <?php endwhile; ?>                         
                        <?php endif; ?>                                                                                                            
                    </tbody>
                </table>                
            </div>
            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="frequenciaBeneficiario.php" class="text-decoration-none">
                        <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                    </a>
                    <p>Voltar</p>
                </span>                               
            </div>
        </main>
    </form>

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
