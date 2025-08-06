<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");
    
  // TEM QUE FAZER UM SELECT DOS DADOS DO DEPENDENTE
     $sqlSelect_dependentes = "SELECT * FROM filho_dependente;";
     $result = $conexao->query($sqlSelect_dependentes);

    // Flags de erro
    $erro = False;
    $alterado = False;
    $erro_idade = False;

     // LISTAR OS DADOS DE DEPENDENTE PARA OUTROS CAMPOS
    if (isset($_POST['pesquisar']) && !empty($_POST['cpfDependente']) ){
        $_SESSION['cpf_dependente'] = $_POST['cpfDependente'];
        $cpf_dependente = $_SESSION['cpf_dependente'];

        $sqlSelect_dependente = "SELECT * FROM tbFilho_dependente WHERE cpf = '$cpf_dependente';";
        $resultSelect_dependente = mysqli_query($conexao, $sqlSelect_dependente);
        if(mysqli_num_rows($resultSelect_dependente) > 0){
            while ($dados_tbDependente = mysqli_fetch_assoc($resultSelect_dependente)){
                $idDependente = $dados_tbDependente['idFilho_Dependente'];
                $_SESSION['idDependente'] = $idDependente;  
                $nome_dependente = $dados_tbDependente['nome'];   
                $data_nascimento = $dados_tbDependente['data_nascimento'];
                $parentesco = $dados_tbDependente['parentesco'];
                $pcd = $dados_tbDependente['pcd'];
                $laudo = $dados_tbDependente['laudo'];
                $comorbidade = $dados_tbDependente['comorbidade'];                 
                // TEM QUE FAZER O SELECT DO BENEFICIOGOV
                $beneficioGov = $dados_tbDependente['beneficioGov'];                
            }
            if (!empty($idDependente)){
                $sqlSelect_filho_dep = "SELECT idBeneficioGov FROM filho_dependente WHERE idFilho_Dependente = '$idDependente';";
                $resultSelect_filho_dep = mysqli_query($conexao, $sqlSelect_filho_dep);
                    if(mysqli_num_rows($resultSelect_filho_dep) > 0){
                        $dado_filhoDep = mysqli_fetch_assoc($resultSelect_filho_dep);
                        $id_BeneficioGov = $dado_filhoDep['idBeneficioGov'];
                        $_SESSION['idBeneficioGov_dep'] = $id_BeneficioGov;
                    }
                    if(!empty($id_BeneficioGov)){
                        $sqlSelect_beneficioGov = "SELECT * FROM BeneficioGov WHERE idBeneficioGov = '$id_BeneficioGov';";
                        $resultSelect_benefioGov = mysqli_query($conexao, $sqlSelect_beneficioGov);
                            if(mysqli_num_rows($resultSelect_benefioGov) > 0){
                                while($dados_beneficioGov = mysqli_fetch_assoc($resultSelect_benefioGov)){
                                    $id_nomesBeneficioGov = $dados_beneficioGov['idBeneficios_gov'];
                                    $_SESSION['idNome_beneficio_dep'] = $id_nomesBeneficioGov;
                                    $valor_beneficioGov = $dados_beneficioGov['valor_beneficio'];                                    
                                }
                            }        
                    }
            }
        }
    }    
    
    // ALTERAR DEPENDENTE
    if(isset($_POST['alterar']) && !$erro && !$alterado){
        // verificar se possui algum campo obrigatório em branco. Verificar máscaras
        if(empty($_POST['data_nascimento'])){
            echo "<script>window.alert('O campo data de nascimento está em branco');</script>";
            $erro = true;
        } else {
            $data_nascimento = $_POST['data_nascimento'];
            ///         DATA NASCIMENTO         ///        
            $data_nascimentoDT = new DateTime($data_nascimento); // CONVERTE PARA DATA
            $data_atual = new DateTime();
            $intervalo = $data_nascimentoDT->diff($data_atual);
            $idade = $intervalo->y; // A IDADE SERÁ O INTERVALO EM ANOS, DO DIA DE HOJE PARA A DATA_NASCIMENTO
            if($intervalo->invert){
                // Se o invert == 1 -> FUTURO
                // SE O invert == 0 -> PASSADO
                $Verifica_idade = $intervalo->invert;
            }
              
            // Verificação da idade
            if (!empty($Verifica_idade) && $Verifica_idade == 1){            
                echo "<script>window.alert('Escolha uma data de nascimento que realmente exista');</script>";
                $erro = True;
            }
            if (empty($_POST['rbParentesco'])){
                echo "<script>window.alert('O campo parentesco está em branco');</script>";
            } else {
                if (($_POST['rbParentesco'] == 'Filho(a) - Menor') && $idade >= 18){
                    echo "<script>window.alert('Há uma incoerência em relação a idade do(a) menor.');</script>";
                    $erro = true;
                } 
                else if ($_POST['rbParentesco'] == 'Mae ou pai' && $idade < 37){
                    echo "<script>window.alert('Há uma incoerência em relação a idade da mãe ou pai.');</script>";
                    $erro = true;
                } else if ($_POST['rbParentesco'] == 'Parente_Pcd'){
                    if (empty($_POST['rbPCD']) || $_POST['rbPCD'] == "N"){
                        echo "<script>window.alert('Há uma incoerência em relação à PCD.');</script>";
                        $erro = true;
                    }
                } 
            }             
        }

        if (empty($_POST['rbPossuiBenf'])){
            echo "<script>window.alert('O campo Possui Benefício está em branco');</script>";
            $erro = true;
        } else if (!empty($_POST['rbPossuiBenf'])){
            $parentesco1 = $_POST['rbParentesco'];            
            if ($_POST['rbPossuiBenf'] == "S" && (empty($_POST['valor_benecicioDependente']) || empty($_POST['beneficioDependente']))){
                echo "<script>window.alert('Há uma incoerência em relação ao Benefício do Dependente');</script>";
                $erro = true;
            } else if ($_POST['rbParentesco'] == 'Filho_M' && $_POST['beneficioDependente'] == 'Aposentadoria'){
                echo "<script>window.alert('Há uma incoerência em relação ao Benefício do Dependente');</script>";
                $erro = true;
            }
        }
        if (!empty($_POST['rbPCD']) && $_POST['rbPCD'] == "S" && empty($_POST['nome_doenca'])){
            echo "<script>window.alert('Há uma incoerência em relação à PCD.');</script>";
            $erro = true;
        }

        if (!$erro){
            // LANCAR NO BANCO
            $idDependente = $_SESSION['idDependente'];
            $data_nascimento = $_POST['data_nascimento'];
            $parentesco = $_POST['rbParentesco'];
            $pcd = $_POST['rbPCD'];
            $laudo = $_POST['rbPossuiLaudo'];
            $rbPossuiBenf = $_POST['rbPossuiBenf'];
            $comorbidade = $_POST['nome_doenca'];
            $valorBeneficioGov = $_POST['valor_benecicioDependente']; // alterar Beneficio gov
            $valorBeneficioGov = floatval($valorBeneficioGov);
            $beneficioGov = $_POST['beneficioDependente']; // alterar nomeBeneficiosGov
            // ID BENEFICIOGOV 
            if ($rbPossuiBenf == "N"){                
                // EXCLUIR O BENEFÍCIO
                if (!empty($_SESSION['idBeneficioGov_dep'])){
                    $idBenf = $_SESSION['idBeneficioGov_dep'];
                    $stmt = $conexao->prepare("UPDATE filho_dependente SET idBeneficioGov = NULL WHERE idFilho_Dependente = ?");
                    $stmt->bind_param("i", $idDependente);
                    $result_idBenf = $stmt->execute();
                    $stmt->close();
                    if ($result_idBenf){
                        $stmt = $conexao->prepare("DELETE FROM BeneficioGov WHERE idBeneficioGov = ?");
                        $stmt->bind_param("i", $idBenf);
                        $result = $stmt->execute();                        
                        $stmt->close();
                        $idBeneficioGov = null;
                    } else {
                        echo "erro em alterar benefício Dependente";
                    }                    
                }
            } else {
                if (!empty($beneficioGov) && !empty($valorBeneficioGov)){
                    switch ($beneficioGov) {
                        case "Novo Bolsa Família": $id_nomesBeneficioGov = 1; break;
                        case "Benefício de Prestação Continuada (BPC)": $id_nomesBeneficioGov = 2; break;
                        case "Aposentadoria": $id_nomesBeneficioGov = 3; break;
                        case "Vale-Gas": $id_nomesBeneficioGov = 4; break;
                        case "Outros": $id_nomesBeneficioGov = 5; break;
                        default: $id_nomesBeneficioGov = NULL; break; 
                    }                       
                        if (empty($_SESSION['idBeneficioGov_dep'])){
                            // NÃO TINHA UM BENEFÍCIO
                            $stmt = $conexao->prepare("INSERT INTO BeneficioGov (idBeneficios_gov, valor_beneficio) VALUES (?,?)");
                            $stmt->bind_param("id", $id_nomesBeneficioGov, $valorBeneficioGov);
                            if ($stmt->execute()){
                                // TEM QUE SELECIONAR O ÚLTIMO BENEFICIO CADASTRADO
                                $idBeneficioGov = $conexao->insert_id;
                            }
                            $stmt->close();
                        } else {
                            // JÁ POSSUÍA UM BENEFÍCIO
                            $idBeneficioGov = $_SESSION['idBeneficioGov_dep'];
                            $stmt = $conexao->prepare("UPDATE BeneficioGov SET idBeneficios_gov = ?, valor_beneficio = ? WHERE idBeneficioGov = ?");
                            $stmt->bind_param("idi", $id_nomesBeneficioGov, $valorBeneficioGov, $idBeneficioGov);
                            $result = $stmt->execute();
                            $stmt->close();
                            if (!$result){
                                $erro = true;
                            }
                        }
                }  
            }
            
            // UPDATE EM filho_dependente            
            if (!empty($idBeneficioGov)){
                $sqlUpdate_filhoDep = "UPDATE filho_dependente SET data_nascimento_filho_dep = '$data_nascimento', parentesco = '$parentesco', PCD = '$pcd', laudo = '$laudo', doenca = '$comorbidade', idBeneficioGov = '$idBeneficioGov' WHERE idFilho_Dependente = '$idDependente';";                  
            } else {
                $sqlUpdate_filhoDep = "UPDATE filho_dependente SET data_nascimento_filho_dep = '$data_nascimento', parentesco = '$parentesco', PCD = '$pcd', laudo = '$laudo', doenca = '$comorbidade' WHERE idFilho_Dependente = '$idDependente';"; 
            }

            $resultUpdate_filhoDep = mysqli_query($conexao, $sqlUpdate_filhoDep);
            if ($resultUpdate_filhoDep){
                echo "<script>window.alert('Dependente alterado com sucesso!');</script>";
                unset($_SESSION['cpf_dependente']);
                unset($_SESSION['idBeneficioGov_dep']);                
                unset($_SESSION['idNome_beneficio_dep']);
                $data_nascimento = '';
                $parentesco = '';
                $pcd = '';
                $laudo = '';
                $comorbidade = '';
                $valorBeneficioGov = '';
                $beneficioGov = '';
                $idBeneficioGov = '';
                $id_nomesBeneficioGov = '';
                $alterado = True;
            } else {
                echo "<script>window.alert('Erro em alterar Dependente!');</script>";
                $erro = True;
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
    <title>Alteração Dependente</title>
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
                    <ion-icon name="person-add-outline" class="icones_nav" id="linksNavegacao"></ion-icon>
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
            <h3 style="color: var(--cor_letras);">Alteração Dependente</h3>
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
            <div class="d-flex justify-content-around w-100 input-group mb-4">                
                    <select class="form-select form-select-md w-50" name="cpfDependente">               
                        <option value="">CPF do(a) Dependente</option>
                        <!-- TEM QUE PEGAR ESSE CPF PARA FAZER O SELECT -->
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($dependente_data = mysqli_fetch_assoc($result)): ?>
                                <option value="<?= $dependente_data['cpf'] ?>"<?= (isset($cpf_dependente) && $cpf_dependente == $dependente_data['cpf']) ? 'selected' : '' ?>><?=$dependente_data['cpf']?></option>;
                            <?php endwhile; ?>
                        <?php endif ?>
                    </select>                        
                <button id="buttonPesquisarCPF" name="pesquisar" type="submit">
                    Pesquisar
                </button>
            </div>
            <!-- DADOS PESSOAIS -->
            <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
                Dados Pessoais
            </h3>
            <div class="d-flex flex-column container">
                <label class="form-label">Nome Completo:</label>
                <input type="text" id="nome" class="form-control border" name="nome_completo" disabled value="<?= !empty($nome_dependente) ? $nome_dependente : '' ?>">
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">          
                <span class="col-lg-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" value="<?= !empty($data_nascimento) ? $data_nascimento : '' ?>">
                </span>
                <span class="col-lg-4">
                    <label for="">Parentesco</label>
                    <select class="form-select form-select-md" name="rbParentesco">
                        <option value="" <?= !empty($parentesco) && $parentesco == "" ? 'selected' : '' ?>></option>
                        <option value="Filho_M" <?= !empty($parentesco) && $parentesco == "Filho_M" ? 'selected' : '' ?>>Filho(a) - Menor</option>
                        <option value="Parente_Pcd" <?= !empty($parentesco) && $parentesco == "Parente_Pcd" ? 'selected' : '' ?>>Parente - PCD</option>
                        <option value="Mae ou pai" <?= !empty($parentesco) && $parentesco == "Mae ou pai" ? 'selected' : '' ?>>Mãe ou pai</option>
                        <option value="Neto(a)" <?= !empty($parentesco) && $parentesco == "Neto(a)" ? 'selected' : '' ?>>Neto(a)</option>
                        <option value="Irmao(a)" <?= !empty($parentesco) && $parentesco == "Irmao(a)" ? 'selected' : '' ?>>Irmão(ã)</option>
                        <option value="Outro" <?= !empty($parentesco) && $parentesco == "Outro" ? 'selected' : '' ?>>Outro</option>
                    </select>
                </span>
            </div>
            <!-- SITUAÇÃO -->
            <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
                Situação
            </h3>
             <div class="d-flex justify-content-between formularios_Beneficiario  mt-3 container">
                <div class="d-flex flex-row col-lg-8 col-sm-12 container justify-content-between" id="form_beneficiario_beneficio">
                    <span class="col-4 container p-0">
                        <label for="">Possui Benefício?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S" <?= (!empty($beneficioGov)) ? 'checked' : '' ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N" <?= (isset($_POST['pesquisar']) && empty($beneficioGov)) ? 'checked' : '' ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                          <select name="beneficioDependente" class="form-select form-select-md">
                            <option value=""></option>
                            <option value="Aposentadoria" <?= (!empty($id_nomesBeneficioGov) && $id_nomesBeneficioGov == 3) ? 'selected' : '' ?>>Aposentadoria</option>
                            <option value="Benefício de Prestação Continuada (BPC)" <?= (!empty($id_nomesBeneficioGov) && $id_nomesBeneficioGov == 2) ? 'selected' : '' ?>>Benefício de Prestação Continuada (BPC)</option>
                            <option value="Novo Bolsa Família" <?= (!empty($id_nomesBeneficioGov) && $id_nomesBeneficioGov == 1) ? 'selected' : '' ?>>Bolsa Família</option>
                            <option value="Vale-gas" <?= (!empty($id_nomesBeneficioGov) && $id_nomesBeneficioGov == 4) ? 'selected' : '' ?>>Vale Gás</option>
                            <option value="Outros" <?= (!empty($id_nomesBeneficioGov) && $id_nomesBeneficioGov == 5) ? 'selected' : '' ?>>Outros</option>                            
                        </select>                
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" id="valor" class="form-control" name="valor_benecicioDependente" value="<?= !empty($valor_beneficioGov) ? $valor_beneficioGov : '' ?>">
                </span> 
            </div>
            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0">
                    <span class="col-6">
                        <label for="">PCD?</label>
                        <div class="d-flex container p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="S" <?= (!empty($pcd) && $pcd == "S") ? 'checked' : '' ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="N" <?= (!empty($pcd) && $pcd == "N") ? 'checked' : '' ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-6  justify-content-start">
                        <label for="">Possui Laudo Médico?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="S" <?= (!empty($laudo) && $laudo == "S") ? 'checked' : '' ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="N" <?= (!empty($laudo) && $laudo == "N") ? 'checked' : '' ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>                 
                </div>           
                <span class="col-lg-6">
                    <label for="">Nome da Comorbidade</label>
                    <input type="text" class="form-control" name="nome_doenca" id="comorbidade" value="<?= !empty($comorbidade) ? $comorbidade : '' ?>">
                </span> 
            </div> 
            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="cadastro.php" class="text-decoration-none">
                        <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                    </a>
                    <p>Voltar</p>
                </span>
                <span class="align-items-center text-center">
                    <ion-icon name="close-circle-outline" id="btCancelar"></ion-icon>
                    <p>Deletar</p>
                </span>
               <button type="submit" class="botoes_crud" name="alterar" value="1">
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
