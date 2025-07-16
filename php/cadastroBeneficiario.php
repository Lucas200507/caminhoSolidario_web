<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");   

    $cadastrado = False;
    $ja_cadastrado = False;
    $em_branco = False;
    $possuiDependentes = False;

     if (isset($_POST['cadastrar']) 
     && !empty($_POST['nome_completoBeneficiario']) 
     && !empty($_POST['cpfBeneficiario']) 
     && !empty($_POST['data_nascimentoBeneficiario']) 
     && !empty($_POST['estado_civilBeneficiario']) 
     && !empty($_POST['telefoneBeneficiario']) 
     && !empty($_POST['emailBeneficiario']) 
     && !empty($_POST['endereco_completoBeneficiario'])
     && !empty($_POST['cepBeneficiario']) 
     && !empty($_POST['cidadeBeneficiario']) 
     && !empty($_POST['estadoBeneficiario'])
     && !empty($_POST['situacao_moradiaBeneficiario'])
     && !empty($_POST['valor_despesasBeneficiario'])  // SO SE FOR ALUGADA
     && !empty($_POST['rbPossuiBenf'])     
     && !empty($_POST['rbPCD'])     
     && !empty($_POST['rbPossuiDependentes'])       
     && !empty($_POST['renda_familiarBeneficiario'])){

        // PRECISA VERIFICAR SE JÁ POSSUI O CPF CADASTRADO
        $cpfBeneficiario = $_POST['cpfBeneficiario']; // PESSOA
        $cpfBeneficiario = str_replace(['-', '.', ' '], '', $cpfBeneficiario); // FORMATANDO
        
        $sqlSelect_Pessoa1 = "SELECT idPessoa FROM pessoa WHERE cpf = '$cpfBeneficiario';";
        $result = $conexao->query($sqlSelect_Pessoa1);
            if (mysqli_num_rows($result) > 0){
                while ($pessoa_data1 = mysqli_fetch_assoc($result)){
                    $idPessoa1 = $pessoa_data1['idPessoa'];
                }
                $sqlSelect_beneficiario1 = "SELECT * FROM Beneficiario WHERE idPessoa = '$idPessoa1';";
                $result9 = $conexao->query($sqlSelect_beneficiario1);
                if (mysqli_num_rows($result9) > 0){
                    // Já possui esse cpf cadastrado
                    $ja_cadastrado = True;
                    $em_branco = True;
                    $cpfBeneficiario = NULL;                
                }
            }


        if (!$em_branco){
            $nome_completoBeneficiario = $_POST['nome_completoBeneficiario']; // PESSOA
            $data_nascimentoBeneficiario = $_POST['data_nascimentoBeneficiario']; // BENEFICIARIO
            $estado_civilBeneficiario = $_POST['estado_civilBeneficiario']; // BENEFICIARIO
            $telefoneBeneficiario = $_POST['telefoneBeneficiario']; // PESSOA
            $telefoneBeneficiario = str_replace(['(', ')', ' '], '', $telefoneBeneficiario); // FORMATANDO
            $emailBeneficiario = $_POST['emailBeneficiario']; // PESSOA
            $endereco_completoBeneficiario = $_POST['endereco_completoBeneficiario']; // ENDERECO
            $cep = $_POST['cepBeneficiario']; // Endereco
            $cep = str_replace(['-', '.', ' '], '', $cep); //FORMATANDO
            $cidadeBeneficiario = $_POST['cidadeBeneficiario']; // ENDERECO
            $estadoBeneficiario = $_POST['estadoBeneficiario']; // ENDERECO 
            $situacao_moradiaBeneficiario = $_POST['situacao_moradiaBeneficiario']; // ENDERECO                     
            $valor_despesasBeneficiario = $_POST['valor_despesasBeneficiario']; // ENDERECO
            $renda_familiarBeneficiario = $_POST['renda_familiarBeneficiario']; // BENEFICIARIO
            $rbPossuiBenf = $_POST['rbPossuiBenf']; 
            $rbPCD = $_POST['rbPCD']; // beneficiario
            $rbPossuiDependentes = $_POST['rbPossuiDependentes'];  
            if (!empty($_POST['rbPossuiLaudo']))      {
                $rbPossuiLaudo = $_POST['rbPossuiLaudo']; // Beneficiario
            } else {
                $rbPossuiLaudo = "N";
            }
            

            if ($rbPossuiBenf == "S"){ // INSERIR NA TABELA beneficio_gov
                if (!empty($_POST['beneficioBeneficiario']) && !empty($_POST['valor_benecicioBeneficiario'])){
                    $beneficio = $_POST['beneficioBeneficiario']; 
                    $valor_beneficio = $_POST['valor_benecicioBeneficiario'];
                } else {
                    $em_branco = True;                    
                }
            } 

            // TEM QUE SALVAR LOCALMENTE A QUANTIDA PARA INSERIR A QUANTIDADE CERTA DE DEPENDENTES
            if ($rbPossuiDependentes == "S"){            
                if (!empty($_POST['quantos_dependentes'])){
                    $quantos_dependentes = $_POST['quantos_dependentes']; // INSERIR NA TABELA beneficiario
                } else {
                    $em_branco = True;                    
                }
            } else {
                $quantos_dependentes = 0;
                $dependentes_pendentes = 0;
            }
            // beneficiario
            if ($rbPCD == "S"){
                if (!empty($rbPossuiLaudo) && !empty($_POST['nome_doencaBeneficiario'])){                                 
                    $nome_doenca = $_POST['nome_doencaBeneficiario'];                                    
                } else {
                    $em_branco = True;                    
                }
            } else {
                $rbPossuiLaudo = "N";
                $nome_doenca = "-";
            }

            // INSERTS
            if ($em_branco == False){
                // INSERT PESSOA
                $sqlInsert_PESSOA = "INSERT INTO pessoa (nome_completo, cpf, telefone) VALUES ('$nome_completoBeneficiario', '$cpfBeneficiario', '$telefoneBeneficiario')";
        
                $result1 = $conexao->query($sqlInsert_PESSOA);

                // ERROS:
                if(!$result1){
                    die("Erro ao inserir Pessoa: ". mysqli_error($conexao));
                }
        
                // FAZER UM SELECT DO ID_PESSOA
                $sqlSelect_PESSOA = "SELECT idPessoa FROM pessoa WHERE cpf = $cpfBeneficiario;";
        
                $result2 = $conexao->query($sqlSelect_PESSOA);

                
                // ERROS:
                if(!$result2){
                    die("Erro ao Selecionar Pessoa: ". mysqli_error($conexao));
                }
        

                if (mysqli_num_rows($result2) > 0){
                    while($pessoa_data = mysqli_fetch_assoc($result2)){
                        $idPessoa = $pessoa_data['idPessoa'];
                    }
                }
        
                // INSERT ENDERECO
                $sqlInsert_Endereco = "INSERT INTO endereco (endereco, cidade, estado, cep, situacao_moradia, valor_despesas, idPessoa) VALUES('$endereco_completoBeneficiario', '$cidadeBeneficiario', '$estadoBeneficiario', '$cep', '$situacao_moradiaBeneficiario', '$valor_despesasBeneficiario', '$idPessoa')";

                $result3 = $conexao->query($sqlInsert_Endereco);

                
                // ERROS:
                if(!$result3){
                    die("Erro ao inserir Endereco: ". mysqli_error($conexao));
                }
        

                // SELECT ID ENDERECO
                $sqlSelect_ENDERECO = "SELECT idEndereco FROM endereco WHERE cep = $cep;";

                $result4 = $conexao->query($sqlSelect_ENDERECO);

                // ERROS:
                if(!$result4){
                    die("Erro ao Selecionar Endereco: ". mysqli_error($conexao));
                }
        

                if (mysqli_num_rows($result4) > 0){
                    while($endereco_data = mysqli_fetch_assoc($result4)){
                        $idEndereco = $endereco_data['idEndereco'];
                    }
                }

                // INSERT BENEFICIOGOV
                if (!empty($beneficio) && !empty($valor_beneficio)){
                    $sqlSelect_nomeBeneficioGov = "SELECT * FROM nomeBeneficiosGov WHERE nome_beneficiogov = $beneficio;";
                    $result10 = $conexao->query($sqlSelect_nomeBeneficioGov);

                    if(!$result10){
                        die("Erro ao Selecionar nomeBeneficioGov: ". mysqli_error($conexao));
                    }

                    
                    if (mysqli_num_rows($result10) > 0){
                        while($Nomebeneficio_data = mysqli_fetch_assoc($result10)){
                            $idBeneficiosGov = $Nomebeneficio_data['idBeneficios_gov'];
                    }

                    $sqlInsert_beneficioGov = "INSERT INTO BeneficioGov (idBeneficios_gov, valor_beneficio) VALUES ('$idBeneficiosGov', '$valor_beneficio')";
                    $result5 = $conexao->query($sqlInsert_beneficioGov);
                    
                    // ERROS:
                    if(!$result5){
                        die("Erro ao inserir BeneficioGov: ". mysqli_error($conexao));
                    }
            
                    // SELECT ID BENEFICIOGOV
                    $sqlSelect_beneficioGov = "SELECT idBeneficioGov FROM BeneficioGov WHERE valor_beneficio = '$valor_beneficio' AND nome_beneficio_gov = '$beneficio';";
                    $result6 = $conexao->query($sqlSelect_beneficioGov);

                    
                    // ERROS:
                    if(!$result6){
                        die("Erro ao Selecionar BeneficioGov: ". mysqli_error($conexao));
                    }
        

                    if (mysqli_num_rows($result6) > 0){
                        while($beneficio_data = mysqli_fetch_assoc($result6)){
                            $idBeneficio = $beneficio_data['idBeneficioGov'];
                        }

                            // INSERT BENEFICIARIO
                        $sqlInsert_Beneficiario = "INSERT INTO Beneficiario (data_nascimento, estado_civil, PCD, laudo, doenca, quantos_dependentes, renda_familiar, idPessoa, idEndereco, idBeneficioGov) VALUES ('$data_nascimentoBeneficiario', '$estado_civilBeneficiario', '$rbPCD', '$rbPossuiLaudo', '$nome_doenca', '$quantos_dependentes', '$renda_familiarBeneficiario', '$idPessoa', '$idEndereco', '$idBeneficio');";
            
                        $result7 = $conexao->query($sqlInsert_Beneficiario);                        
                        
                        // ERROS:
                        if(!$result7){
                            die("Erro ao inserir Beneficiario c/ BeneficioGov: ". mysqli_error($conexao));
                        } else {
                            $cadastrado = True;
                        }
        
                    }

                } else {
                    // INSERT BENEFICIARIO SEM BENEFICIOGOV
                    $sqlInsert_Beneficiario = "INSERT INTO Beneficiario (data_nascimento, estado_civil, PCD, laudo, doenca, quantos_dependentes, renda_familiar, idPessoa, idEndereco) VALUES ('$data_nascimentoBeneficiario', '$estado_civilBeneficiario', '$rbPCD', '$rbPossuiLaudo', '$nome_doenca', '$quantos_dependentes', '$renda_familiarBeneficiario', '$idPessoa', '$idEndereco');";
        
                    $result7 = $conexao->query($sqlInsert_Beneficiario);

                    // ERROS:
                    if(!$result7){
                        die("Erro ao inserir Beneficiario s/ BeneficioGov: ". mysqli_error($conexao));
                    } else {
                            $cadastrado = True;
                    }
                }
                // VERIFICA SE O USUÁRIO POSSUI DEPENDENTES
                $sqlSelect_beneficiario = "SELECT * FROM Beneficiario;";
                $result8 = $conexao->query($sqlSelect_beneficiario);

                // ERROS:
                    if(!$result8){
                        die("Erro ao Selecionar Beneficiario: ". mysqli_error($conexao));
                    }

                if (mysqli_num_rows($result8) > 0){
                    while($beneficiario_data = mysqli_fetch_assoc($result8)){
                        $idBeneficiario = $beneficiario_data['idBeneficiario'];
                        $dependentes_pendentes = $beneficiario_data['quantos_dependentes'];
                    }
                }

                if ($dependentes_pendentes > 0){
                    header("Location: ../php/cadastroDependente.php?IDbeneficiario=$idBeneficiario&dependentes_pendentes=$dependentes_pendentes");
                }
                        
            }
            
        }
        // DEPOIS DO INSERT TEM QUE APAGAR TODOS OS DADOS
        $data_nascimentoBeneficiario = NULL;        
        $estado_civilBeneficiario = NULL; 
        $rbPCD = NULL; 
        $rbPossuiLaudo = NULL; 
        $nome_doenca = NULL; 
        $quantos_dependentes = NULL; 
        $renda_familiarBeneficiario = NULL; 
        $idPessoa = NULL; 
        $idEndereco = NULL;
        $idBeneficio = NULL;

        
    } else {
        $em_branco = True;
    }
    
    if($em_branco && isset(($_POST['cadastrar']))){
         echo '<script>
            window.alert("Deu erro");
        </script>';
    } else if ($cadastrado){
        echo'<script>
            window.alert("Cadastrado com sucesso");
        </script>';
    }

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
    <?php if($ja_cadastrado): ?>
        <script>
            window.alert("Beneficiário já cadastrado no sistema");
        </script>
    <?php endif; ?>
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
                    <ion-icon name="person-add-outline" class="icones_nav"
                        id="linksNavegacaoSelecionado"></ion-icon>
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
            <h3 style="color: var(--cor_letras);">Cadastro Beneficiário</h3>
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
        <!-- <?php if($em_branco): ?>        
            <div id="mensagem_erro2" class="container_mensagem_erro" style="margin-top: 2em; margin-button: 2em; margin-left:auto; margin-right: auto;">
                Campos obrigatórios estão em branco. Ou em alguns casos, possuem a quantidade de caracteres abaixo do mínimo.
            </div>
        <?php endif; ?>           -->
        <form action="" class="container_formularios" method="post">  
            <!-- DADOS PESSOAIS -->
            <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
                Dados Pessoais
            </h3>
            <div class="d-flex flex-column container">
                <label class="form-label">Nome Completo:</label>
                <input type="text" required class="form-control border" name="nome_completoBeneficiario">            
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-xl-3">
                    <label for="">CPF</label>
                    <input type="text" maxlength="14" minlength="14" id="cpf" required class="form-control" name="cpfBeneficiario">
                </span>
                <span class="col-xl-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" required class="form-control" name="data_nascimentoBeneficiario">
                </span>
                <span class="col-xl-4">
                    <label for="">Estado Civil</label>
                    <select class="form-select form-select-md" name="estado_civilBeneficiario" required>
                        <option value=""></option>
                        <option value="S">Solteiro</option>
                        <option value="C">Casado</option>
                        <option value="V">Viúvo</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5 col-xs-12">
                    <label>Telefone:</label>
                    <input type="text" maxlength="15" minlength="15" id="telefone" required class="form-control" name="telefoneBeneficiario">
                </span>
                <span class="col-lg-6 col-xs-12">
                    <label>Email:</label>
                    <input type="email" required class="form-control form-control-xl" name="emailBeneficiario">
                </span>
            </div>
                <!-- ENDEREÇO -->
            <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
                    Endereço        
            </h3>
            <div class="d-flex justify-content-between container formularios_Beneficiario">
                <span class="col-lg-7 col-xs-12">
                    <label class="form-label">Endereço Completo:</label>
                    <input type="text" required class="form-control border" name="endereco_completoBeneficiario">            
                </span>
                <span class="col-lg-4 col-xs-12">
                    <label>CEP:</label>
                    <input type="text" id="cep" maxlength="9" minlength="9" required class="form-control form-control-xl" name="cepBeneficiario">
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-3">
                    <label for="">Cidade</label>
                    <input type="text" required class="form-control" name="cidadeBeneficiario">
                </span>
                <span class="col-lg-3">
                    <label for="">Estado</label>
                    <select class="form-select form-select-md" name="estadoBeneficiario" required>
                        <option value=""></option>
                        <option value="AC">AC - Acre</option>
                        <option value="AL">AL - Alagoas</option>
                        <option value="AP">AP - Amapá</option>
                        <option value="AM">AM - Amazonas</option>
                        <option value="BA">BA - Bahia</option>
                        <option value="CE">CE - Ceará</option>
                        <option value="DF">DF - Distrito Federal</option>
                        <option value="ES">ES - Espírito Santo</option>
                        <option value="GO">GO - Goiás</option>
                        <option value="MA">MA - Maranhão</option>
                        <option value="MT">MT - Mato Grosso</option>
                        <option value="MS">MS - Mato Grosso do Sul</option>
                        <option value="MG">MG - Minas Gerais</option>
                        <option value="PA">PA - Pará</option>
                        <option value="PB">PB - Paraíba</option>
                        <option value="PR">PR - Paraná</option>
                        <option value="PE">PE - Pernambuco</option>
                        <option value="PI">PI - Piauí</option>
                        <option value="RJ">RJ - Rio de Janeiro</option>
                        <option value="RN">RN - Rio Grande do Norte</option>
                        <option value="RS">RS - Rio Grande do Sul</option>
                        <option value="RO">RO - Rondônia</option>
                        <option value="RR">RR - Roraima</option>
                        <option value="SC">SC - Santa Catarina</option>
                        <option value="SE">SE - Sergipe</option>
                        <option value="TO">TO - Tocantis</option>                    
                    </select>
                </span>
                <span class="col-lg-4">
                    <label for="">Situação da moradia</label>
                    <select class="form-select form-select-md" name="situacao_moradiaBeneficiario" required>
                        <option value=""></option>
                        <option value="C">Comprada</option>
                        <option value="A">Alugada</option>
                        <option value="O">Outro</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-12">
                    <label>Valor do Aluguel + Despesas (Água e luz):</label>
                    <input type="number" class="form-control" name="valor_despesasBeneficiario">
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
                        <select name="beneficioBeneficiario" class="form-select form-select-md">
                            <option value=""></option>
                            <option value="Auxílio Reconstrução">Auxílio Reconstrução</option>
                            <option value="Novo Bolsa Família">Novo Bolsa Família</option>
                            <option value="Benefício de Prestação Continuada (BPC)">Benefício de Prestação Continuada (BPC)</option>
                            <option value="Garantia-Safra">Garantia-Safra</option>
                            <option value="Seguro-Defeso">Seguro-Defeso</option>
                        </select>                   
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" class="form-control" name="valor_benecicioBeneficiario">
                </span> 
            </div>
            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0" id="form_beneficiario_beneficio">
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
                    <input type="text" class="form-control" name="nome_doencaBeneficiario">
                </span> 
            </div> 
              <div class="d-flex flex-row justify-content-between container formularios_Beneficiario mt-3">        
                <span class="col-4">
                    <label for="">Possui Dependentes?</label>                    
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="S" onclick="enviarValorPDependente('sim')">
                            <label for="depSim">Sim</label>                        
                        </div>                         
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="N">
                            <label for="depNao">Não</label>
                        </div>               
                    </div>
                </span> 
                <span class="col-lg-7  col-sm-6">
                    <label for="">Quantos?</label>
                    <input type="number" class="form-control" name="quantos_dependentes">
                </span> 
            </div>                                    
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5  col-xs-12">
                    <label>Quantos trabalham:</label>
                    <input type="number" class="form-control" name="quantos_trabalhamBeneficiario">
                </span>
                <span class="col-lg-6  col-xs-12">
                    <label>Renda Familiar total:</label>
                    <input type="number" required class="form-control" name="renda_familiarBeneficiario">
                </span>
            </div>            
            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="cadastro.php" class="text-decoration-none">
                        <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
                    </a>
                    <p>Voltar</p>
                </span>
                <?php if($funcao === "Administrador"): ?>
                    <button type="submit" class="botoes_crud" name="deletar" value="2">
                        <span class="align-items-center text-center" onclick="cancelarCadastro()">
                            <ion-icon name="close-circle-outline" id="btCancelar"></ion-icon>
                            <p>Deletar</p>
                        </span>                        
                    </button>
                <?php endif?>
                <button type="submit" class="botoes_crud" name="cadastrar" value="1">
                    <span class="align-items-center text-center">
                        <ion-icon name="cloud-done-outline" id="btSalvar"></ion-icon>
                        <p>Salvar</p>
                    </span>
                </button>              
            </div>       
        </form>
    </main>

    <!--        MÁSCARAS         -->
    <script src="../js/mascaras.js"></script>
    <!--        BOOTSTRAP        -->
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