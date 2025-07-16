<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

    // PRECISA VERIFICAR SE O USUÁRIO VEIO DO CADASTRO BENEFICIARIO
    if (isset($_GET['IDbeneficiario']) && isset($_GET['dependentes_pendentes'])){
        $idBeneficiarioPendente = $_GET['IDbeneficiario'];
        //$dependentes_pendentes = $_GET['dependentes_pendentes'];
        $sqlSelect_beneficiarioP = "SELECT cpf FROM tbBeneficiario WHERE ID = $idBeneficiarioPendente;";
        $result2 = $conexao->query($sqlSelect_beneficiarioP);    
    }

    // SE VIER DO CADASTRO BENEFICIARIO, PRECISA TRAVAR O CPF DO BENEFICIARIO E NÃO PODE SAIR DA TELA ATÉ CADASTRAR TODOS OS DEPENDENTES
    // SELECT BENEFICIÁRIO - PREENCHER O SELECT cpf BENEFICIÁRIO
    $sqlSelect_tbBeneficiario = "SELECT cpf FROM tbBeneficiario;";
    $result1 = $conexao->query($sqlSelect_tbBeneficiario); 
    
    $em_branco = False;
    $MAX_dep = False;
    $jaCadastrado = False;

    if (isset($_POST['cadastrar']) 
     && !empty($_POST['nome_completo']) 
     && !empty($_POST['cpfDependente']) 
     && !empty($_POST['data_nascimento']) 
     && !empty($_POST['rbParentesco'])     
     && !empty($_POST['rbPCD'])     
     && !empty($_POST['rbPossuiBenf'])
     ){
        if ($em_branco == False){        
            // SELECT ID_BENEFICIARIO
            if (!empty($_POST['cpfBeneficiario'])){
                $cpfBeneficiario = $_POST['cpfBeneficiario'];
                $sqlSelect_beneficiario = "SELECT * FROM tbBeneficiario WHERE cpf = $cpfBeneficiario";
                $result3 = $conexao->query($sqlSelect_beneficiario);
                    if (mysqli_num_rows($result3) > 0){
                        while($benefiario_data = mysqli_fetch_assoc($result3)){
                            $idBeneficiario = $benefiario_data['ID']; 
                            $qnts_dependentes = $benefiario_data['quantos_dependentes'];

                            // PRECISA VERIFICAR QUANTOS DEPENTES ESTÃO PENDENTES PARA CADASTRO
                            $sqlSelect_dependentes = "SELECT * FROM filho_dependente WHERE idBeneficiario = $idBeneficiario;";

                            $result5 = $conexao->query($sqlSelect_dependentes);
                            $qtd_dep_cadastrados = mysqli_num_rows($result5);

                            $qtd_dep_pendentes = $qnts_dependentes - $qtd_dep_cadastrados;
                            if ($qtd_dep_pendentes <= 0){
                                $MAX_dep = True;
                                $em_branco = True;                                

                            }
                        }
                    }
            
            } else {
                $em_branco = True;                
            }
            // PRECISA VERIFICAR SE JÁ POSSUI UM CPF IGUAL NA TABELA DEPENDENTE
            $cpf = $_POST['cpfDependente'];// filho_dependente
            $cpf = str_replace(['-', '.', ' '], '', $cpf);

            $sqlSelect_dependentes2 = "SELECT * FROM filho_dependente WHERE cpf = $cpf;";
            $result6 = $conexao->query($sqlSelect_dependentes2);
            // VERIFICA SE JÁ POSSUI O DEPENDENTE CADASTRADO
            if (mysqli_num_rows($result6) > 1){
                $jaCadastrado = True;
                $em_branco = True;                
            }


            $nome = $_POST['nome_completo']; // filho_dependente
            $data_nascimento = $_POST['data_nascimento']; // filho_dependente
            $parentesco = $_POST['rbParentesco']; // filho_dependente
            $pcd = $_POST['rbPCD']; // filho_dependente
            $possuiBenf = $_POST['rbPossuiBenf']; // filho_dependente

            if (!empty($_POST['rbPossuiLaudo']))      {
                $rbPossuiLaudo = $_POST['rbPossuiLaudo']; // filho_dependente
            } else {
                $rbPossuiLaudo = "N";
            }

            if ($possuiBenf == "S"){ // INSERIR NA TABELA beneficio_gov
                if (!empty($_POST['beneficioBeneficiario']) && !empty($_POST['valor_benecicioBeneficiario'])){
                    $beneficio = $_POST['beneficioBeneficiario']; 
                    $valor_beneficio = $_POST['valor_benecicioBeneficiario'];
                } else {
                    $em_branco = True;                    
                }
            }
            
            if ($pcd== "S"){
                if (!empty($rbPossuiLaudo) && !empty($_POST['nome_doencaBeneficiario'])){                                 
                    $nome_doenca = $_POST['nome_doencaBeneficiario'];                                    
                } else {
                    $em_branco = True;                    
                }
            } else {
                $rbPossuiLaudo = "N";
                $nome_doenca = "-";
            }                        
            
            
            if ($em_branco == False){
                $sqlInsert_Dependente = "INSERT INTO filho_dependente (nome_filho_dependente, cpf, data_nascimento_filho_dep, parentesco, PCD, laudo, doenca, idBeneficiario) VALUES ('$nome', '$cpf', '$data_nascimento', '$parentesco', '$pcd', '$rbPossuiLaudo', '$nome_doenca', '$idBeneficiario');";

                $result4 = $conexao->query($sqlInsert_Dependente);                                 
            }
        }

        $nome = NULL;
        $cpf = NULL;
        $data_nascimento = NULL;
        $parentesco = NULL;
        $pcd = NULL;
        $rbPossuiLaudo = NULL;
        $nome_doenca = NULL;
        $idBeneficiario = NULL;
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
                <script>
                    window.alert("A quantidade de dependentes relacionados ao beneficiário excedeu.");
                </script>
            <?php elseif($jaCadastrado): ?>
                <script>
                    window.alert("Dependente já cadastrado.");
                </script>
            <?php endif; ?>
            <div class="d-flex justify-content-around w-100 input-group mb-4">
                <?php if (!empty($idBeneficiarioPendente)): ?>
                    <select class="form-select form-select-md w-50" disabled name="cpfBeneficiario">;
                <?php else: ?>
                    <select class="form-select form-select-md w-50" name="cpfBeneficiario">
                <?php endif; ?>
                        <option value="">CPF do(a) Beneficiário(a)</option>
                        <?php if (!empty($idBeneficiarioPendente)): ?>
                            <?php if (mysqli_num_rows($result2) > 0): ?>
                                <?php while ($beneficiario_data = mysqli_fetch_assoc($result2)): ?>                                    
                                    <option value="<?= $beneficiario_data['cpf'] ?>" selected><?= $beneficiario_data['cpf'] ?></option>                                    
                                <?php endwhile; ?>                                
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ((mysqli_num_rows($result1) > 0)): ?>
                                <?php while ($beneficiario_data = mysqli_fetch_assoc($result1)): ?>
                                    <option value="<?= $beneficiario_data['cpf'] ?>"><?= $beneficiario_data['cpf'] ?></option>
                                <?php endwhile; ?>  
                            <?php endif; ?>                                                              
                        <?php endif; ?>
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
                <input type="text" required class="form-control border" name="nome_completo">
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-3">
                    <label for="">CPF</label>
                    <input type="text" maxlength="14" minlength="14" id="cpf" required class="form-control" name="cpfDependente" id="cpf">
                </span>
                <span class="col-lg-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" required>
                </span>
                <span class="col-lg-4">
                    <label for="">Parentesco</label>
                    <select class="form-select form-select-md" name="rbParentesco" required>
                        <option value=""></option>
                        <option value="Filho_M">Filho(a) - Menor</option>
                        <option value="Filho_Pcd">Filho(a) - PCD</option>
                        <option value="Mae ou pai">Mãe ou pai</option>
                        <option value="Neto(a)">Neto(a)</option>
                        <option value="Irmao(a)">Irmão(ã)</option>
                        <option value="Outro">Outro</option>
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
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S">
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N">
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                         <input type="text" class="form-control" name="beneficioDependente"> <!-- PODIA SER UM COMBOBOX -->
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" class="form-control" name="valor_benecicioDependente">
                </span> 
            </div>
            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0">
                    <span class="col-6">
                        <label for="">PCD?</label>
                        <div class="d-flex container p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="S">
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="N">
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-6  justify-content-start">
                        <label for="">Possui Laudo Médico?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="S">
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="N">
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>                 
                </div>           
                <span class="col-lg-6">
                    <label for="">Nome da Comorbidade</label>
                    <input type="text" class="form-control" name="nome_doenca">
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
                    <p>Cancelar</p>
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
