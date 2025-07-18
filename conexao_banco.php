<!-- Host: sql10.freesqldatabase.com
Database name: sql10786757
Database user: sql10786757
Database password: jiifSn3xIm
Port number: 3306 -->
<?php
// BANCO EM NUVEM
    $dbHost = 'sql10.freesqldatabase.com';
    $dbUser = 'sql10786757';
    $dbPassword = 'jiifSn3xIm';
    $dbName = 'sql10786757';

    // BANCO LUCAS
    // $dbHost = '127.0.0.1';
    // $dbUser = 'root';
    // $dbPassword = 'flamengo';
    // $dbName = 'caminho_solidario';

    // BANCO LOCAL
    // $dbHost = 'localhost';
    // $dbUser = 'root';
    // $dbPassword = '';
    // $dbName = 'caminho_solidario';

    
    $conexao = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

    //    if($conexao->connect_errno){
    //        echo "Erro";
    //    } else {
    //        echo "Conectado com sucesso";
    //    }
?>

