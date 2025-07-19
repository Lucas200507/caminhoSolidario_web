<?php
    // PARA ALTERAR A QUANTIDADE DE DEPENDENTES, PRECISA VERIFICAR SE JÁ POSSUI CADASTRADOS DEPENDENTES NO NOME DESTE BENEFICIÁRIO
    include_once('../conexao_banco.php'); // ACESSANDO A CONEXÃO
    include_once('../routes/verificacao_logado.php'); // VERIFICAÇÃO SE O USUÁRIO ESTÁ LOGADO
    // Acessando o dados_usuario_logado para receber seus dados 
    include_once("../routes/dados_usuarioLogado.php");

    // PEGANDO O CPF DO BNEFICIARIO
    $sql = "SELECT cpf FROM tbBeneficiario;";
    $result = mysqli_query($conexao, $sql);

    if(isset($_POST['pesquisar']) && !empty($_POST['cpfBeneficiario'])){
        $cpfBeneficiario = $_POST['cpfBeneficiario'];
        //  TEM QUE SELECIONAR PRIMEIRO O ID
        $sql = "SELECT ID FROM tbBeneficiario WHERE cpf = '$cpfBeneficiario';";
        $result0 = mysqli_query($conexao, $sql);
        if(mysqli_num_rows($result0) > 0){
            while ($dados_tbBeneficiario = mysqli_fetch_assoc($result0)){            
                $idBeneficiario = $dados_tbBeneficiario['ID'];
            }

            // AGORA LISTAMOS A TABELA BENEFICIARIO PELO ID            
                $sql2 = "SELECT * FROM Beneficiario WHERE idBeneficiario = '$idBeneficiario';";
                $result2 = mysqli_query($conexao, $sql2);
                    if(mysqli_num_rows($result2) > 0){
                        while ($dadosBeneficiario = mysqli_fetch_assoc($result2)){                            
                           
                            $data_nascimento = $dadosBeneficiario['data_nascimento'];
                            $email = $dadosBeneficiario['email'];
                            $estado_civil = $dadosBeneficiario['estado_civil'];
                            $pcd = $dadosBeneficiario['PCD'];
                            $laudo = $dadosBeneficiario['laudo'];
                            $comorbidade = $dadosBeneficiario['doenca'];
                            $quantos_dependentes = $dadosBeneficiario['quantos_dependentes'];
                            $renda_familiar = $dadosBeneficiario['renda_familiar'];
                            $idPessoa = $dadosBeneficiario['idPessoa'];
                            $idEndereco = $dadosBeneficiario['idEndereco'];                            
                        }

                        // AGORA PRECISAMOS LISTAR OS DADOS DE pessoa, endereco e Beneficio Gov
                        $sql3 = "SELECT * FROM pessoa WHERE idPessoa = '$idPessoa';";
                        $result3 = mysqli_query($conexao, $sql3);
                        if (mysqli_num_rows($result3) > 0){
                            while ($dados_pessoa = mysqli_fetch_assoc($result3)){
                                $nome_Beneficiario = $dados_pessoa['nome_completo'];
                                $telefone = $dados_pessoa['telefone'];
                            }
                        }
                        $sql4 = "SELECT * FROM endereco WHERE idEndereco = '$idEndereco';";
                        $result4 = mysqli_query($conexao, $sql4);
                        if (mysqli_num_rows($result4) > 0){
                            while($dados_endereco = mysqli_fetch_assoc($result4)){
                                $endereco = $dados_endereco['endereco'];
                                $cidade = $dados_endereco['cidade'];
                                $estado = $dados_endereco['estado'];
                                $cep = $dados_endereco['cep'];
                                $situacao_moradia = $dados_endereco['situacao_moradia'];
                                $valor_despesas = $dados_endereco['valor_despesas'];
                            }
                        }                        

                    }
            
        }
    }
    // tem que pegar todos os dados deste cpf
    

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
            <div class="d-flex justify-content-around w-100 input-group mb-4">
                <select class="form-select form-select-md w-50" name="cpfBeneficiario">
                    <option value="">CPF do(a) Beneficiário(a)</option>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($Beneficiario_data = mysqli_fetch_assoc($result)): ?>
                            <option value="<?= $Beneficiario_data['cpf'] ?>" <?= (isset($cpfBeneficiario) && $cpfBeneficiario == $Beneficiario_data['cpf']) ? 'selected' : '' ?>>
                                <?= $Beneficiario_data['cpf'] ?>
                            </option>
                            <?php $BeneficioGov = $Beneficiario_data['beneficioGov']; ?>
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
                <input type="text" class="form-control border" disabled value="<?= !empty($nome_Beneficiario) ? $nome_Beneficiario : ''; ?>">
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">            
                <span class="col-md-5 col-sm-12">
                        <label for="">Data Nascimento</label>
                        <input type="date" class="form-control" name="data_nascimentoBeneficiario" value="<?= !empty($data_nascimento) ? $data_nascimento : ''; ?>">
                    </span>
                    <span class="col-md-6 col-sm-12">
                        <label for="">Estado Civil</label>
                        <select class="form-select form-select-md" name="estado_civilBeneficiario">
                            <option value=""></option>
                            <option value="S" <?= (!empty($estado_civil) && $estado_civil == 'S') ? 'selected' : ''; ?>>Solteiro</option>
                            <option value="C" <?= (!empty($estado_civil) && $estado_civil == 'C') ? 'selected' : ''; ?>>Casado</option>
                            <option value="V" <?= (!empty($estado_civil) && $estado_civil == 'V') ? 'selected' : ''; ?>>Viúvo</option>

                        </select>
                    </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5 col-xs-12">
                    <label>Telefone:</label>
                    <input type="text" maxlength="15" minlength="15" id="telefone" class="form-control" name="telefoneBeneficiario" value="<?= !empty($telefone) ? $telefone : ''; ?>">                    
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
                    <input type="text" class="form-control" name="cidadeBeneficiario" value="<?= !empty($cidade) ? $cidade : ''; ?>">
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
                        <option value="C" <?= (!empty($estado_civil) && $estado_civil == 'C') ? 'selected' : ''; ?>>Comprada</option>
                        <option value="A" <?= (!empty($estado_civil) && $estado_civil == 'A') ? 'selected' : ''; ?>>Alugada</option>
                        <option value="O" <?= (!empty($estado_civil) && $estado_civil == 'O') ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </span>
            </div>
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-12">
                    <label>Valor do Aluguel + Despesas (Água e luz):</label>
                    <input type="number" class="form-control" name="valor_despesasBeneficiario" value="<?= !empty($valor_despesas) ? $valor_despesas : ''; ?>">
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
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="S" <?= !empty($BeneficioGov) ? 'checked' : ''; ?>>
                                <label for="">Sim</label>                        
                            </div> 
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" name="rbPossuiBenf" value="N" <?= empty($BeneficioGov) ? 'checked' : ''; ?>>
                                <label for="">Não</label>
                            </div>               
                        </div>
                    </span>
                    <span class="col-8">
                        <label for="">Qual o nome do Benefício?</label>
                        <select name="beneficioBeneficiario" class="form-select form-select-md">
                            <option value=""></option>
                            <option value="Aposentadoria" <?php if(!empty($BeneficioGov) && $BeneficioGov == "Aposentadoria") echo "selected" ?>>Aposentadoria</option>
                            <option value="Benefício de Prestação Continuada (BPC)" <?php if(!empty($BeneficioGov) && $BeneficioGov == "Benefício de Prestação Continuada (BPC)") echo "selected" ?>>Benefício de Prestação Continuada (BPC)</option>
                            <option value="Novo Bolsa Família" <?php if(!empty($BeneficioGov) && $BeneficioGov == "Novo Bolsa Família") echo "selected" ?>>Bolsa Família</option>
                            <option value="Vale-gas" <?php if(!empty($BeneficioGov) && $BeneficioGov == "Vale-gas") echo "selected" ?>>Vale Gás</option>
                            <option value="Outros" <?php if(!empty($BeneficioGov) && $BeneficioGov == "Outros") echo "selected" ?>>Outros</option>                            

                        </select>                   
                    </span> 
                </div>           
                <span class="col-lg-4 ">
                    <label for="">Valor</label>
                    <input type="number" class="form-control" name="valor_benecicioBeneficiario" value="<?= !empty($nome_Beneficiario) ? $nome_Beneficiario : ''; ?>">
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
                    <input type="text" class="form-control" name="nome_doencaBeneficiario" value="<?= !empty($comorbidade) ? $comorbidade : ''; ?>">
                </span> 
            </div> 
                <div class="d-flex flex-row justify-content-between container formularios_Beneficiario mt-3">        
                <span class="col-4">
                    <label for="">Possui Dependentes?</label>                    
                    <div class="d-flex container justify-content-start p-0">
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="S" <?= !empty($quantos_dependentes) && $quantos_dependentes > 0 ? 'checked' : ''; ?>>
                            <label for="depSim">Sim</label>                        
                        </div>                         
                        <div class="form-check col-6">
                            <input type="radio" class="form-check-input" name="rbPossuiDependentes" value="N" <?= !empty($quantos_dependentes) && $quantos_dependentes <= 0 ? 'checked' : ''; ?>>
                            <label for="depNao">Não</label>
                        </div>               
                    </div>
                </span> 
                <span class="col-lg-7  col-sm-6">
                    <label for="">Quantos?</label>
                    <input type="number" class="form-control" name="quantos_dependentes" value="<?= !empty($quantos_dependentes) ? $quantos_dependentes : ''; ?>">
                </span> 
            </div>                                    
            <div class="d-flex justify-content-between mt-3 container formularios_Beneficiario">
                <span class="col-lg-5  col-xs-12">
                    <!-- ?????????????????????????????????????????????????????????????????????????????????????????????? -->
                    <label>Quantos trabalham em casa:</label>
                    <input type="number" class="form-control" name="quantos_trabalhamBeneficiario" value="<?php if(isset($_POST['quantos_trabalhamBeneficiario']) && !$cadastrado) echo $_POST['quantos_trabalhamBeneficiario']; ?>">
                </span>
                <span class="col-lg-6  col-xs-12">
                    <label>Renda Familiar total:</label>
                    <input type="number" class="form-control" name="renda_familiarBeneficiario" value="<?= !empty($renda_familiar) ? $renda_familiar : ''; ?>">
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
