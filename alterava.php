<div id="centro">
<?php
    session_start();

    require'conexao.php';

    $conexao = Conectar();
    
    
    $empresa = $_SESSION['empresa']; 
    $matricula = $_SESSION['matricula'];
    $numfunc = $_SESSION['numfunc'];
    $empfunc = $_SESSION['empfunc'];
    $nomfunc = $_SESSION['nomfunc'];
    $titcar = $_SESSION['titcar'];
    $nomloc = utf8_encode($_SESSION['nomloc']);
    $idava = $_SESSION['idava'];
    $nomche = $_SESSION['nomche'];
    $tipava = $_SESSION['tipava'];
    $i = 1;
    
    $qrtip = "select c.siscar as vcheck1 from vetorh.r034fun f, vetorh.r024car c
                where f.estcar = c.estcar
		and f.codcar = c.codcar
		and f.numemp = $empfunc
		and f.tipcol = 1
		and f.numcad = $numfunc";
    
    $contip = oci_parse($conexao, $qrtip) or die ("erro");
    oci_define_by_name($contip, 'VCHECK1', $catpes);
    oci_execute($contip);
    oci_fetch($contip);
    
    switch ($catpes) {
        case 1:
            $catpesnom = 'de Liderança';
            break;
        case 2:
            $catpesnom = 'Administrativa';
            break;
        case 3:
            $catpesnom = 'Operacional';
            break;
    }
   print '<div id="titulo">';   
        print '<ul>';
        print '<li><b>Avaliação '.$catpesnom.'</b></li>';        
        print '<li><b>Avaliado:</b> '.$numfunc.' - '.$nomfunc.'</li>';
        print '<li><b>Cargo:</b> '.$titcar.'</li>';
        print '<li><b>Departamento:</b> '.$nomloc.'</li>';
        print '<li><b>Data:</b> '.date('d/m/Y').'</li>';
        print '<li><b>Avaliador:</b> '.$nomche.'</li>'; 
        print '</ul>';
        print '<br></br>';
    print '</div>';
      
    
    $qrperg= "select c.siscar as CATPES from vetorh.r034fun f, vetorh.r024car c
                where f.estcar = c.estcar
		and f.codcar = c.codcar
		and concat(f.numemp,f.numcad) = $idava
		and f.tipcol = 1";
    
    $reperg = oci_parse($conexao, $qrperg ) or die ("erro");
    oci_execute($reperg);
    
    $qrpont = "select usu_ponfor Pon_For, usu_ponfra Pon_Fra, usu_avaliador Aval, usu_motivo Motivo,
                usu_havret as Hav_Ret
                from usu_rpespto
                where usu_empfun = $empfunc
                and usu_cadfun = $numfunc
                and usu_codava = $tipava";

    $repont = oci_parse($conexao, $qrpont) or die ("erro");
    oci_define_by_name($repont, 'PON_FOR', 	$ponfor);
    oci_define_by_name($repont, 'PON_FRA', 	$ponfra);
    oci_define_by_name($repont, 'HAV_RET', 	$havret);
    oci_define_by_name($repont, 'AVAL', 	$avaliador);
    oci_define_by_name($repont, 'MOTIVO', 	$motivo);
    oci_execute($repont);
    oci_fetch($repont);
    
    $qrnota = "SELECT r.usu_codite Codigo_item,
  t.usu_titite Titulo_Item,
  t.USU_DESITE AS DESITE,
  r.usu_notite AS Avaliacao
FROM usu_rpesres r ,
  usu_rpestab t ,
  r034fun c ,
  r034fun f
WHERE r.usu_empche = c.numemp
AND r.usu_cadche   = c.numcad
AND r.usu_empfun   = f.numemp
AND r.usu_cadfun   = f.numcad
AND r.usu_codpes   = t.usu_codpes
AND r.usu_codite   = t.usu_codite
AND r.usu_empfun   = $empfunc
AND r.usu_cadfun   = $numfunc
AND r.usu_codava   = $tipava
ORDER BY r.usu_codite";
   
 $renota = oci_parse($conexao, $qrnota) or die("erro");
 oci_execute($renota);
    
?>
<script src="js/jquery-1.9.0.js" type="text/javascript"> </script>
<script type="text/javascript">
	$(document).ready(function(){
		//Quando 'btnEntrar' for clicado
                $("#btnEntrar").click(function(){
			//Envia por POST para a página login.php: usuario = valor da textbox usuario
			//e senha = valor da textbox senha (pegando valores pelo ID)
                        function loading_show(){
                        $('#loading').html("<img src='img/ajax_loader_blue_32.gif'/>").fadeIn('fast');
                        }

                        //Aqui desativa a imagem de loading
                        function loading_hide(){
                            $('#loading').fadeOut('fast');
                        }  
                        
                        loading_show();
                        var envio = $.post("altgrava.php", { 
			catpes: $("#catpes").val(),
                        tipava: $("#tipava").val(),
			empche: $("#empche").val(),
                        matche: $("#matche").val(),
                        empfunc: $("#empfunc").val(),
                        matfunc: $("#matfunc").val(),
                        ptnforte: $("#ptnforte").val(),
                        ptnfraco: $("#ptnfraco").val(),
                        avaliador: $("#avaliador").val(),
                        motivo: $("#motivo").val(),
                        retorno: $('input[name="retorno"]:checked').val(),
                        q1:$('input[name="q1"]:checked').val(),
                        q2: $('input[name="q2"]:checked').val(),
                        q3: $('input[name="q3"]:checked').val(),
                        q4: $('input[name="q4"]:checked').val(),
                        q5: $('input[name="q5"]:checked').val(),
                        q6: $('input[name="q6"]:checked').val(),
                        q7: $('input[name="q7"]:checked').val(),
                        q8: $('input[name="q8"]:checked').val(),
                        q9: $('input[name="q9"]:checked').val(),
                        q10:$('input[name="q10"]:checked').val()
			});
			//Se achou a página, exiba o resultado no elemento com ID resultado
                        
			envio.done(function(data) {
				$("#resultado").html(data);
                                loading_hide();
			});
			//Se envio falhar
			envio.fail(function() { alert("Erro na requisição"); })	;
		});
	});
</script>
<div id="resultado">
<!-- Essa div irá receber todos os resultados --> 
</div>
<div id="formulario" class="form-group">
        <form action="" name="" method="post" onSubmit="">
            </br>
            <div>
                <?php
                    while ($row = oci_fetch_array ($renota, OCI_ASSOC)) {
                        print '<div class="pergunta">';
                        print '<h4> ' .$row['CODIGO_ITEM'] . ' - ' .utf8_encode($row['TITULO_ITEM']) .'</h4>';                        
                        print '</div>';
                        print '<div id="resposta">';
                        if ($row['AVALIACAO'] == 4) {
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="4" checked="" title="4"/>ÓTIMO</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="3" title="3"/>BOM</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="2" title="2"/>REGULAR</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="1" title="1"/>INSATISFATÓRIO</div>';
                        } elseif ($row['AVALIACAO'] == 3) {
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="4" title="4"/>ÓTIMO</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="3" checked="" title="3"/>BOM</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="2" title="2"/>REGULAR</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="1" title="1"/>INSATISFATÓRIO</div>';
                        } elseif ($row['AVALIACAO'] == 2) {
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="4" title="4"/>ÓTIMO</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="3" title="3"/>BOM</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="2" checked="" title="2"/>REGULAR</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="1" title="1"/>INSATISFATÓRIO</div>';
                        } elseif ($row['AVALIACAO'] == 1) {
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="4" title="4"/>ÓTIMO</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="3" title="3"/>BOM</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="2" title="2"/>REGULAR</div>';
                            print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="1" checked="" title="1"/>INSATISFATÓRIO</div>';
                        }
                        print '</div>';
                        print '<div><label class="pergdes">' .utf8_encode($row['DESITE']) . '</label></div>';
                        print '<br></br>';                        
                        $i++;
                    }
                ?>
            <div id="pnto3">
            	<label>Avaliador:</label>            
                <input type="text" id="avaliador" value="<?php echo $avaliador; ?>" required class="form-control" />
            </div>
            <div id="pnto">
            	<label>Pontos Fortes:</label>            
                <input type="text" id="ptnforte" value="<?php echo $ponfor; ?>" required class="form-control"/>
            </div>
            <div id="pnto">
            	<label>Pontos Fracos:</label>            
                <input type="text" id="ptnfraco" value="<?php echo $ponfra; ?>" required class="form-control"/>
            </div>
            <br></br>            
            <?php
            if (isset($havret)) {
                print '<div id="pnto2">';
            	print '<label>Existe possibilidade de retorno:</label>';
                print '<div id="resposta2">';
                    if ($havret == 0){
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="1"/>SIM</div>';
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="0" checked="" title="4"/>NÃO</div>';
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="2"/>SIM c/ Restrição</div>';
                    }
                    if ($havret == 1) {
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="1" checked=""  title="4"/>SIM</div>';
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="0"/>NÃO</div>';
                        print '<div class="item"><input type="radio" name="retorno" id="retorno" value="2"/>SIM c/ Restrição</div>';
                    } 
                    if ($havret == 2) {
                    	print '<div class="item"><input type="radio" name="retorno" id="retorno" value="1" />SIM</div>';
                    	print '<div class="item"><input type="radio" name="retorno" id="retorno" value="0"/>NÃO</div>';
                    	print '<div class="item"><input type="radio" name="retorno" id="retorno" value="2" checked="" title="4"/>SIM c/ Restrição</div>';
                    }
            print '</div>';
            print '</div>';
            print '<div id="pnto2"><label>Motivo Desligamento:</label>';
            print '<input type="text" id="motivo" required value="'.$motivo.'" class="form-control"/></div>';
            print '<br></br>';
            print '<br></br>';
            }
            ?>
                <input type="hidden" id="catpes" value="<?php echo $catpes ?>"/>
                <input type="hidden" id="tipava" value="<?php echo $tipava ?>"/>
                <input type="hidden" id="empche" value="<?php echo $empresa ?>"/>
                <input type="hidden" id="matche" value="<?php echo $matricula ?>"/>
                <input type="hidden" id="empfunc" value="<?php echo $empfunc ?>"/>
                <input type="hidden" id="matfunc" value="<?php echo $numfunc ?>"/>
            <div id="pnto2">
                <input type="button" id="btnEntrar" value="Alterar" class="send"/>            
                <input type="button" id="" value="Voltar" class="send" onClick="history.go(-1)"/>
                <div id="loading"></div>
            </div>            
            <br></br>     
        </form>
</div>
</div>


