<?php
// DEVE MUDAR O COMBO_BOX do cpf para um input, para o usuário pesquisar o cpf e filtrar

//session_start();
include_once('../conexao_banco.php');
include_once('../routes/verificacao_logado.php');
include_once("../routes/dados_usuarioLogado.php");

// Função para buscar endereço pelo CEP
function get_endereco($cep){
    $url = "http://viacep.com.br/ws/$cep/xml/";
    return simplexml_load_file($url);
}

// PARA EVITAR MÚLTIPLAS ALTERAÇÕES
if(!isset($_SESSION['beneficiario_alterado'])){
    $_SESSION['beneficiario_alterado'] = False;
}

// Flags de erro
$erro = False;
$idade = null;
// Consulta CPF
$sql = "SELECT cpf FROM tbBeneficiario;";
$result = mysqli_query($conexao, $sql);

// PESQUISA
if(isset($_POST['pesquisar']) && !empty($_POST['cpfBeneficiario'])){
    $_SESSION['beneficiario_alterado'] = False;
    $cpfBeneficiario = $_POST['cpfBeneficiario'];
    $_SESSION['cpf'] = $cpfBeneficiario;
    $sql = "SELECT ID FROM tbBeneficiario WHERE cpf = '$cpfBeneficiario';";
    $result0 = mysqli_query($conexao, $sql);

    if(mysqli_num_rows($result0) > 0){
        while ($dados_tbBeneficiario = mysqli_fetch_assoc($result0)){
            $idBeneficiario = $dados_tbBeneficiario['ID'];
            $_SESSION['idBeneficiario'] = $idBeneficiario;                
        }
        
        $sql2 = "SELECT * FROM Beneficiario WHERE idBeneficiario = '$idBeneficiario';";
        $result2 = mysqli_query($conexao, $sql2);

        if(mysqli_num_rows($result2) > 0){
            $dadosBeneficiario = mysqli_fetch_assoc($result2);

            $data_nascimento = $dadosBeneficiario['data_nascimento'];
            $email = $dadosBeneficiario['email'];
            $estado_civil = $dadosBeneficiario['estado_civil'];
            $pcd = $dadosBeneficiario['PCD'];
            $laudo = $dadosBeneficiario['laudo'];
            $comorbidade = $dadosBeneficiario['doenca'];
            $quantos_dependentes = $dadosBeneficiario['quantos_dependentes'];
            $quantos_trabalham = $dadosBeneficiario['qnt_trabalham'];
            $renda_familiar = $dadosBeneficiario['renda_familiar'];
            $idPessoa = $dadosBeneficiario['idPessoa'];
            $_SESSION['idPessoa'] = $idPessoa;
            $idEndereco = $dadosBeneficiario['idEndereco'];
            $_SESSION['idEndereco'] = $idEndereco;
            $idBeneficioGov = $dadosBeneficiario['idBeneficioGov']; 
            $_SESSION['idbeneficioGov'] = $idBeneficioGov;

            $sql3 = "SELECT * FROM pessoa WHERE idPessoa = '$idPessoa';";
            $result3 = mysqli_query($conexao, $sql3);
            if ($dados_pessoa = mysqli_fetch_assoc($result3)) {
                $nome_Beneficiario = $dados_pessoa['nome_completo'];
                $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $dados_pessoa['telefone']);
            }

            $sql4 = "SELECT * FROM endereco WHERE idEndereco = '$idEndereco';";
            $result4 = mysqli_query($conexao, $sql4);
            if ($dados_endereco = mysqli_fetch_assoc($result4)) {
                $endereco = $dados_endereco['endereco'];
                $cidade = $dados_endereco['cidade'];
                $estado = $dados_endereco['estado'];
                $situacao_moradia = $dados_endereco['situacao_moradia'];
                $valor_despesas = $dados_endereco['valor_despesas'];
                $cep = substr($dados_endereco['cep'], 0, 5) . "-" . substr($dados_endereco['cep'], 5);
            }

            $sql5 = "SELECT * FROM tbBeneficioGov WHERE ID = '$idBeneficioGov';";            
            $result5 = mysqli_query($conexao, $sql5);
            if ($dados_beneficioGov = mysqli_fetch_assoc($result5)) {
                $BeneficioGov = $dados_beneficioGov['Beneficio_gov'];                
                $valor_beneficio = $dados_beneficioGov['valor_beneficio'];
            }
        }
    }
}

//if (isset($_POST['ver'])){}

// ALTERAÇÃO
if (isset($_POST['alterar']) && $_SESSION['beneficiario_alterado'] === False && !$erro) {
    if (!empty($_POST['data_nascimentoBeneficiario']) 
        && !empty($_POST['estado_civilBeneficiario']) 
        && !empty($_POST['telefoneBeneficiario']) 
        && !empty($_POST['endereco_completoBeneficiario']) 
        && !empty($_POST['cepBeneficiario']) 
        && !empty($_POST['cidadeBeneficiario']) 
        && !empty($_POST['estadoBeneficiario']) 
        && !empty($_POST['situacao_moradiaBeneficiario']) 
        && !empty($_POST['valor_despesasBeneficiario']) 
        && !empty($_POST['renda_familiarBeneficiario'])
        && !empty($_POST['quantos_trabalhamBeneficiario'])
    ) {

        // $telefone = preg_replace('/[^0-9]/', '', $_POST['telefoneBeneficiario']);
        $telefone = str_replace(['(', ')', ' '], '', $_POST['telefoneBeneficiario']);        
        $cep = preg_replace('/[^0-9]/', '', $_POST['cepBeneficiario']);

        if (strlen($telefone) < 11 || strlen($cep) < 8) {
            echo "<script>alert('Telefone ou CEP possuem caracteres insuficientes');</script>";
            $erro = true;
        }
        $data_nascimento = $_POST['data_nascimentoBeneficiario'];        
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
              
         if (!empty($Verifica_idade) && $Verifica_idade == 1){            
            echo "<script>window.alert('Escolha uma data de nascimento que realmente exista');</script>";
            $erro = True;        
        } else if ($idade < 20){
            echo "<script>window.alert('A idade mínima para se cadastrar como Beneficiário(a) é de 20 anos.');</script>";
            $erro = True;
        }
        
        $estado_civil = $_POST['estado_civilBeneficiario'];
        $endereco = $_POST['endereco_completoBeneficiario'];
        $cidade = $_POST['cidadeBeneficiario'];
        $estado = $_POST['estadoBeneficiario'];
        $situacao_moradia = $_POST['situacao_moradiaBeneficiario'];
        $beneficioGov = $_POST['beneficioBeneficiario'];
        $valor_beneficio = $_POST['valor_benecicioBeneficiario'];
        $valor_despesas = $_POST['valor_despesasBeneficiario'];
        $comorbidade = $_POST['doenca'];
        $renda_familiar = $_POST['renda_familiarBeneficiario'];
        $quantos_dependentes = $_POST['quantos_dependentes'];
        $quantos_trabalham = $_POST['quantos_trabalhamBeneficiario'];
        $email = $_POST['emailBeneficiario'] ?? $email; // ou outro campo do formulário
        $pcd = $_POST['rbPCD'] ?? 'N';
        $laudo = $_POST['rbPossuiLaudo'] ?? 'N';

        $id = $_SESSION['idBeneficiario'];

        $sqlSelect_Dependente = "SELECT COUNT(*) AS total FROM filho_dependente WHERE idBeneficiario = '$id';";
        $resDep = mysqli_query($conexao, $sqlSelect_Dependente);
        $total_dependentes = mysqli_fetch_assoc($resDep)['total']; // QUANTIDADE DE DEPENDENTES CADASTRADOS PELO BENEFICIARIO
        

        if ($total_dependentes > $quantos_dependentes) {
            echo "<script>window.alert('Quantidade de dependentes já cadastrados é maior que a informada');</script>";   
            $erro = true;         
        }

        // Validações de coerência
        if ((($_POST['rbPossuiBenf'] == 'S' && (empty($beneficioGov)) || (empty($valor_beneficio))) || (!empty($beneficioGov) && $beneficioGov == 'Aposentadoria' && $idade < 60))) {
            echo "<script>window.alert('Há uma incoerência em relação ao Benefício do Beneficiário');</script>";
            $erro = true;
        } 

        //if ($_POST['rbPCD'] == 'S' && (empty($comorbidade) || $_POST['rbPossuiLaudo'] == 'N')) {
        if ($_POST['rbPCD'] == 'S' && (empty($comorbidade))) {
            echo "<script>alert('Há uma incoerência em relação à PCD');</script>";
            $erro = true;
        } else if ($_POST['rbPCD'] == 'N'){
            $comorbidade = '';
        }

        if ($_POST['rbPossuiDependentes'] == 'S' && empty($quantos_dependentes)) {
            echo "<script>alert('Há uma incoerência em relação aos Dependentes');</script>";
            $erro = true;
        } else if ($_POST['rbPossuiDependentes'] == 'N' && $total_dependentes > 0){
            echo "<script>alert('Já possui Dependentes cadastrados em relação ao Beneficiário');</script>";
            $erro = true;
        }

        if (!$erro) {            
            $idPessoa = $_SESSION['idPessoa'];
            $idEndereco = $_SESSION['idEndereco']; // já definido            

            $sqlUpdate_endereco = "UPDATE endereco SET endereco = '$endereco', cidade = '$cidade', estado = '$estado', cep = '$cep', situacao_moradia = '$situacao_moradia', valor_despesas = '$valor_despesas' WHERE idEndereco = '$idEndereco';";

            // VERIFICAR SE JÁ POSSUI ESTE TELEFONE CADASTRADO            
            $sqlTelefone = "SELECT * FROM pessoa WHERE telefone = '$telefone';";
            $resultTelefone = mysqli_query($conexao, $sqlTelefone);
                if (mysqli_num_rows($resultTelefone) > 0){
                    
                    while ($dados_telefone = mysqli_fetch_assoc($resultTelefone)){                        
                        $id_telefone = $dados_telefone['idPessoa'];
                        if (!empty($id_telefone) && $id_telefone != $idPessoa){
                            echo "<script>window.alert('O telefone informado já está cadastrado por outra pessoa');</script>";
                            $erro = true;
                        }
                    }
                }

            $sqlUpdate_pessoa = "UPDATE pessoa SET telefone = '$telefone' WHERE idPessoa = '$idPessoa';";

            $resultUpdate_endereco = mysqli_query($conexao, $sqlUpdate_endereco);
            $resultUpdate_pessoa = mysqli_query($conexao, $sqlUpdate_pessoa);

            // BENEFICIO GOV        
            if (!empty($beneficioGov) && !empty($valor_beneficio)) {
                switch ($beneficioGov) {
                    case "Novo Bolsa Família": $idNome_Beneficio = 1; break;
                    case "Benefício de Prestação Continuada (BPC)": $idNome_Beneficio = 2; break;
                    case "Aposentadoria": $idNome_Beneficio = 3; break;
                    case "Vale-Gas": $idNome_Beneficio = 4; break;
                    case "Outros": $idNome_Beneficio = 5; break;
                    default: $idNome_Beneficio = NULL; break;
                }

                if (empty($_SESSION['idbeneficioGov'])) { // Verifica se o usuário já possuía um benefício
                    $sqlInsert_BeneficioGov = "INSERT INTO BeneficioGov (idBeneficios_gov, valor_beneficio) VALUES ('$idNome_Beneficio', '$valor_beneficio');";
                    $resultUpdate_Beneficio = mysqli_query($conexao, $sqlInsert_BeneficioGov);

                    if ($resultUpdate_Beneficio) {
                        $sqlSelect_idBeneficioGov = "SELECT idBeneficioGov FROM BeneficioGov ORDER BY idBeneficioGov DESC LIMIT 1;";
                        $resultSelect_idBeneficioGov = mysqli_query($conexao, $sqlSelect_idBeneficioGov);
                        if (mysqli_num_rows($resultSelect_idBeneficioGov)) {
                            $dado_Beneficio = mysqli_fetch_assoc($resultSelect_idBeneficioGov);
                            $idBeneficioGov = $dado_Beneficio['idBeneficioGov'];
                            $_SESSION['idbeneficioGov'] = $idBeneficioGov;
                        }
                    }
                } else {
                    $idBeneficioGov = $_SESSION['idbeneficioGov']; // CORRIGIDO: usava o nome errado antes
                    $sqlUpdate_BeneficioGov = "UPDATE BeneficioGov SET idBeneficios_gov = '$idNome_Beneficio', valor_beneficio = '$valor_beneficio' WHERE idBeneficioGov = '$idBeneficioGov';";
                    $resultUpdate_Beneficio = mysqli_query($conexao, $sqlUpdate_BeneficioGov);
                }                          
            } 
            // UPDATE BENEFICIARIO
            //COM BENEFICIO
            if (!empty($idBeneficioGov)){
                $sqlUpdate_Beneficiario = "UPDATE Beneficiario SET data_nascimento = '$data_nascimento', email = '$email', estado_civil = '$estado_civil', PCD = '$pcd', laudo = '$laudo', doenca = '$comorbidade', quantos_dependentes = '$quantos_dependentes', renda_familiar = '$renda_familiar', qnt_trabalham = '$quantos_trabalham', idBeneficioGov = '$idBeneficioGov' WHERE idBeneficiario = '$id';";
            } else {
                $sqlUpdate_Beneficiario = "UPDATE Beneficiario SET data_nascimento = '$data_nascimento', email = '$email', estado_civil = '$estado_civil', PCD = '$pcd', laudo = '$laudo', doenca = '$comorbidade', quantos_dependentes = '$quantos_dependentes', renda_familiar = '$renda_familiar', qnt_trabalham = '$quantos_trabalham' WHERE idBeneficiario = '$id';";
            }

            
            $resultUpdate_Beneficiario = mysqli_query($conexao, $sqlUpdate_Beneficiario);           
            if ($resultUpdate_Beneficiario){
                echo "<script>window.alert('Beneficiário alterado com sucesso!');</script>";
            }

            if ($resultUpdate_endereco && $resultUpdate_pessoa && $resultUpdate_Beneficiario) {                               
                // LIBERAR AS SESSÕES
                unset($_SESSION['idBeneficiario']);
                unset($_SESSION['idbeneficioGov']);
                unset($_SESSION['cpf']);
                unset($_SESSION['idPessoa']);
                unset($_SESSION['idEndereco']);
                // Limpa variáveis em memória
                $id = '';
                $telefone = "";
                $beneficioGov = "";
                $valor_beneficio = "";
                $quantos_dependentes = "";
                $data_nascimento = "";
                $email = "";
                $estado_civil = "";
                $pcd = "";
                $laudo = "";
                $comorbidade = "";
                $renda_familiar = "";
                $quantos_trabalham = "";
                $endereco = "";
                $cidade = "";
                $estado = "";
                $situacao_moradia = "";
                $valor_despesas = "";
                $cep = "";
                $nome_Beneficiario = "";
                $_SESSION['beneficiario_alterado'] = true;                 
            }   else {
                echo "<script>alert('Erro ao alterar o beneficiário. Verifique os dados e tente novamente.');</script>";
            }
        }
    } else {
        echo "<script>alert('Há campos em branco');</script>";
        $erro = true;
    }
}    

if ($erro){
    // LIBERAR AS SESSÕES
    unset($_SESSION['idBeneficiario']);
    unset($_SESSION['idbeneficioGov']);
    unset($_SESSION['cpf']);
    unset($_SESSION['idPessoa']);
    unset($_SESSION['idEndereco']);
    // Limpa variáveis em memória
    $id = '';
    $telefone = "";
    $beneficioGov = "";
    $valor_beneficio = "";
    $quantos_dependentes = "";
    $data_nascimento = "";
    $email = "";
    $estado_civil = "";
    $pcd = "";
    $laudo = "";
    $comorbidade = "";
    $renda_familiar = "";
    $quantos_trabalham = "";
    $endereco = "";
    $cidade = "";
    $estado = "";
    $situacao_moradia = "";
    $valor_despesas = "";
    $cep = "";
    $nome_Beneficiario = "";
}

// DELETAR
if (isset($_POST['deletar']) && !empty($_POST['cpfBeneficiario'])){
    // VERIFICA SE O USUÁRIO QUER DELETAR
    echo "<script>window.confirm('Tem certeza que deseja excluir este Beneficiário ?');</script>";
    if (isset($_POST['conf_delete'])){
        // PRECISA VERIFICAR SE ESTE BENEFICIÁRIO TEM RELACIONAMENTO COM ALGUM DEPENDENTE

    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração Beneficiário</title>
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
            <!-- PRECISA CRIAR UMA CAIXA QUANDO O USUÁRIO FOR DELETAR UM DADO PARA CONFIRMAR
            CASO O ID_BENEFICIÁRIO ESTEJA RELACIONADO COM ALGUM DEPENDENTE, PRECISA EXCLUIR TODOS OS DEPENDENTES PRIMEIRO E PEDIR PAR CONFIRMAR AO USUÁRIO -->
            <div class="d-flex justify-content-around w-100 input-group mb-4">
                <select class="form-select form-select-md w-50" name="cpfBeneficiario">
                    <option value="">CPF do(a) Beneficiário(a)</option>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($Beneficiario_data = mysqli_fetch_assoc($result)): ?>
                            <option value="<?= $Beneficiario_data['cpf'] ?>" <?= (isset($cpfBeneficiario) && $cpfBeneficiario == $Beneficiario_data['cpf']) ? 'selected' : '' ?>>
                                <?= $Beneficiario_data['cpf'] ?>
                            </option>                        
                        <?php endwhile; ?>
                    <?php endif ?>
                </select>
                <button id="buttonPesquisarCPF" type="submit" value="1" name="pesquisar">
                    Pesquisar
                </button>
            </div>
            <!-- DADOS PESSOAIS -->
            <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
                Dados Pessoais
            </h3>
            <div class="d-flex flex-column container">
                <label class="form-label">Nome Completo:</label>
                <input type="text" id="nome" class="form-control border" disabled value="<?= !empty($nome_Beneficiario) ? $nome_Beneficiario : ''; ?>">
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">            
                <span class="col-xl-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimentoBeneficiario" value="<?= !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                </span>
                <span class="col-xl-4">
                    <label for="">Estado Civil</label>
                    <select class="form-select form-select-md" name="estado_civilBeneficiario">
                        <option value=""></option>
                        <option value="S" <?= !empty($estado_civil) && $estado_civil == 'S' ? 'selected' : ''; ?>>Solteiro</option>
                        <option value="C" <?= !empty($estado_civil) && $estado_civil == 'C' ? 'selected' : ''; ?>>Casado</option>
                        <option value="V" <?= !empty($estado_civil) && $estado_civil == 'V' ? 'selected' : ''; ?>>Viúvo</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5 col-xs-12">
                    <label>Telefone:</label>
                    <input type="text" maxlength="15" minlength="15" id="telefone" class="form-control" name="telefoneBeneficiario" value="<?= isset($_POST['pesquisar']) && !empty($telefone) ? $telefone : ''; ?>">                    
                </span>
                <span class="col-lg-6 col-xs-12">
                    <label>Email:</label>
                    <input type="email" class="form-control form-control-xl" name="emailBeneficiario" value="<?= !empty($email) ? $email : ''; ?>">
                </span>
            </div>
                <!-- ENDEREÇO -->
            <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
                    Endereço        
            </h3>
            <div class="d-flex justify-content-between container formularios_Beneficiario">
                <span class="col-lg-7 col-xs-12">
                    <label class="form-label">Endereço Completo:</label>
                    <input type="text" class="form-control border" name="endereco_completoBeneficiario" value="<?= !empty($endereco) ? $endereco : ''; ?>">            
                </span>
                <span class="col-lg-4 col-xs-12">
                    <label>CEP:</label>
                    <input type="text" id="cep" maxlength="9" minlength="9" class="form-control form-control-xl" name="cepBeneficiario" value="<?= !empty($cep) ? $cep : ''; ?>">
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-3">
                    <label for="">Cidade</label>
                    <input type="text" class="form-control" name="cidadeBeneficiario" id="cidade" value="<?= !empty($cidade) ? $cidade : ''; ?>">
                </span>
                <span class="col-lg-3">
                    <label for="">Estado</label>
                    <select class="form-select form-select-md" name="estadoBeneficiario">
                        <option value=""></option>
                        <option value="AC" <?= (!empty($estado) && $estado == 'AC') ? 'selected' : '' ?>>AC - Acre</option>
                        <option value="AL" <?= (!empty($estado) && $estado == 'AL') ? 'selected' : '' ?>>AL - Alagoas</option>
                        <option value="AP" <?= (!empty($estado) && $estado == 'AP') ? 'selected' : '' ?>>AP - Amapá</option>
                        <option value="AM" <?= (!empty($estado) && $estado == 'AM') ? 'selected' : '' ?>>AM - Amazonas</option>
                        <option value="BA" <?= (!empty($estado) && $estado == 'BA') ? 'selected' : '' ?>>BA - Bahia</option>
                        <option value="CE" <?= (!empty($estado) && $estado == 'CE') ? 'selected' : '' ?>>CE - Ceará</option>
                        <option value="DF" <?= (!empty($estado) && $estado == 'DF') ? 'selected' : '' ?>>DF - Distrito Federal</option>
                        <option value="ES" <?= (!empty($estado) && $estado == 'ES') ? 'selected' : '' ?>>ES - Espírito Santo</option>
                        <option value="GO" <?= (!empty($estado) && $estado == 'GO') ? 'selected' : '' ?>>GO - Goiás</option>
                        <option value="MA" <?= (!empty($estado) && $estado == 'MA') ? 'selected' : '' ?>>MA - Maranhão</option>
                        <option value="MT" <?= (!empty($estado) && $estado == 'MT') ? 'selected' : '' ?>>MT - Mato Grosso</option>
                        <option value="MS" <?= (!empty($estado) && $estado == 'MS') ? 'selected' : '' ?>>MS - Mato Grosso do Sul</option>
                        <option value="MG" <?= (!empty($estado) && $estado == 'MG') ? 'selected' : '' ?>>MG - Minas Gerais</option>
                        <option value="PA" <?= (!empty($estado) && $estado == 'PA') ? 'selected' : '' ?>>PA - Pará</option>
                        <option value="PB" <?= (!empty($estado) && $estado == 'PB') ? 'selected' : '' ?>>PB - Paraíba</option>
                        <option value="PR" <?= (!empty($estado) && $estado == 'PR') ? 'selected' : '' ?>>PR - Paraná</option>
                        <option value="PE" <?= (!empty($estado) && $estado == 'PE') ? 'selected' : '' ?>>PE - Pernambuco</option>
                        <option value="PI" <?= (!empty($estado) && $estado == 'PI') ? 'selected' : '' ?>>PI - Piauí</option>
                        <option value="RJ" <?= (!empty($estado) && $estado == 'RJ') ? 'selected' : '' ?>>RJ - Rio de Janeiro</option>
                        <option value="RN" <?= (!empty($estado) && $estado == 'RN') ? 'selected' : '' ?>>RN - Rio Grande do Norte</option>
                        <option value="RS" <?= (!empty($estado) && $estado == 'RS') ? 'selected' : '' ?>>RS - Rio Grande do Sul</option>
                        <option value="RO" <?= (!empty($estado) && $estado == 'RO') ? 'selected' : '' ?>>RO - Rondônia</option>
                        <option value="RR" <?= (!empty($estado) && $estado == 'RR') ? 'selected' : '' ?>>RR - Roraima</option>
                        <option value="SC" <?= (!empty($estado) && $estado == 'SC') ? 'selected' : '' ?>>SC - Santa Catarina</option>
                        <option value="SE" <?= (!empty($estado) && $estado == 'SE') ? 'selected' : '' ?>>SE - Sergipe</option>
                        <option value="TO" <?= (!empty($estado) && $estado == 'TO') ? 'selected' : '' ?>>TO - Tocantis</option>                    
                    </select>
                </span>
                <span class="col-lg-4">
                    <label for="">Situação da moradia</label>
                    <select class="form-select form-select-md" name="situacao_moradiaBeneficiario">
                        <option value=""></option>
                        <option value="C" <?= (!empty($situacao_moradia) && $situacao_moradia == 'C') ? 'selected' : ''; ?>>Comprada</option>
                        <option value="A" <?= (!empty($situacao_moradia) && $situacao_moradia == 'A') ? 'selected' : ''; ?>>Alugada</option>
                        <option value="O" <?= (!empty($situacao_moradia) && $situacao_moradia == 'O') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-12">
                    <label>Valor do Aluguel + Despesas (Água e luz):</label>
                    <input type="number" class="form-control" id="valor" name="valor_despesasBeneficiario" value="<?= !empty($valor_despesas) ? $valor_despesas : ''; ?>">
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
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S" <?= isset($_POST['pesquisar']) && !empty($BeneficioGov) ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N"<?= isset($_POST['pesquisar']) && empty($BeneficioGov) ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                        <select name="beneficioBeneficiario" class="form-select form-select-md">
                            <option value=""></option>
                            
                            <option value="Aposentadoria" <?= (!empty($BeneficioGov) && $BeneficioGov == "Aposentadoria") ? 'selected' : '' ?>>Aposentadoria</option>
                            <option value="Benefício de Prestação Continuada (BPC)" <?= (!empty($BeneficioGov) && $BeneficioGov == "Benefício de Prestação Continuada (BPC)") ? 'selected' : '' ?>>Benefício de Prestação Continuada (BPC)</option>
                            <option value="Novo Bolsa Família" <?= (!empty($BeneficioGov) && $BeneficioGov == "Novo Bolsa Família") ? 'selected' : '' ?>>Novo Bolsa Família</option>
                            <option value="Vale-Gas" <?= (!empty($BeneficioGov) && $BeneficioGov == "Vale-Gas") ? 'selected' : '' ?>>Vale-Gas</option>
                            <option value="Outros" <?= (!empty($BeneficioGov) && $BeneficioGov == "Outros") ? 'selected' : '' ?>>Outros</option>
                        </select>                   
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" id="valorB" class="form-control" name="valor_benecicioBeneficiario" value="<?= !empty($valor_beneficio) ? $valor_beneficio : ''; ?>">
                </span> 
            </div>
            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0" id="form_beneficiario_beneficio">
                    <span class="col-6">
                        <label for="">PCD?</label>
                        <div class="d-flex container p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="S" <?= !empty($pcd) && $pcd == 'S' ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="N" <?= !empty($pcd) && $pcd == 'N' ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-6  justify-content-start">
                        <label for="">Possui Laudo Médico?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="S" <?= !empty($laudo) && $laudo == 'S' ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="N" <?= !empty($laudo) && $laudo == 'N' ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>                 
                </div>           
                <span class="col-lg-6">
                    <label for="">Nome da Comorbidade</label>
                    <input type="text" class="form-control" name="doenca" id="comorbidade" value="<?= !empty($comorbidade) ? $comorbidade : ''; ?>">
                </span> 
            </div> 
                <div class="d-flex flex-row justify-content-between container formularios_Beneficiario mt-3">        
                <span class="col-4">
                    <label for="">Possui Dependentes?</label>                    
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="S" <?= isset($_POST['pesquisar']) && (!empty($quantos_dependentes) && $quantos_dependentes > 0) ? 'checked' : ''; ?>>
                            <label for="depSim">Sim</label>                        
                        </div>                         
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="N" <?= isset($_POST['pesquisar']) && (empty($quantos_dependentes) || $quantos_dependentes <= 0) ? 'checked' : ''; ?>>
                            <label for="depNao">Não</label>
                        </div>               
                    </div>
                </span> 
                <span class="col-lg-7  col-sm-6">
                    <label for="">Quantos?</label>
                    <input type="number" id="quantos_dependentes" class="form-control" name="quantos_dependentes" value="<?= !empty($quantos_dependentes) ? $quantos_dependentes : ''; ?>">
                </span> 
            </div>                                    
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5  col-xs-12">
                    <!-- ?????????????????????????????????????????????????????????????????????????????????????????????? -->
                    <label>Quantos trabalham em casa:</label>
                    <input type="number" class="form-control" id="quantos_trabalham" name="quantos_trabalhamBeneficiario" value="<?= !empty($quantos_trabalham) ? $quantos_trabalham : '';?>">
                </span>
                <span class="col-lg-6  col-xs-12">
                    <label>Renda Familiar total:</label>
                    <input type="number" class="form-control" id="renda_familiar" name="renda_familiarBeneficiario" value="<?= !empty($renda_familiar) ? $renda_familiar : ''; ?>">
                </span>
            </div>            
            <div class="d-flex container justify-content-around w-100 align-items-center mb-5" style="margin-top: 3em;">
                <span class="align-items-center text-center">
                    <a href="alteracao.php" class="text-decoration-none">
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
                 <button type="submit" class="botoes_crud" name="alterar" value="1">
                 <!-- <button type="submit" class="botoes_crud" name="ver" value="1"> -->
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
