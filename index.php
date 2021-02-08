<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Avaliação de Desepenho</title>
<link rel="stylesheet" type="text/css" href="css/stilo.css"/>
<script type="text/javascript" src="js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="js/jquery-1.11.2.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link href='css/bootstrap.css' rel='stylesheet' type='text/css'/>
</head>
<?php 
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    session_start();
    ini_set('session.cache_expire', 60);
    ini_set('session.cookie_httponly', true);
    ini_set('session.use_only_cookie', true);  
?>
<body>
<div id="geral">
<div id="cabecalho">
    <img src="Img/logo.png"/>
    <h2>Avaliação de Desempenho</h2>
</div>
<div id="conteiner">
    <?php
        $var = $_GET["var"];
        if (!isset($var)) {
          $var = 0;
        }
        switch ($var){
            case 0:
                include 'home.php';
                break;
            case 1:
                include 'inicio.php';
                break;
            case 2:
                include 'conavap.php';
            break;
            case 3:
                include 'avapadrao.php';
            break;
            case 4:
                include 'reportind2.php';
            break;
//            case 5:
//                include 'avadem.php';
//            break;
            case 6:
                include 'menurelatorios.php';
            break;
            case 7:
                include 'report.php';
            break;
            case 8:
                include 'individual.php';
            break;
            case 9:
                include 'alterava.php';
            break;
            case 10:
                include 'reportind.php';
            break;
//            case 11:
//                include 'reportemp.php';
//            break;
        }
        
    ?>
</div>
<div id="rodape">
    <small>
    <p>Copyright 2015 - Grupo Farias TIC</p>
    </small>
</div>
</div>
</body>
</html>