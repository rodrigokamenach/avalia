<div id="centro">
<script type="text/javascript">
function Volta()
{
location.href="index.php?var=1";
}
</script>
<?php
    session_start();
    
    $empresa = $_SESSION["empresa"];
    $matricula = $_SESSION["matricula"];
    $cpf = $_SESSION["cpf"];
    $nomfun = $_SESSION['nomche'];
    
    if ((!isset($empresa)) and (!isset($matricula)) and (!isset($cpf))) {
        echo "<script> document.location = 'index.php' </script>";
        session_destroy();
    }
     else {
        print '<div id="titulo">';
        print '<h4>Menu Relatórios</h4>';
        print '<h3>Avaliador: '.$nomfun.'</h3>';
        print '</div>';
        print '<div class="cont">';
            print '<div class="relatorio2">';
            print    '<a class="botao" href="index.php?var=7" class="link">Relatório Geral</a>';
            print '</div>';
            print '<div class="relatorio2">';
            print    '<a class="botao" href="index.php?var=8" class="link">Relatório Individual</a>';
            print '</div>';
        print '</div>';
        print '<br></br>';
        print '<div>';
        print '<input type="button" value="Voltar" class="send" onClick="Volta()"  />';
        print '</div>';
           
}
?>
</div>

