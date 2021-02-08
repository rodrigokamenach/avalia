<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
$.tablesorter.addParser({
        id: "fancyNumber",
        is: function(s) {
            return /^[0-9]?[0-9,\.]*$/.test(s);
        },
        format: function(s) {
            return $.tablesorter.formatFloat(s.replace(/,/g, ''));
        },
        type: "numeric"
    });

$(function() {
    
   $('.tablesorter').tablesorter({
        widgets: ["zebra","filter"],
        sortList: [[3,0], [4,0]],
        cssChildRow: "tablesorter-childRow",
        
        // These are detected by default,
        // but you can change or disable them
        headers: {
            7: { sorter: "fancyNumber" }
        },
        widgetOptions: {
            // filter_anyMatch replaced! Instead use the filter_external option
            // Set to use a jQuery selector (or jQuery object) pointing to the
            // external filter (column specific or any match)
            filter_external : '.search',
            // add a default type search to the first name column
            filter_defaultFilter: { 1 : '~{query}' },
            // include column filters
            filter_columnFilters: false,
            filter_placeholder: { search : 'Search...' },
            filter_saveFilters : true           
        }         
       	})
        .tablesorterPager({
            container: $(".pager"),
            updateArrows: true,            
            page: 0,           
            size: 10,
            savePages : true,
            removeRows: false,            
            fixedHeight: false,            
            cssNext: '.next', // next page arrow
            cssPrev: '.prev', // previous page arrow
            cssFirst: '.first', // go to first page arrow
            cssLast: '.last', // go to last page arrow
            cssGoto: '.gotoPage', // select dropdown to allow choosing a page
            cssPageDisplay: '.pagedisplay', // location of where the "output" is displayed
            cssPageSize: '.pagesize'
    });
    $('.tablesorter').trigger('pageSize', 10);
    $('.tablesorter-childRow td').hide();

    $('.tablesorter').delegate('.toggle', 'click' ,function(){
    $(this).closest('tr').nextUntil('tr:not(.tablesorter-childRow)').find('td').toggle();
    return false;
    });
});
</script>
<script type="text/javascript">
//$(function() {
//    $(".tablesorter")
//        
//});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
});
</script>
<script type="text/javascript">
function Nova()
{
location.href="index.php?var=6";
}
</script>
<?php	
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
require'conexao.php';
$conexao = Conectar();
$empresa = $_SESSION["empresa"];
$matricula = $_SESSION["matricula"];
$nomfun = $_SESSION['nomche'];
$t_mesano = date("d/m/Y");
$qrm = "select round(avg(usu_notite),2) media_funci 
from usu_rpesres 
where usu_cadche= $matricula";
$rem = oci_parse($conexao, $qrm ) or die ("erro");
oci_define_by_name($rem, 'MEDIA_FUNCI', $mediafun);
oci_execute($rem);
oci_fetch($rem);
$msg .="<div id='report2'>";
$msg .='<div id="titulo">';
$msg .='<h5><b>Avaliador:</b> '.$matricula.' - '.$nomfun.'</h5>';
$msg .="<h5><b>Media Avaliador:</b>". str_replace('.',',',round(str_replace(',','.',$mediafun),2))."</h5>";
$msg .="</div>";
$msg .="<br>";
//começamos a concatenar nossa tabela
$msg .='<div class="proc">Pesquisa: <input class="search" type="search" data-column="all"></div>';
$msg .='<div id="pager" class="pager">';
$msg .="<form>";
$msg .="<img src='img/first.gif' class='first'/>";
$msg .="<img src='img/prev.gif' class='prev'/>";
$msg .='<input type="text" class="pagedisplay"/>';
$msg .='<img src="img/next.gif" class="next"/>';
$msg .='<img src="img/last.gif" class="last"/>';
$msg .='<select class="pagesize">';
$msg .='<option selected="selected" value="10">10</option>';
$msg .='<option value="20">20</option>';
$msg .='<option value="30">30</option>';
$msg .='<option value="40">40</option>';
$msg .='</select>';
$msg .='</form>';
$msg .='</div>';
$msg .="<table class='tablesorter'>";
$msg .="<thead>";
$msg .="<tr>";
$msg .="        <th>Tipo de Avaliação</th>";
//$msg .="        <th>Empresa Avaliador</th>";
//$msg .="        <th>Nome Emp Avaliador</th>";
//$msg .="        <th>Matricula Avaliador</th>";
//$msg .="        <th>Nome Avaliador</th>";
$msg .="        <th>Empresa Avaliado</th>";
$msg .="        <th>Nome Emp Avaliado</th>";
$msg .="        <th>Matricula Avaliado</th>";
$msg .="        <th>Nome Avaliado</th>";
$msg .="        <th>Data</th>";
$msg .="        <th>Categoria</th>";
$msg .="        <th>Avaliação da Questão</th>";
$msg .="    </tr>";
$msg .="</thead>";
//$msg .="<tfoot>";
//$msg .="<tr>";
//$msg .="        <th>Empresa Avaliador</th>";
//$msg .="        <th>Nome Emp Avaliador</th>";
//$msg .="        <th>Matricula Avaliador</th>";
//$msg .="        <th>Nome Avaliador</th>";
//$msg .="        <th>Empresa Avaliado</th>";
//$msg .="        <th>Nome Emp Avaliado</th>";
//$msg .="        <th>Matricula Avaliado</th>";
//$msg .="        <th>Nome Avaliado</th>";
//$msg .="        <th>Data</th>";
//$msg .="        <th>Tipo de Pesquisa</th>";
//$msg .="        <th>Avaliação da Questão</th>";
//$msg .="    </tr>";
//$msg .="</tfoot>";
$msg .="<tbody>";

$qr = "SELECT i.usu_codava,
  i.usu_nomava,
  i.empresa_chefe,
  i.nomempche,
  i.matricula_chefe,
  i.nome_chefe,
  i.empresa_colaborador,
  i.nomempfun,
  i.matricula_colaborador,
  i.nome_colaborador,
  i.data_ava,
  i.pesquisa,
  round(AVG(i.avaliacao),2) AS avaliacao
FROM
  (SELECT g.usu_codava,
    g.usu_nomava,
    c.numemp Empresa_Chefe,
    CASE c.numemp
      WHEN 23
      THEN 'Coorporativo'
      WHEN 1
      THEN 'UAN'
      WHEN 3
      THEN 'UAV'
      WHEN 4
      THEN 'UIT'
      WHEN 5
      THEN 'UTP'
      WHEN 6
      THEN 'USJ'
      WHEN 7
      THEN 'UPE'
      WHEN 8
      THEN 'TASA'
      WHEN 10
      THEN 'UBF'
    END AS NomEmpChe,
    c.numcad Matricula_Chefe,
    c.nomfun Nome_Chefe,
    f.numemp Empresa_colaborador,
    CASE c.numemp
      WHEN 23
      THEN 'Coorporativo'
      WHEN 1
      THEN 'UAN'
      WHEN 3
      THEN 'UAV'
      WHEN 4
      THEN 'UIT'
      WHEN 5
      THEN 'UTP'
      WHEN 6
      THEN 'USJ'
      WHEN 7
      THEN 'UPE'
      WHEN 8
      THEN 'TASA'
      WHEN 10
      THEN 'UBF'
    END AS NomEmpFun,
    f.numcad Matricula_Colaborador,
    f.nomfun Nome_Colaborador,
    r.usu_mesano Data_Ava,
    CASE r.usu_codpes
      WHEN 1
      THEN 'Liderança'
      WHEN 2
      THEN 'Administrativo'
      WHEN 3
      THEN 'Operacional'
    END          AS Pesquisa,
    r.usu_notite AS Avaliacao
  FROM usu_rpesres r,
    usu_rpestab t,
    r034fun c,
    r034fun f,
    usu_tipava g
  WHERE r.usu_empche = c.numemp
  AND r.usu_cadche   = c.numcad
  AND r.usu_empfun   = f.numemp
  AND r.usu_cadfun   = f.numcad
  AND r.usu_codpes   = t.usu_codpes
  AND r.usu_codite   = t.usu_codite
  AND r.usu_empche   = $empresa
  AND r.usu_cadche   = $matricula
  AND r.usu_codava   = g.usu_codava
  ) i
GROUP BY i.usu_codava,
  i.usu_nomava,
  i.empresa_chefe,
  i.nomempche,
  i.matricula_chefe,
  i.nome_chefe,
  i.empresa_colaborador,
  i.nomempfun,
  i.matricula_colaborador,
  i.nome_colaborador,
  i.data_ava,
  i.pesquisa";
$re = oci_parse($conexao, $qr) or die("erro");
$r = oci_execute($re);

if ($r) {
    while ($res = oci_fetch_array($re, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $msg .='<TR class="clickable-row" data-href="detailind.php?empresaava='.$res['EMPRESA_COLABORADOR'].'&matriculaava='.$res['MATRICULA_COLABORADOR'].'&empresa='.$res['EMPRESA_CHEFE'].'&matricula='.$res['MATRICULA_CHEFE'].'&tipava='.$res['USU_CODAVA'].'">';
        $msg .='<TD>'.$res['USU_NOMAVA'].'</TD>';
        //$msg .='<TD>'.$res['EMPRESA_CHEFE'].'</TD>';
        //$msg .='<TD>'.$res['NOMEMPCHE'].'</TD>';
        //$msg .='<TD>'.$res['MATRICULA_CHEFE'].'</TD>';
        //$msg .='<TD>'.utf8_encode($res['NOME_CHEFE']).'</TD>';
        $msg .='<TD>'.$res['EMPRESA_COLABORADOR'].'</TD>';
        $msg .='<TD>'.$res['NOMEMPFUN'].'</TD>';
        $msg .='<TD>'.$res['MATRICULA_COLABORADOR'].'</TD>';
        $msg .='<td>'.utf8_encode($res['NOME_COLABORADOR']).'</TD>';
        $msg .='<TD>'.$res['DATA_AVA'].'</TD>';
        $msg .='<TD>'.utf8_encode($res['PESQUISA']).'</TD>';
        $msg .='<TD>'.$res['AVALIACAO'].'</TD>';
        $msg .='</TR>';
        
//    $qrn = "SELECT r.usu_codite Codigo_item, t.usu_titite Titulo_Item, CASE r.usu_notite WHEN 1 THEN 'Insatisfatório' WHEN 2 THEN 'Regular' WHEN 3 THEN 'Bom' WHEN 4 THEN 'Ótimo' END AS Avaliacao
//            FROM usu_rpesres r ,usu_rpestab t ,r034fun c ,r034fun f
//            WHERE r.usu_empche = c.numemp AND r.usu_cadche   = c.numcad AND r.usu_empfun   = f.numemp AND r.usu_cadfun   = f.numcad
//            AND r.usu_codpes   = t.usu_codpes AND r.usu_codite   = t.usu_codite AND r.usu_empfun = ".$res['EMPRESA_COLABORADOR']."
//            AND r.usu_cadfun   = ".$res['MATRICULA_COLABORADOR']." and r.usu_codava = ".$res['USU_CODAVA']." ORDER BY r.usu_codite";
//        $redt = oci_parse($conexao, $qrn) or die("erro");
//        $rdt = oci_execute($redt);
//        if (isset($rdt)) {      
//            while ($resdt = oci_fetch_array($redt, OCI_ASSOC+OCI_RETURN_NULLS)) {
//                    $msg .='<tr class="tablesorter-childRow">';
//                    $msg .='<td colspan="3">';
//                    $msg .='<td colspan="1">'.$resdt['CODIGO_ITEM'].'</td>';
//                    $msg .='<td colspan="3">'.utf8_encode($resdt['TITULO_ITEM']).'</td>';
//                    $msg .='<td colspan="2">'.$resdt['AVALIACAO'].'</td>';
//                    $msg .='</tr>';                    
//            }
//        } else {
//            $msg .="<td colspan='11'>Nenhum resultado foi encontrado</td>";                
//        }
    }
} else {    
    $msg .="Nenhum resultado foi encontrado...";
}
$msg .="</tbody>";
$msg .="</table>";
$msg .="<br></br>";
$msg .='<div id="btn">';
$msg .="<input type='button' value='Voltar' id='Voltar' onClick='Nova()' />";
$msg .="</div>";
$msg .="</div>";
$msg .="<br></br>";
//retorna a msg concatenada
echo $msg;
?>