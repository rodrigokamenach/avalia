<script type="text/javascript">
function Volta()
{
location.href="index.php";
}
</script>
<div id="centro">
<?php
    session_start();
    
    $empresa = $_SESSION["empresa"];
    $matricula = $_SESSION["matricula"];
    $cpf = $_SESSION["cpf"];
    
    require 'conexao.php';
    $conexao = Conectar();
    $qrnom = "SELECT
                NOMFUN
                FROM
                vetorh.r034fun 
                WHERE
                numemp   = $empresa
                AND numcad = $matricula
                AND numcpf = $cpf";
    
    $renom = oci_parse($conexao, $qrnom) or die ("Erro na consulta ao banco");
    oci_define_by_name($renom,'NOMFUN',$nomfun);
    oci_execute($renom);
    oci_fetch($renom);
    
       
    if ((!isset($empresa)) and (!isset($matricula)) and (!isset($cpf))) {
        echo "<script> document.location = 'index.php' </script>";
        session_destroy();
    }
     else {
        $_SESSION['empresa'] = $empresa;
        $_SESSION['matricula'] = $matricula;
        $_SESSION['cpf'] = $cpf;
        $_SESSION['nomche'] = $nomfun;
        print '<div id="titulo">';
        print '<h4>Menu Principal</h4>';
        print '<h3><b>Avaliador:</b> '.$nomfun.'</h3>';
        print '</div>';
        print '<div class="cont">';
            print '<div class="relatorio">';
            print    '<a class="button2" href="index.php?var=2">Avaliação</a>';
            print '</div>';
            print '<div class="relatorio">';
            print    '<a class="botao" href="index.php?var=6">Relatórios</a>';
            print '</div>';
        print '</div>';
        print '</br>';
        print '<div>';
        print '<input type="button" value="Voltar" class="send" onClick="Volta()"  />';
        print '</div>';
    }
?>
</div>
