<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
$.tablesorter.addParser({
    // set a unique id 
    id: 'myDateFormat',
    is: function(s) {
        return false;
    },
    format: function(s) {
        var date = s.split('/');
        return new Date(date[2], date[1], date[0]).getTime();
    },
    type: 'numeric'
});


$(function() {
   $('.tablesorter').tablesorter({
        theme: 'blue',
        // These are detected by default,
        // but you can change or disable them
        headers: {
            3: { sorter: "myDateFormat" }
            
        },
        widgets: ["zebra", "filter"],
//        
	});
});
</script>
<script type="text/javascript">
function Nova()
{
location.href="index.php?var=7";
}
</script>
<div id="report">
    <?php
    session_start();

    $matriculaava = $_SESSION['matriculaava'];
    $nomfunc = $_SESSION['nomfunc'];
    $titcar = $_SESSION['titcar'];
    $nomloc = $_SESSION['nomloc'];
    $nomche = $_SESSION['nomche'];
    $mediafun = $_SESSION['mediafun'];
    $empresaava = $_SESSION['empresaava'];
    $tipava = $_SESSION['tipava'];
    $apeemp = $_SESSION['apeemp'];
    $sigemp = $_SESSION['sigemp'];
    $datava = $_SESSION['datava'];
    
    require'conexao.php';
    $conexao = Conectar();
    
    $qrav = "select usu_nomava from usu_tipava where usu_codava = $tipava";
    $reav = oci_parse($conexao, $qrav) or die ("erro");
    oci_define_by_name($reav, 'USU_NOMAVA', $nomava);
    oci_execute($reav);
    oci_fetch($reav);
    print '<div id="titulo">';    
    print '<ul>';
    print '<li><b>Avaliação: ' . $nomava . '</b></li>';
    print '<li><b>Avaliado:</b> ' . $matriculaava . ' - ' . $nomfunc . '</li>';
    print '<li><b>Cargo:</b> ' . $titcar . '</li>';
    print '<li><b>Departamento:</b> ' . utf8_encode($nomloc) . '</li>';
    print '<li><b>Empresa:</b> '. $sigemp .' - '. utf8_encode($apeemp) . '</li>';
    print '<li><b>Data:</b> ' . $datava . '</li>';
    print '<li><b>Avaliador:</b> ' . $nomche . '</li>';
    print '<li><b>Media Avaliado:</b> ' . str_replace('.',',',round(str_replace(',','.',$mediafun),2)) . '<li>';
    print '</ul>';
    print '<br></br>';
    print '<br></br>';
    print '</div>';    
    print '<div id="outro">';    
    // CONSULTA PROCESSO
    $proc ="select n.nrodoc, n.notfic, n.datnot
           from vetorh.r038not n
           ,    vetorh.r034fun r
                        where n.numcad = r.numcad
                        and   n.numemp = r.numemp
                        and   n.numemp = $empresaava
                        and   n.numcad = $matriculaava
                        and   n.tipnot in (15,16)";
    $rproc = oci_parse($conexao, $proc) or die ("erro");
    oci_execute($rproc);
    $vproc = oci_fetch_all($rproc, $x);
           
    if ($vproc > 0) {
        $rproc = oci_parse($conexao, $proc) or die ("erro");
        oci_execute($rproc);
        print'<div>';
        print'<TABLE class="processo">';
        print'<TR>';
        print'<Th>Tipo de Processo</TD> ';
        print'<Th>Observação do Processo</TD> ';
        print'<Th>Data do Processo</TD>';
        print'</TR>';

            while ($rowproc = oci_fetch_array($rproc, OCI_ASSOC+OCI_RETURN_NULLS)) {
                print'<TR>';
                print'<TD><label> ' . $rowproc['NRODOC'] . '</label></TD>';
                print'<TD><label> ' . $rowproc['NOTFIC'] . '</label></TD>';
                print'<TD><label> ' . $rowproc['DATNOT'] . '</label></TD>';
                print'</TR>';					
            }
        print'</TABLE>';				
        print'</div>';
        //print '<br></br>';
    } else {
        print '<div class="alerta"><h4><b>Funcionário não possui processo.</b><h4></div>';
    }
    // CONSULTA ADVERTENCIA
    $qrdad = "select n.nrodoc, n.notfic, n.datnot
            from vetorh.r038not n, vetorh.r034fun r
            where n.numcad = r.numcad
            and   n.numemp = r.numemp
            and   n.numemp = $empresaava
            and   n.numcad = $matriculaava
            and   n.tipnot = 2";
    $read = oci_parse($conexao, $qrdad ) or die ("erro");
    oci_execute($read);
    $vadv = oci_fetch_all($read, $z);
           
    if ($vadv > 0) {
        $read = oci_parse($conexao, $qrdad) or die ("erro");
        oci_execute($read);
    print'<div>';
    print'<TABLE class="advertencia">';
    print'<TR>';
    print'<Th>Tipo da advertencia</TD> ';
    print'<Th>Descrição da advertencia</TD> ';
    print'<Th>Data da advertencia</TD>';
    print'</TR>';

        while ($rowadv = oci_fetch_array($read, OCI_ASSOC)) {
            print'<TR>';
            print'<TD><label> ' . $rowadv['NRODOC'] . '</label></TD>';
            print'<TD><label> ' . $rowadv['NOTFIC'] . '</label></TD>';
            print'<TD><label> ' . $rowadv['DATNOT'] . '</label></TD>';
            print'</TR>';					
        }

    print'</TABLE>';				
    print'</div>';
    //print '<br></br>';
    } else {
        print '<div class="alerta"><h4><b>Funcionário não possui advertência.</b><h4></div>';
    }
//    print '<br></br>';
    // CONSULTA PONTOS FORTES E FRACOS E POSSIBILIDADE DE RETORNO
    $qrpont = "select usu_ponfor Pon_For, usu_ponfra Pon_Fra, usu_motivo Motivo, usu_avaliador Aval,
                case usu_havret when 0 then 'Não' when 1 then 'Sim' when 2 then 'Sim c/ Restrição' end as Hav_Ret
                from usu_rpespto
                where usu_empfun = $empresaava
                and usu_cadfun = $matriculaava
                and usu_codava = $tipava";

    $repont = oci_parse($conexao, $qrpont) or die ("erro");
    oci_define_by_name($repont, 'PON_FOR', 	$ponfor);
    oci_define_by_name($repont, 'PON_FRA', 	$ponfra);
    oci_define_by_name($repont, 'HAV_RET', 	$havret);
    oci_define_by_name($repont, 'MOTIVO', 	$avaliador);
    oci_define_by_name($repont, 'AVAL', 	$motivo);
    oci_execute($repont);
    oci_fetch($repont);
    
    print '<div class="pontos">';
    print '<label><b>Pontos fortes:</b> ' . $ponfor.'</label>';
    print '<label><b>Pontos fracos:</b> ' . $ponfra.'</label>';
    print '</div>';
    print '<div class="pontos">';
    print '<label><b>Avaliador:</b> ' . $avaliador.'</label>';
    print '<label><b>Motivo:</b> ' . $motivo.'</label>';
    print '</div>';
    
    
    if ( $havret != "" ) {
        print'<div class="field">';
        print'<label><b>Possibilidade de retorno:</b> '. $havret .'</label>';
        print'</div>';
    }
    print '<br></br>';
    print '</div>';
    //CONSULTA NOTAS - DADOS DA TABELA
    $qr = "select r.usu_codite Codigo_item, 
                        t.usu_titite Titulo_Item, 
                        case r.usu_notite 
                        when 1 then 'Insatisfatório' 
                        when 2 then 'Regular' 
                        when 3 then 'Bom' 
                        when 4 then 'Ótimo' end as Avaliacao
                                from usu_rpesres r
                                   , usu_rpestab t
                                   , r034fun     c
                                   , r034fun     f
                                where r.usu_empche      = c.numemp
                                  and r.usu_cadche      = c.numcad
                                  and r.usu_empfun      = f.numemp
                                  and r.usu_cadfun      = f.numcad
                                  and r.usu_codpes      = t.usu_codpes
                                  and r.usu_codite      = t.usu_codite
                                  and r.usu_empfun      = $empresaava
                                  and r.usu_cadfun      = $matriculaava
                                  and r.usu_codava      = $tipava
                                order by r.usu_codite";

    $re = oci_parse($conexao, $qr) or die("erro");
    oci_execute($re);
    print '<table class="tablesorter">';
    print '<thead>';
    print '<tr>';
//    print '<th>Empresa Avaliador</th>';
//    print '<th>Nome Emp Avaliador</th>';
//    print '<th>Nom Emp Avaliado</th>';
//    print '<th>Data</th>';
//    print '<th>Categoria</th>';
    print '<th WIDTH=100>Nº da Questão</th>';
    print '<th>Título da Questão</th>';
    print '<th WIDTH=100>Avaliação da Questão</th>';
    print '</tr>';
    print '</thead>';
//    print '<tfoot>';
//    print '<tr>';
//    print '<th>Empresa Avaliador</th>';
//    print '<th>Nome Emp Avaliador</th>';
//    print '<th>Nom Emp Avaliado</th>';
//    print '<th>Data</th>';
//    print '<th>Tipo de Pesquisa</th>';
//    print '<th>Nº da Questão</th>';
//    print '<th>Título da Questão</th>';
//    print '<th>Avaliação da Questão</th>';
//    print '</tr>';
//    print '</tfoot>';
    print '<tbody>';

    while ($row = oci_fetch_array($re, OCI_ASSOC)) {
        print'<TR>';
//        print'<TD>' . $row['EMPRESA_CHEFE'] . '</TD>';
//        print'<TD>' . $row['NOMEMPCHE'] . '</TD>';
//        print'<TD>' . $row['NOMEMPFUN'] . '</TD>';
//        print'<TD>' . $row['DATA_AVA'] . '</TD>';
//        print'<TD>' . $row['PESQUISA'] . '</TD>';
        print'<TD WIDTH=100>' . $row['CODIGO_ITEM'] . '</TD>';
        print'<TD>' . utf8_encode($row['TITULO_ITEM']) . '</TD>';
        print'<TD WIDTH=100>' . $row['AVALIACAO'] . '</TD>';
        print'</TR>';
    }
    print '</tbody>';
    print '</table>';
    print '<br></br>';
    print '<div id="btn">';
    $_SESSION['nomche'] = $nomche;
    print '<input type="button" value="Voltar" class="send" onClick="Nova()"  />';
    print '</div>';
    print '</div>';    
 ?>
</div>
<br></br>