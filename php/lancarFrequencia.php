<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

    // SELECT OS CPFS Beneficiarios    
    $sqlSelect_tbBeneficiario = "SELECT cpf FROM tbBeneficiario";
    $result1 = $conexao->query($sqlSelect_tbBeneficiario);

    $campo = [];
    // LISTAR TODAS AS FREQUÊNCIAS
    if (isset($_POST['pesquisar']) && !empty($_POST['cpfBeneficiario'])){
        $anosF = ['2023', '2024', '2025'];
        $mesesF = [
            'jan' => 'JAN', 'fev' => 'FEV', 'mar' => 'MAR', 'abr' => 'ABR',
            'mai' => 'MAI', 'jun' => 'JUN', 'jul' => 'JUL', 'ago' => 'AGO',
            'set' => 'SET', 'out' => 'OUT', 'nov' => 'NOV', 'dez' => 'DEZ'
        ];
        $cpfBeneficiario = $_POST['cpfBeneficiario'];
        $_SESSION['cpf'] = $cpfBeneficiario;

        $sqlSelect_frequencia = "SELECT * FROM tbRelatorio WHERE cpf = '$cpfBeneficiario' AND ANO BETWEEN '2023' AND '2025';";
        $result2 = mysqli_query($conexao, $sqlSelect_frequencia);
        
            if (mysqli_num_rows($result2) > 0){
                $campo = []; // Zera o array antes para evitar sujeira anterior                

                while ($dados_frequencia = mysqli_fetch_assoc($result2)){
                    $ano = $dados_frequencia['ANO'];       // ex: '2023'
                    $mes_bd = strtoupper(trim($dados_frequencia['MES']));
                    $mes_pt = array_search($mes_bd, $mesesF);

                    
                    $registro = trim(strtoupper($dados_frequencia['REGISTRO']));
                    if ($registro == 'P' && $mes_pt !== false) {
                        $campo["{$mes_pt}_{$ano}"] = 'P';                       
                    }
                }
            }        

    } else if (isset($_POST['pesquisar']) && empty($_POST['cpfBeneficiario'])){
        echo "<script>window.alert('Você deve primeiro selecionar um cpf');</script>";
    }

    if (isset($_POST['lancar']) && !empty($_SESSION['cpf'])){
        // SELECT DO ID BENEFICIÁRIO
        $cpf = $_SESSION['cpf'];
        $sql = "SELECT ID FROM tbBeneficiario WHERE cpf = '$cpf';";
        $result0 = mysqli_query($conexao, $sql);

        if(mysqli_num_rows($result0) > 0){
            while ($dados_tbBeneficiario = mysqli_fetch_assoc($result0)){
                $idBeneficiario = $dados_tbBeneficiario['ID'];
                $_SESSION['idBeneficiario'] = $idBeneficiario;                
            }

            $anos = ['2023', '2024', '2025'];
            $meses = [
                'jan' => 'JAN', 'fev' => 'FEV', 'mar' => 'MAR', 'abr' => 'ABR',
                'mai' => 'MAI', 'jun' => 'JUN', 'jul' => 'JUL', 'ago' => 'AGO',
                'set' => 'SET', 'out' => 'OUT', 'nov' => 'NOV', 'dez' => 'DEZ'
            ];

            // foreach - percorre cada elemento de uma array, executa um boloco de código para cada elemento, tipo for
            foreach ($anos as $ano) {
                // mes_pt - mes_minusculo | mes_bd - mes_maiusculo
                foreach ($meses as $mes_pt => $mes_bd) {
                    $campo = "{$mes_pt}_{$ano}";
                    if (!empty($_POST[$campo])) {
                        $sql = "UPDATE frequencia SET REGISTRO = 'P'
                                WHERE ANO = '$ano' AND MES = '$mes_bd' AND idBeneficiario = '$idBeneficiario'";
                        $result = mysqli_query($conexao, $sql);
                        if (!$result) {
                            die("Erro ao atualizar frequência para $mes_bd/$ano: " . mysqli_error($conexao));
                        }
                    }
                }
            }

            echo "<script>window.alert('Frequência salva com sucesso');</script>";
        }
    }


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançar Frequência</title>
    <!-- icone no titulo da pagina -->
    <link rel="icon" href="../img/logo_semnome.png">

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
                class="navbar-toggler-icon">
            </span><!-- Aparece quado você diminui o tamanho da tela para o tamanho definido em "nav>navbar-expand-(md)" -->
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
            <h3 style="color: var(--cor_letras);">Lançar Frequência</h3>
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
        <form action="" method="post">
            <div class="mb-4">
                <h3 style="text-align: center;" class="mb-4" id="subtitulos_paginas">
                    Aqui você consegue saber informações sobre seu beneficiário
                </h3>
                <div class="d-flex justify-content-around w-100 input-group">
                    <select class="form-select form-select-md w-50" name="cpfBeneficiario" required>
                        <option value="">Selecione o CPF do(a) Beneficiário(a)</option>
                        <?php if ($result1->num_rows > 0): ?>
                            <?php while ($beneficiario = $result1->fetch_assoc()): ?>
                                <option value="<?= $beneficiario['cpf'] ?>"
                                    <?= (isset($_POST['cpfBeneficiario']) && $_POST['cpfBeneficiario'] == $beneficiario['cpf']) ? 'selected' : '' ?>>
                                    <?= $beneficiario['cpf'] ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                    <button id="buttonPesquisarCPF" name="pesquisar" type="submit" value="1">
                        Pesquisar
                    </button>
                </div>
            </div>
            <!-- CAROUSEL -->
            <h3 style="text-align: center;" class="mb-4" id="subtitulos_paginas">
                Ano
            </h3>
            <div class="mb-4 carousel carousel-dark slide" id="carouselFrequencia">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <h4 class="text-center" id="subtitulos_paginas">
                            2023
                        </h4>
                        <div class="d-flex flex-row w-100 justify-content-around">
                            <span class="d-flex flex-column">
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jan_2023" <?= ($campo["jan_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Janeiro</label>
                                </span>
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="fev_2023" <?= ($campo["fev_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Fevereiro</label>
                                </span>
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mar_2023" <?= ($campo["mar_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Março</label>
                                </span>
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="abr_2023" <?= ($campo["abr_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Abril</label>
                                </span>
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mai_2023" <?= ($campo["mai_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Maio</label>
                                </span>
                                <span class="mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jun_2023" <?= ($campo["jun_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Junho</label>
                                </span>
                            </span>
                            <span>
                                <span class="d-flex flex-column">
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="jul_2023" <?= ($campo["jul_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Julho</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="ago_2023" <?= ($campo["ago_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Agosto</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="set_2023" <?= ($campo["set_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Setembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="out_2023" <?= ($campo["out_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Outubro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="nov_2023" <?= ($campo["nov_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Novembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="dez_2023" <?= ($campo["dez_2023"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Dezembro</label>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <h4 class="text-center" id="subtitulos_paginas">
                            2024
                        </h4>
                        <div class="d-flex flex-row w-100 justify-content-around">
                            <span class="d-flex flex-column">
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jan_2024" <?= ($campo["jan_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Janeiro</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="fev_2024" <?= ($campo["fev_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Fevereiro</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mar_2024" <?= ($campo["mar_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Março</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="abr_2024" <?= ($campo["abr_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Abril</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mai_2024" <?= ($campo["mai_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Maio</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jun_2024" <?= ($campo["jun_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Junho</label>
                                </span>
                            </span>
                            <span>
                                <span class="d-flex flex-column">
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="jul_2024" <?= ($campo["jul_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Julho</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="ago_2024" <?= ($campo["ago_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Agosto</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="set_2024" <?= ($campo["set_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Setembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="out_2024" <?= ($campo["out_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Outubro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="nov_2024" <?= ($campo["nov_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Novembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="dez_2024" <?= ($campo["dez_2024"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Dezembro</label>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <h4 class="text-center" id="subtitulos_paginas">
                            2025
                        </h4>
                        <div class="d-flex flex-row w-100 justify-content-around">
                            <span class="d-flex flex-column">
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jan_2025" <?= ($campo["jan_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Janeiro</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="fev_2025" <?= ($campo["fev_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Fevereiro</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mar_2025" <?= ($campo["mar_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Março</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="abr_2025" <?= ($campo["abr_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Abril</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="mai_2025" <?= ($campo["mai_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Maio</label>
                                </span>
                                <span class="d-flex d-row mes_freqBenef">
                                    <input type="checkbox" value="P" class="caixaMarcacao" name="jun_2025" <?= ($campo["jun_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                    <label for="">Junho</label>
                                </span>
                            </span>
                            <span>
                                <span class="d-flex flex-column">
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="jul_2025" <?= ($campo["jul_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Julho</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="ago_2025" <?= ($campo["ago_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Agosto</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="set_2025" <?= ($campo["set_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Setembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="out_2025" <?= ($campo["out_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Outubro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="nov_2025" <?= ($campo["nov_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Novembro</label>
                                    </span>
                                    <span class="d-flex d-row mes_freqBenef">
                                        <input type="checkbox" value="P" class="caixaMarcacao" name="dez_2025" <?= ($campo["dez_2025"] ?? '') == 'P' ? 'checked ' . ' disabled' : '' ?>>
                                        <label for="">Dezembro</label>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselFrequencia"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselFrequencia"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="frequenciaBeneficiario.php" class="text-decoration-none">
                        <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                    </a>
                    <p>Voltar</p>
                </span>
                <?php if($funcao == 'Administrador'): ?>
                    <button type="submit" class="botoes_crud" name="deletar" value="2">
                        <span class="align-items-center text-center">
                            <ion-icon name="close-circle-outline" id="btCancelar"></ion-icon>
                            <p>Deletar</p>
                        </span>                        
                    </button>
                <?php endif; ?>
                <button type="submit" class="botoes_crud" name="lancar" value="1">
                    <span class="align-items-center text-center">
                        <ion-icon name="cloud-done-outline" id="btSalvar"></ion-icon>
                        <p>Salvar</p>
                    </span>                      
                </button>
            </div>
        </form>
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
