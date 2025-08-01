<?php
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");   

    function get_endereco($cep){

    // MÁSCARAS: data_nascimento não pode ser uma data inexistente. | vós não podem ter idade < 60 | pais não podem ter idade < 40
        

    // formatar o cep removendo caracteres nao numericos    
    $url = "http://viacep.com.br/ws/$cep/xml/";

    $xml = simplexml_load_file($url);
    return $xml;
    }

    // $endereco = get_endereco("37500405");

    // tem que pegar o dado do email
    $cadastrado = False;                            
    $erro = false;
    $mudar_pagina = false; 

    if (
        isset($_POST['cadastrar'])
        && !empty($_POST['nome_completoBeneficiario']) 
        && !empty($_POST['cpfBeneficiario']) 
        && !empty($_POST['data_nascimentoBeneficiario']) 
        && !empty($_POST['estado_civilBeneficiario']) 
        && !empty($_POST['telefoneBeneficiario'])         
        && !empty($_POST['endereco_completoBeneficiario'])
        && !empty($_POST['cepBeneficiario']) 
        && !empty($_POST['cidadeBeneficiario']) 
        && !empty($_POST['estadoBeneficiario'])
        && !empty($_POST['situacao_moradiaBeneficiario'])
        && !empty($_POST['valor_despesasBeneficiario'])  
        && !empty($_POST['rbPossuiBenf'])     
        && !empty($_POST['rbPCD'])     
        && !empty($_POST['rbPossuiDependentes'])  
        && !empty($_POST['renda_familiarBeneficiario'])
        && !empty($_POST['quantos_trabalhamBeneficiario'])
    ) {

// Formatando CPF
$cpfBeneficiario = str_replace(['-', '.', ' '], '', $_POST['cpfBeneficiario']);

// Verificar se o CPF já existe
$sql = "SELECT idPessoa FROM pessoa WHERE cpf = '$cpfBeneficiario'";
$result = mysqli_query($conexao, $sql);
if (!$result) {
    die("Erro na consulta de pessoa: " . mysqli_error($conexao));
}
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $idPessoa1 = $row['idPessoa'];

    $sql = "SELECT idBeneficiario FROM Beneficiario WHERE idPessoa = '$idPessoa1'";
    $result_benef = mysqli_query($conexao, $sql);
    if (!$result_benef) {
        die("Erro na consulta de beneficiário: " . mysqli_error($conexao));
    }
    if (mysqli_num_rows($result_benef) > 0) {
        echo "<script>window.alert('CPF já cadastrado');</script>";
        $erro = true;        
    }
}

//verificar se já possui o cep
$cep = str_replace(['-', '.', ' '], '', $_POST['cepBeneficiario']);
$sql = "SELECT cep FROM endereco WHERE cep = '$cep';";
$result = mysqli_query($conexao, $sql);

if (!$result) {
    die("Erro na consulta de endereco: " . mysqli_error($conexao));    
}

if (mysqli_num_rows($result) > 0) {
    // Já possui alguém com o mesmo cep
    echo "<script>window.alert('CEP já cadastrado');</script>";
    $erro = True;    
} else {
    $erro = False;
}

// verificar a quantidade de caracteres
if (strlen($cpfBeneficiario) < 11 || strlen($cep) < 8){
    echo "<script>window.alert('Quantidade de caracteres de CPF ou CEP insuficientes');</script>";
    $erro = True;
}

if (!$erro) {
    $nome_completo = $_POST['nome_completoBeneficiario'];
    $data_nascimento = $_POST['data_nascimentoBeneficiario'];
    $estado_civil = $_POST['estado_civilBeneficiario'];
    $telefone = str_replace(['(', ')', ' '], '', $_POST['telefoneBeneficiario']);    
    $sqlTelefone = "SELECT * FROM pessoa WHERE telefone = '$telefone';";
            $resultTelefone = mysqli_query($conexao, $sqlTelefone);
                if (mysqli_num_rows($resultTelefone) > 0){                                       
                        echo "<script>window.alert('O telefone informado já está cadastrado por outra pessoa');</script>";
                        $erro = True;                        
                    }
                
    if (strlen($telefone) < 11){
        echo "<script>window.alert('Quantidade de caracteres do telefone insuficientes');</script>";
        $erro = True;
    }
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
   }
    } else if ($idade < 20){
        echo "<script>window.alert('A idade mínima para se cadastrar como Beneficiário(a) é de 20 anos.');</script>";
        $erro = True;
    }

    $email = $_POST['emailBeneficiario'];
    $endereco = $_POST['endereco_completoBeneficiario'];    
    $cidade = $_POST['cidadeBeneficiario'];
    $estado = $_POST['estadoBeneficiario'];
    $situacao_moradia = $_POST['situacao_moradiaBeneficiario'];
    $valor_despesas = $_POST['valor_despesasBeneficiario'];
    $renda_familiar = $_POST['renda_familiarBeneficiario'];
    $rbPossuiBenf = $_POST['rbPossuiBenf'];
    $rbPCD = $_POST['rbPCD'];
    $rbPossuiDependentes = $_POST['rbPossuiDependentes'];
    $quantos_trabalham = $_POST['quantos_trabalhamBeneficiario'];
    $rbPossuiLaudo = $_POST['rbPossuiLaudo'] ?? 'N';

    // Benefício
    $beneficio = '';
    $valor_beneficio = '';
    if ($rbPossuiBenf == "S") {
        if (!empty($_POST['beneficioBeneficiario']) && !empty($_POST['valor_benecicioBeneficiario'])) {
            $beneficio = $_POST['beneficioBeneficiario'];
            $valor_beneficio = $_POST['valor_benecicioBeneficiario'];
        } else {
            echo "<script>window.alert('Campo Beneficío está em branco');</script>";
            $erro = True;            
        }
    }

    // Dependentes
    $quantos_dependentes = 0;
    if ($rbPossuiDependentes == "S") {
        if (!empty($_POST['quantos_dependentes'])) {
            $quantos_dependentes = $_POST['quantos_dependentes'];
        } else {
            echo "<script>window.alert('Incoerência em relação à Dependentes');</script>";
            $erro = True;
        }
    }

    // PCD
    $nome_doenca = "-";
    if ($rbPCD == "S") {
        if (!empty($rbPossuiLaudo) && !empty($_POST['nome_doencaBeneficiario'])) {
            $nome_doenca = $_POST['nome_doencaBeneficiario'];
        } else {
            echo "<script>window.alert('Incoerência em relação à comorbidade');</script>";
            $erro = True;
        }
    } else {
        $rbPossuiLaudo = "N";
    }

    if (!$erro) {
        // Inserir pessoa
        $sql = "INSERT INTO pessoa (nome_completo, cpf, telefone) VALUES ('$nome_completo', '$cpfBeneficiario', '$telefone')";
        if (!mysqli_query($conexao, $sql)) {
            die("Erro ao inserir pessoa: " . mysqli_error($conexao));
        }
        $idPessoa = mysqli_insert_id($conexao);

        // Inserir endereço
        $sql = "INSERT INTO endereco (endereco, cidade, estado, cep, situacao_moradia, valor_despesas, idPessoa) VALUES ('$endereco', '$cidade', '$estado', '$cep', '$situacao_moradia', '$valor_despesas', '$idPessoa')";
        if (!mysqli_query($conexao, $sql)) {
            die("Erro ao inserir endereço: " . mysqli_error($conexao));
        }
        $idEndereco = mysqli_insert_id($conexao);

        // Inserir benefício, se existir
        $idBeneficio = null;    
        if (!empty($beneficio) && !empty($valor_beneficio)) {
            $sql = "SELECT idBeneficios_gov FROM nomeBeneficiosGov WHERE nome_beneficiogov = '$beneficio';";
            $result = mysqli_query($conexao, $sql);
            if (!$result) {
                die("Erro ao buscar benefício: " . mysqli_error($conexao));
            }
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $idBeneficiosGov = $row['idBeneficios_gov'];

                $sql = "INSERT INTO BeneficioGov (idBeneficios_gov, valor_beneficio) VALUES ('$idBeneficiosGov', '$valor_beneficio')";
                if (!mysqli_query($conexao, $sql)) {
                    die("Erro ao inserir benefício: " . mysqli_error($conexao));
                }
                $idBeneficio = mysqli_insert_id($conexao);
            }
        }

        // Inserir beneficiário
        $sql = "INSERT INTO Beneficiario (
        data_nascimento, estado_civil, email, PCD, laudo, doenca, quantos_dependentes, renda_familiar,
        idPessoa, idEndereco, idBeneficioGov, qnt_trabalham
        ) VALUES (
            '$data_nascimento', '$estado_civil', '$email','$rbPCD', '$rbPossuiLaudo', '$nome_doenca', '$quantos_dependentes',
            '$renda_familiar', '$idPessoa', '$idEndereco', " . ($idBeneficio === null ? "NULL" : "'$idBeneficio'") . ", '$quantos_trabalham'
        )";
        if (!mysqli_query($conexao, $sql)) {
            die("Erro ao inserir beneficiário: " . mysqli_error($conexao));
        }
        $idBeneficiario = mysqli_insert_id($conexao);

        echo "<script>window.alert('Beneficiário cadastrado com sucesso!');</script>";            

        // PRECISA CADASTRAR A FREQUÊNCIA COMO F
        $sql = "INSERT INTO frequencia (ANO, MES, REGISTRO, idBeneficiario) VALUES ('2023', 'JAN', 'F', '$idBeneficiario'), ('2023', 'FEV', 'F', '$idBeneficiario'), ('2023', 'MAR', 'F', '$idBeneficiario'), ('2023', 'ABR', 'F', '$idBeneficiario'), ('2023', 'MAI', 'F', '$idBeneficiario'), ('2023', 'JUN', 'F', '$idBeneficiario'), ('2023', 'JUL', 'F', '$idBeneficiario'), ('2023', 'AGO', 'F', '$idBeneficiario'), ('2023', 'SET', 'F', '$idBeneficiario'), ('2023', 'OUT', 'F', '$idBeneficiario'), ('2023', 'NOV', 'F', '$idBeneficiario'), ('2023', 'DEZ', 'F', '$idBeneficiario'), 
        ('2024', 'JAN', 'F', '$idBeneficiario'), ('2024', 'FEV', 'F', '$idBeneficiario'), ('2024', 'MAR', 'F', '$idBeneficiario'), ('2024', 'ABR', 'F', '$idBeneficiario'), ('2024', 'MAI', 'F', '$idBeneficiario'), ('2024', 'JUN', 'F', '$idBeneficiario'), ('2024', 'JUL', 'F', '$idBeneficiario'), ('2024', 'AGO', 'F', '$idBeneficiario'), ('2024', 'SET', 'F', '$idBeneficiario'), ('2024', 'OUT', 'F', '$idBeneficiario'), ('2024', 'NOV', 'F', '$idBeneficiario'), ('2024', 'DEZ', 'F', '$idBeneficiario'), 
        ('2025', 'JAN', 'F', '$idBeneficiario'), ('2025', 'FEV', 'F', '$idBeneficiario'), ('2025', 'MAR', 'F', '$idBeneficiario'), ('2025', 'ABR', 'F', '$idBeneficiario'), ('2025', 'MAI', 'F', '$idBeneficiario'), ('2025', 'JUN', 'F', '$idBeneficiario'), ('2025', 'JUL', 'F', '$idBeneficiario'), ('2025', 'AGO', 'F', '$idBeneficiario'), ('2025', 'SET', 'F', '$idBeneficiario'), ('2025', 'OUT', 'F', '$idBeneficiario'), ('2025', 'NOV', 'F', '$idBeneficiario'), ('2025', 'DEZ', 'F', '$idBeneficiario')";

        $result = mysqli_query($conexao, $sql);


        $cpfBeneficiario = NULL; // Para não cadastrar novamente

        // Redirecionar se possuir dependentes
        if ($quantos_dependentes > 0) {
            echo "<script>window.alert('Beneficiário cadastrado com sucesso!');</script>";
            $cadastrado = true;
            $mudar_pagina = true;            
        }

        if ($mudar_pagina){
            header("Location: ../php/cadastroDependente.php?IDbeneficiario=$idBeneficiario&dependentes_pendentes=$quantos_dependentes");
            exit;
        }
    }
}
else {
    echo '<script>alert("Existem campos em branco.");</script>';
    $erro = true;
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
        <form action="" class="container_formularios" method="post">  
            <!-- DADOS PESSOAIS -->
            <h3 style="text-align: center;" class="mb-3" id="subtitulos_paginas">
                Dados Pessoais
            </h3>
            <div class="d-flex flex-column container">
                <label class="form-label">Nome Completo:</label>
                <input type="text" required class="form-control border" name="nome_completoBeneficiario" id="nome" value="<?php if(isset($_POST['nome_completoBeneficiario']) && !$cadastrado) echo $_POST['nome_completoBeneficiario']; ?>">            
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-xl-3">
                    <label for="">CPF</label>
                    <input type="text" maxlength="14" minlength="14" id="cpf" required class="form-control" name="cpfBeneficiario" value="<?php if(isset($_POST['cpfBeneficiario']) && !$cadastrado) echo $_POST['cpfBeneficiario']; ?>">
                </span>
                <span class="col-xl-3">
                    <label for="">Data Nascimento</label>
                    <input type="date" required class="form-control" name="data_nascimentoBeneficiario" value="<?php if(isset($_POST['data_nascimentoBeneficiario']) && !$cadastrado) echo $_POST['data_nascimentoBeneficiario']; ?>">
                </span>
                <span class="col-xl-4">
                    <label for="">Estado Civil</label>
                    <select class="form-select form-select-md" name="estado_civilBeneficiario" required>
                        <option value=""></option>
                        <option value="S" <?php echo(isset($_POST['estado_civilBeneficiario']) && $_POST['estado_civilBeneficiario'] == 'S' && !$cadastrado) ? 'selected ': ''; ?>>Solteiro</option>
                        <option value="C" <?php echo(isset($_POST['estado_civilBeneficiario']) && $_POST['estado_civilBeneficiario'] == 'C' && !$cadastrado) ? 'selected ': ''; ?>>Casado</option>
                        <option value="V" <?php echo(isset($_POST['estado_civilBeneficiario']) && $_POST['estado_civilBeneficiario'] == 'V' && !$cadastrado) ? 'selected ': ''; ?>>Viúvo</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5 col-xs-12">
                    <label>Telefone:</label>
                    <input type="text" maxlength="15" minlength="15" id="telefone" required class="form-control" name="telefoneBeneficiario" value="<?php if(isset($_POST['telefoneBeneficiario']) && !$cadastrado) echo $_POST['telefoneBeneficiario']; ?>">
                </span>
                <span class="col-lg-6 col-xs-12">
                    <label>Email:</label>
                    <input type="email" class="form-control form-control-xl" name="emailBeneficiario" value="<?php if(isset($_POST['emailBeneficiario']) && !$cadastrado) echo $_POST['emailBeneficiario']; ?>">
                </span>
            </div>
                <!-- ENDEREÇO -->
            <h3 style="text-align: center;" class="mb-3 mt-5" id="subtitulos_paginas">
                    Endereço        
            </h3>
            <div class="d-flex justify-content-between container formularios_Beneficiario">
                <span class="col-lg-7 col-xs-12">
                    <label class="form-label">Endereço Completo:</label>
                    <input type="text" required class="form-control border" name="endereco_completoBeneficiario" value="<?php if(isset($_POST['endereco_completoBeneficiario']) && !$cadastrado) echo $_POST['endereco_completoBeneficiario']; ?>">            
                </span>
                <span class="col-lg-4 col-xs-12">
                    <label>CEP:</label>
                    <input type="text" id="cep" maxlength="9" minlength="9" required class="form-control form-control-xl" name="cepBeneficiario" value="<?php if(isset($_POST['cepBeneficiario']) && !$cadastrado) echo $_POST['cepBeneficiario']; ?>">
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-3">
                    <label for="">Cidade</label>
                    <input type="text" required class="form-control" name="cidadeBeneficiario" id="cidade" value="<?php if(isset($_POST['cidadeBeneficiario']) && !$cadastrado) echo $_POST['cidadeBeneficiario']; ?>">
                </span>
                <span class="col-lg-3">
                    <label for="">Estado</label>
                    <select class="form-select form-select-md" name="estadoBeneficiario" required>
                        <option value=""></option>
                        <option value="AC" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'AC' && !$cadastrado) ? 'selected ': ''; ?>>AC - Acre</option>
                        <option value="AL" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'AL' && !$cadastrado) ? 'selected ': ''; ?>>AL - Alagoas</option>
                        <option value="AP" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'AP' && !$cadastrado) ? 'selected ': ''; ?>>AP - Amapá</option>
                        <option value="AM" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'AM' && !$cadastrado) ? 'selected ': ''; ?>>AM - Amazonas</option>
                        <option value="BA" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'BA' && !$cadastrado) ? 'selected ': ''; ?>>BA - Bahia</option>
                        <option value="CE" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'CE' && !$cadastrado) ? 'selected ': ''; ?>>CE - Ceará</option>
                        <option value="DF" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'DF' && !$cadastrado) ? 'selected ': ''; ?>>DF - Distrito Federal</option>
                        <option value="ES" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'ES' && !$cadastrado) ? 'selected ': ''; ?>>ES - Espírito Santo</option>
                        <option value="GO" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'GO' && !$cadastrado) ? 'selected ': ''; ?>>GO - Goiás</option>
                        <option value="MA" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'MA' && !$cadastrado) ? 'selected ': ''; ?>>MA - Maranhão</option>
                        <option value="MT" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'MT' && !$cadastrado) ? 'selected ': ''; ?>>MT - Mato Grosso</option>
                        <option value="MS" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'MS' && !$cadastrado) ? 'selected ': ''; ?>>MS - Mato Grosso do Sul</option>
                        <option value="MG" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'MG' && !$cadastrado) ? 'selected ': ''; ?>>MG - Minas Gerais</option>
                        <option value="PA" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'PA' && !$cadastrado) ? 'selected ': ''; ?>>PA - Pará</option>
                        <option value="PB" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'PB' && !$cadastrado) ? 'selected ': ''; ?>>PB - Paraíba</option>
                        <option value="PR" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'PR' && !$cadastrado) ? 'selected ': ''; ?>>PR - Paraná</option>
                        <option value="PE" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'PE' && !$cadastrado) ? 'selected ': ''; ?>>PE - Pernambuco</option>
                        <option value="PI" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'PI' && !$cadastrado) ? 'selected ': ''; ?>>PI - Piauí</option>
                        <option value="RJ" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'RJ' && !$cadastrado) ? 'selected ': ''; ?>>RJ - Rio de Janeiro</option>
                        <option value="RN" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'RN' && !$cadastrado) ? 'selected ': ''; ?>>RN - Rio Grande do Norte</option>
                        <option value="RS" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'RS' && !$cadastrado) ? 'selected ': ''; ?>>RS - Rio Grande do Sul</option>
                        <option value="RO" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'RO' && !$cadastrado) ? 'selected ': ''; ?>>RO - Rondônia</option>
                        <option value="RR" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'RR' && !$cadastrado) ? 'selected ': ''; ?>>RR - Roraima</option>
                        <option value="SC" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'SC' && !$cadastrado) ? 'selected ': ''; ?>>SC - Santa Catarina</option>
                        <option value="SE" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'SE' && !$cadastrado) ? 'selected ': ''; ?>>SE - Sergipe</option>
                        <option value="TO" <?php echo(isset($_POST['estadoBeneficiario']) && $_POST['estadoBeneficiario'] == 'TO' && !$cadastrado) ? 'selected ': ''; ?>>TO - Tocantis</option>                    
                    </select>
                </span>
                <span class="col-lg-4">
                    <label for="">Situação da moradia</label>
                    <select class="form-select form-select-md" name="situacao_moradiaBeneficiario" required>
                        <option value=""></option>
                        <option value="C" <?php echo(isset($_POST['situacao_moradiaBeneficiario']) && $_POST['situacao_moradiaBeneficiario'] == 'C' && !$cadastrado) ? 'selected ': ''; ?>>Comprada</option>
                        <option value="A" <?php echo(isset($_POST['situacao_moradiaBeneficiario']) && $_POST['situacao_moradiaBeneficiario'] == 'A' && !$cadastrado) ? 'selected ': ''; ?>>Alugada</option>
                        <option value="O" <?php echo(isset($_POST['situacao_moradiaBeneficiario']) && $_POST['situacao_moradiaBeneficiario'] == 'O' && !$cadastrado) ? 'selected ': ''; ?>>Outro</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-12">
                    <label>Valor do Aluguel + Despesas (Água e luz):</label>
                    <input type="number" class="form-control" name="valor_despesasBeneficiario" id="valor" value="<?php if(isset($_POST['valor_despesasBeneficiario']) && !$cadastrado) echo $_POST['valor_despesasBeneficiario']; ?>">
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
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S" <?php echo(isset($_POST['rbPossuiBenf']) && $_POST['rbPossuiBenf'] == 'S' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N" <?php echo(isset($_POST['rbPossuiBenf']) && $_POST['rbPossuiBenf'] == 'N' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                        <select name="beneficioBeneficiario" class="form-select form-select-md" id="beneficio">
                            <option value=""></option>
                            <option value="Aposentadoria" <?php echo(isset($_POST['beneficioBeneficiario']) && $_POST['beneficioBeneficiario'] == 'Aposentadoria' && !$cadastrado) ? 'selected ': ''; ?>>Aposentadoria</option>
                            <option value="Benefício de Prestação Continuada (BPC)" <?php echo(isset($_POST['beneficioBeneficiario']) && $_POST['beneficioBeneficiario'] == 'Benefício de Prestação Continuada (BPC)' && !$cadastrado) ? 'selected ': ''; ?>>Benefício de Prestação Continuada (BPC)</option>
                            <option value="Novo Bolsa Família" <?php echo(isset($_POST['beneficioBeneficiario']) && $_POST['beneficioBeneficiario'] == 'Novo Bolsa Família' && !$cadastrado) ? 'selected ': ''; ?>>Bolsa Família</option>
                            <option value="Vale-gas" <?php echo(isset($_POST['beneficioBeneficiario']) && $_POST['beneficioBeneficiario'] == 'Vale-gas' && !$cadastrado) ? 'selected ': ''; ?>>Vale Gás</option>
                            <option value="Outros" <?php echo(isset($_POST['beneficioBeneficiario']) && $_POST['beneficioBeneficiario'] == 'Outros' && !$cadastrado) ? 'selected ': ''; ?>>Outros</option>                            

                        </select>                   
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" id="valorB" class="form-control" name="valor_benecicioBeneficiario" value="<?php if(isset($_POST['valor_benecicioBeneficiario']) && !$cadastrado) echo $_POST['valor_benecicioBeneficiario']; ?>">
                </span> 
            </div>
            <div class="d-flex container justify-content-between formularios_Beneficiario mt-3">
                <div class="d-flex col-lg-6 flex-row container justify-content-between p-0" id="form_beneficiario_beneficio">
                    <span class="col-6">
                        <label for="">PCD?</label>
                        <div class="d-flex container p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="S" <?php echo(isset($_POST['rbPCD']) && $_POST['rbPCD'] == 'S' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPCD" value="N" <?php echo(isset($_POST['rbPCD']) && $_POST['rbPCD'] == 'N' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-6  justify-content-start">
                        <label for="">Possui Laudo Médico?</label>
                        <div class="d-flex container justify-content-start p-0">
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="S" <?php echo(isset($_POST['rbPossuiLaudo']) && $_POST['rbPossuiLaudo'] == 'S' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiLaudo" value="N" <?php echo(isset($_POST['rbPossuiLaudo']) && $_POST['rbPossuiLaudo'] == 'S' && !$cadastrado) ? 'checked ': ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>                 
                </div>           
                <span class="col-lg-6">
                    <label for="">Nome da Comorbidade</label>
                    <input type="text" class="form-control" name="nome_doencaBeneficiario" id="comorbidade" value="<?php if(isset($_POST['nome_doencaBeneficiario']) && !$cadastrado) echo $_POST['nome_doencaBeneficiario']; ?>">
                </span> 
            </div> 
              <div class="d-flex flex-row justify-content-between container formularios_Beneficiario mt-3">        
                <span class="col-4">
                    <label for="">Possui Dependentes?</label>                    
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="S" <?php echo(isset($_POST['rbPossuiDependentes']) && $_POST['rbPossuiDependentes'] == 'S' && !$cadastrado) ? 'checked ': ''; ?>>
                            <label for="depSim">Sim</label>                        
                        </div>                         
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="N" <?php echo(isset($_POST['rbPossuiDependentes']) && $_POST['rbPossuiDependentes'] == 'N' && !$cadastrado) ? 'checked ': ''; ?>>
                            <label for="depNao">Não</label>
                        </div>               
                    </div>
                </span> 
                <span class="col-lg-7  col-sm-6">
                    <label for="">Quantos?</label>
                    <input type="number" id="quantos_dependentes" class="form-control" name="quantos_dependentes" value="<?php if(isset($_POST['quantos_dependentes']) && !$cadastrado) echo $_POST['quantos_dependentes']; ?>">
                </span> 
            </div>                                    
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5  col-xs-12">
                    <label>Quantos trabalham em casa:</label>
                    <input type="number" class="form-control" id="quantos_trabalham" name="quantos_trabalhamBeneficiario" value="<?php if(isset($_POST['quantos_trabalhamBeneficiario']) && !$cadastrado) echo $_POST['quantos_trabalhamBeneficiario']; ?>">
                </span>
                <span class="col-lg-6  col-xs-12">
                    <label>Renda Familiar total:</label>
                    <input type="number" required class="form-control" id="renda_familiar" name="renda_familiarBeneficiario" value="<?php if(isset($_POST['renda_familiarBeneficiario']) && !$cadastrado) echo $_POST['renda_familiarBeneficiario']; ?>">
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
