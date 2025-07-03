<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu senha</title>
    <link rel="stylesheet" href="../css/index.css">

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body id="sem_tema">    
<div class="container_login d-flex align-items-center justify-content-center flex-column">
        <img src="../img/logo_nomeembaixo.png" alt="" class="mb-5">
        <h3>ESQUECEU A SENHA ?</h3>
        <form class="d-block p-3 form_login">
            <div class="form-group d-flex flex-column mt-3">
                <label for=""><input type="text" placeholder="CPF:" class="form-control border-primary text-dark w-100" name="etCpf"></label>
                <label for=""><input type="text" placeholder="Email:" class="form-control border-primary text-dark w-100" name="etEmail"></label>
            </div>
           <button class="btn btn-primary btn-block btn-lg mb-3">Enviar email</button>
           <a href="login.php">
            <ion-icon name="arrow-back-circle-outline" id="btVoltar"></ion-icon>
           </a>           
        </form>
    </div>

    <div id="rodapeI">
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!-- IONICONS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
