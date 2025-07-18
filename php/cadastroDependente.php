<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// PRECISA VERIFICAR A QUANTIDADE DE CARACTERES EM cpf 
include_once('../conexao_banco.php'); 
include_once('../routes/verificacao_logado.php'); 
include_once("../routes/dados_usuarioLogado.php");

// Se vier pela URL
if (isset($_GET['IDbeneficiario']) && isset($_GET['dependentes_pendentes'])) {
    $_SESSION['IDBeneficiario'] = $_GET['IDbeneficiario'];
}

// Recuperar o ID armazenado
$idBeneficiarioPendente = $_SESSION['IDBeneficiario'] ?? null;
$cpfBeneficiarioSelecionado = "";

// Buscar CPF se houver ID
if (!empty($idBeneficiarioPendente)) {
    $sqlSelect_beneficiarioP = "SELECT cpf FROM tbBeneficiario WHERE ID = '$idBeneficiarioPendente'";
    $resultCPF = $conexao->query($sqlSelect_beneficiarioP);
    if ($resultCPF->num_rows > 0) {
        $beneficiario_data = $resultCPF->fetch_assoc();
        $cpfBeneficiarioSelecionado = $beneficiario_data['cpf'];
        $_SESSION['cpfBeneficiario'] = $cpfBeneficiarioSelecionado;
    }
}

// SELECT geral para popular caso não venha de outra página
$sqlSelect_tbBeneficiario = "SELECT cpf FROM tbBeneficiario WHERE quantos_dependentes > 0";
$result1 = $conexao->query($sqlSelect_tbBeneficiario);

// Flags de controle
$em_branco = false;
$MAX_dep = false;
$jaCadastrado = false;
$cadastrado_dependente = false;
$incoerencia = false;

// Lógica de cadastro
if (isset($_POST['cadastrar']) 
    && !empty($_POST['nome_completo']) 
    && !empty($_POST['cpfDependente']) 
    && !empty($_POST['data_nascimento']) 
    && !empty($_POST['rbParentesco'])     
    && !empty($_POST['rbPCD'])     
    && !empty($_POST['rbPossuiBenf'])
) {
    if (!$em_branco && !$jaCadastrado && !$cadastrado_dependente && !$MAX_dep && !$incoerencia) {

        $cpfBeneficiario = $_POST['cpfBeneficiario'] ?? ($_SESSION['cpfBeneficiario'] ?? null);

        if (!empty($cpfBeneficiario)) {
            $sqlSelect_beneficiario = "SELECT * FROM tbBeneficiario WHERE cpf = '$cpfBeneficiario'";
            $resultBenef = $conexao->query($sqlSelect_beneficiario);

            if ($resultBenef->num_rows > 0) {
                while ($benefiario_data = $resultBenef->fetch_assoc()) {
                    $idBeneficiario = $benefiario_data['ID'];
                    $qnts_dependentes = $benefiario_data['quantos_dependentes'];

                    $sqlSelect_dependentes = "SELECT * FROM filho_dependente WHERE idBeneficiario = '$idBeneficiario'";
                    $resultDep = $conexao->query($sqlSelect_dependentes);
                    $qtd_dep_cadastrados = $resultDep->num_rows;

                    if ($qnts_dependentes - $qtd_dep_cadastrados <= 0) {
                        $MAX_dep = true;
                    }
                }
            }
        } else {
            echo "<script>alert('CPF do beneficiário não encontrado.');</script>";
            $em_branco = true;
        }

        $cpfDependente = str_replace(['-', '.', ' '], '', $_POST['cpfDependente']);
        $sqlCheckDep = "SELECT * FROM filho_dependente WHERE cpf = '$cpfDependente'";
        $resultCheckDep = $conexao->query($sqlCheckDep);
        if ($resultCheckDep->num_rows > 0) {
            $jaCadastrado = true;
        }

        $nome = $_POST['nome_completo'];
        $data_nascimento = $_POST['data_nascimento'];
        $parentesco = $_POST['rbParentesco'];
        $pcd = $_POST['rbPCD'];
        $possuiBenf = $_POST['rbPossuiBenf'];

        if ($parentesco == "Filho_M") {
            $idade = (new DateTime($data_nascimento))->diff(new DateTime())->y;
            if ($idade >= 18) {
                $incoerencia = true;
            }
        } elseif ($parentesco == "Parente_Pcd" && $pcd == "N") {
            $incoerencia = true;
        } elseif ($parentesco != "Parente_Pcd" && $pcd == "S") {
            $incoerencia = true;
        }

        if ($possuiBenf == "S") {
            if (!empty($_POST['beneficioDependente']) && !empty($_POST['valor_benecicioDependente'])) {
                $beneficio = $_POST['beneficioDependente'];
                $valor_beneficio = $_POST['valor_benecicioDependente'];
            } else {
                $em_branco = true;
            }
        }

        if ($pcd == "S") {
            if (!empty($_POST['rbPossuiLaudo']) && !empty($_POST['nome_doenca'])) {
                $rbPossuiLaudo = $_POST['rbPossuiLaudo'];
                $nome_doenca = $_POST['nome_doenca'];
            } else {
                $em_branco = true;
            }
        } else {
            $rbPossuiLaudo = "N";
            $nome_doenca = "-";
        }

        if (empty($idBeneficiario)) {
            $em_branco = true;
        }

        if (!$em_branco && !$MAX_dep && !$jaCadastrado && !$incoerencia) {
            $sqlInsert = "INSERT INTO filho_dependente 
            (nome_filho_dependente, cpf, data_nascimento_filho_dep, parentesco, PCD, laudo, doenca, idBeneficiario)
            VALUES ('$nome', '$cpfDependente', '$data_nascimento', '$parentesco', '$pcd', '$rbPossuiLaudo', '$nome_doenca', '$idBeneficiario')";

            if ($conexao->query($sqlInsert)) {
                $cadastrado_dependente = true;
                unset($_SESSION['IDBeneficiario'], $_SESSION['cpfBeneficiario']);
                $idBeneficiarioPendente = null;
            } else {
                echo "<script>alert('Erro ao inserir: {$conexao->error}');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Dependente</title>
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
                    <ion-icon name="person-add-outline" class="icones_nav" id="linksNavegacaoSelecionado"></ion-icon>
                    <a href="cadastro.php" class="nav-link" id="linksNavegacaoSelecionado">Cadastro</a>
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

    <div class="d-flex justify-content-between w-100" id="titulo_home">
        <span class="text-start w-25">
            <h3 style="color: var(--cor_letras);">Cadastro Dependente</h3>
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
        <form action="" class="container_formularios" method="post">   
            <?php if($MAX_dep): ?>        
                <script>window.alert("A quantidade de dependentes relacionados ao beneficiário excedeu.");</script>
            <?php elseif($incoerencia): ?>
                <script>window.alert("Há uma incoêrencia em relação ao parentesco do(a) dependente");</script>
            <?php elseif($em_branco): ?>
                <script>window.alert("Há campos em branco.");</script>                
            <?php elseif($jaCadastrado): ?>
                <script>window.alert("Dependente já cadastrado.");</script>
            <?php elseif ($cadastrado_dependente): ?>
                <script>window.alert("Cadastrado com sucesso");</script>
            <?php endif; ?>

            <div class="d-flex justify-content-around w-100 input-group mb-4">
                <?php if (!empty($idBeneficiarioPendente) && !empty($cpfBeneficiarioSelecionado)): ?>
                    <!-- Caso venha pela URL, exibe bloqueado e envia via hidden -->
                    <select class="form-select form-select-md w-50" disabled>
                        <option value="<?= $cpfBeneficiarioSelecionado ?>" selected><?= $cpfBeneficiarioSelecionado ?></option>
                    </select>
                    <!-- Hidden para enviar o CPF corretamente no POST -->
                    <input type="hidden" name="cpfBeneficiario" value="<?= $cpfBeneficiarioSelecionado ?>">
                <?php else: ?>
                    <!-- Caso fluxo normal, exibe select liberado -->
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
                <?php endif; ?>
                <button id="buttonPesquisarCPF" class="btn btn-primary" type="submit" name="pesquisarCPF">Pesquisar</button>
            </div>

            <!-- DADOS PESSOAIS -->
            <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">Dados Pessoais</h3>
            <div class="d-flex flex-column container">
                <label class="form-label">Nome Completo:</label>
                <input type="text" required class="form-control border" name="nome_completo" 
                    value="<?php if(isset($_POST['nome_completo']) && !$cadastrado_dependente) echo $_POST['nome_completo']; ?>">
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-3">
                    <label for="">CPF</label>
                    <input type="text" maxlength="14" minlength="14" id="cpf" required class="form-control" name="cpfDependente"
                        value="<?php if(isset($_POST['cpfDependente']) && !$cadastrado_dependente) echo $_POST['cpfDependente']; ?>">
                </span>
                <span class="col-lg-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" required
                        value="<?php if(isset($_POST['data_nascimento']) && !$cadastrado_dependente) echo $_POST['data_nascimento']; ?>">
                </span>
                <span class="col-lg-4">
                    <label for="">Parentesco</label>
                    <select class="form-select form-select-md" name="rbParentesco" required>
                        <option value=""></option>
                        <option value="Filho_M" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Filho_M' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Filho(a) - Menor</option>
                        <option value="Parente_Pcd" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Parente_Pcd' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Parente - PCD</option>
                        <option value="Mae ou pai" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Mae ou pai' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Mãe ou pai</option>
                        <option value="Neto(a)" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Neto(a)' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Neto(a)</option>
                        <option value="Irmao(a)" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Irmao(a)' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Irmão(ã)</option>
                        <option value="Outro" <?php echo (isset($_POST['rbParentesco']) && $_POST['rbParentesco'] == 'Outro' && !$cadastrado_dependente) ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </span>
            </div>                    

            <!-- SITUAÇÃO -->
            <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">Situação</h3>
            <div class="d-flex justify-content-between formularios_Beneficiario mt-3 container">
                <div class="d-flex flex-row col-lg-8 col-sm-12 container justify-content-between" id="form_beneficiario_beneficio">
                    <span class="col-4 container p-0">
                        <label for="">Possui Benefício?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S"
                                    <?php echo (isset($_POST['rbPossuiBenf']) && $_POST['rbPossuiBenf'] == 'S' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N"
                                    <?php echo (isset($_POST['rbPossuiBenf']) && $_POST['rbPossuiBenf'] == 'N' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                        <select name="beneficioDependente" class="form-select form-select-md">
                            <option value=""></option>
                            <option value="Aposentadoria" <?php echo(isset($_POST['beneficioDependente']) && $_POST['beneficioDependente'] == 'Aposentadoria' && !$cadastrado) ? 'selected ': ''; ?>>Aposentadoria</option>
                            <option value="Benefício de Prestação Continuada (BPC)" <?php echo(isset($_POST['beneficioDependente']) && $_POST['beneficioDependente'] == 'Benefício de Prestação Continuada (BPC)' && !$cadastrado) ? 'selected ': ''; ?>>Benefício de Prestação Continuada (BPC)</option>
                            <option value="Novo Bolsa Família" <?php echo(isset($_POST['beneficioDependente']) && $_POST['beneficioDependente'] == 'Novo Bolsa Família' && !$cadastrado) ? 'selected ': ''; ?>>Bolsa Família</option>
                            <option value="Vale-gas" <?php echo(isset($_POST['beneficioDependente']) && $_POST['beneficioDependente'] == 'Vale-gas' && !$cadastrado) ? 'selected ': ''; ?>>Vale Gás</option>
                            <option value="Outros" <?php echo(isset($_POST['beneficioDependente']) && $_POST['beneficioDependente'] == 'Outros' && !$cadastrado) ? 'selected ': ''; ?>>Outros</option>                            
                        </select>                
                    </span> 
                </div>           
                <span class="col-lg-4">
                    <label for="">Valor</label>
                    <input type="number" class="form-control" name="valor_benecicioDependente"
                        value="<?php if(isset($_POST['valor_benecicioDependente']) && !$cadastrado_dependente) echo $_POST['valor_benecicioDependente']; ?>">
                </span> 
            </div>

            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0">
                    <span class="col-6">
                        <label for="">PCD?</label>
                        <div class="d-flex container p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="S"
                                    <?php echo (isset($_POST['rbPCD']) && $_POST['rbPCD'] == 'S' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="N"
                                    <?php echo (isset($_POST['rbPCD']) && $_POST['rbPCD'] == 'N' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-6 justify-content-start">
                        <label for="">Possui Laudo Médico?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="S"
                                    <?php echo (isset($_POST['rbPossuiLaudo']) && $_POST['rbPossuiLaudo'] == 'S' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="N"
                                    <?php echo (isset($_POST['rbPossuiLaudo']) && $_POST['rbPossuiLaudo'] == 'N' && !$cadastrado_dependente) ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>                 
                </div>           
                <span class="col-lg-6">
                    <label for="">Nome da Comorbidade</label>
                    <input type="text" class="form-control" name="nome_doenca"
                        value="<?php if(isset($_POST['nome_doenca']) && !$cadastrado_dependente) echo $_POST['nome_doenca']; ?>">
                </span> 
            </div> 

            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="cadastro.php" class="text-decoration-none">
                        <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                    </a>
                    <p>Voltar</p>
                </span>               
                <button type="submit" class="botoes_crud" name="cadastrar" value="1">
                    <span class="align-items-center text-center">
                        <ion-icon name="cloud-done-outline" id="btSalvar"></ion-icon>
                        <p>Salvar</p>
                    </span>
                </button>       
            </div>
        </form>
    </main>
    <!-- MÁSCARAS -->
    <script src="../js/mascaras.js"></script>
    <!-- BOOTSTRAP -->
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
