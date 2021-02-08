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
    echo $idava = $_SESSION['idava'];
    $nomche = $_SESSION['nomche'];
    $tipava = $_SESSION['tipava'];
    $i = 1;
    
    $qrtip = "select c.siscar as CATPES from vetorh.r034fun f, vetorh.r024car c
                where f.estcar = c.estcar
		and f.codcar = c.codcar
		and concat(f.numemp,f.numcad) = $idava
		and f.tipcol = 1";
    //echo $qrtip;
    $contip = oci_parse($conexao, $qrtip) or die ("erro");
    oci_define_by_name($contip, 'CATPES', $catpes);
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
    
    $qrperg= "select usu_codite codite, usu_titite titite, usu_desite desite
                from usu_rpestab
		where usu_codpes = $catpes";
    
    $reperg = oci_parse($conexao, $qrperg ) or die ("erro");
    oci_execute($reperg);        
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
                        var envio = $.post("gravavap.php", { 
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
                        retorno: $("#retorno").val(),
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
<ul id="resultado">         <!-- Essa div irá receber todos os resultados --> 
</ul>
<div id="formulario" class="form-group">
        <form action="" name="" method="post" onSubmit="">                        
                <?php
                    while ($row = oci_fetch_array ($reperg, OCI_ASSOC)) {
                        print '<div class="pergunta">';
                        print '<h4> ' .$row['CODITE'] . ' - ' .utf8_encode($row['TITITE']) .'</h4>';
                        print '</div>';
                        print '<div id="resposta">';
                        print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="4" title="4"/>ÓTIMO</div>';
                        print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="3" title="3"/>BOM</div>';
                        print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="2" title="2"/>REGULAR</div>';
                        print '<div class="item"><input type="radio" name="q'.$i.'" id="q'.$i.'" value="1" title="1"/>INSATISFATÓRIO</div>';                        
                        print '</div>';                        
                        print '<div><label class="pergdes">' .utf8_encode($row['DESITE']) . '</label></div>';
//                        print '<br></br>';                        
                        $i++;
                    }
                ?> 
            <div id="pnto3">
            	<label>Avaliador:</label>            
                <input type="text" id="avaliador" required class="form-control" />
            </div>                                                         
            <div id="pnto">
            	<label>Pontos Fortes:</label>            
                <input type="text" id="ptnforte" required class="form-control" />
            </div>
            <div id="pnto">
            	<label>Pontos Fracos:</label>
                <input type="text" id="ptnfraco" required class="form-control"/>
            </div>             
            <div id="pnto3">
                <?php 
            if ($tipava == 1) {
            print '<label>Existe possibilidade de retorno:</label></div>';
            print '<div id="resposta2">';
            print '<div class="item"><input type="radio" name="retorno" id="retorno" value="1" title="4"/>SIM</div>';
            print '<div class="item"><input type="radio" name="retorno" id="retorno" value="0" title="4"/>NÃO</div>';
            print '<div class="item"><input type="radio" name="retorno" id="retorno" value="2" title="4"/>SIM COM RESTRIÇÃO</div>';
            print '</div>';
            print '<div id="pnto2"><label>Motivo Desligamento:</label>';
            print '<input type="text" id="motivo" required class="form-control"/></div>';
            }
            ?>            
                <input type="hidden" id="catpes" value="<?php echo $catpes ?>"/>
                <input type="hidden" id="tipava" value="<?php echo $tipava ?>"/>
                <input type="hidden" id="empche" value="<?php echo $empresa ?>"/>
                <input type="hidden" id="matche" value="<?php echo $matricula ?>"/>
                <input type="hidden" id="empfunc" value="<?php echo $empfunc ?>"/>
                <input type="hidden" id="matfunc" value="<?php echo $numfunc ?>"/>
            </div>
            <div id="pnto2">
                <input type="button" id="btnEntrar" value="Salvar" class="send"/>            
                <input type="button" id="" value="Voltar" class="send" onClick="history.go(-1)"/>
                <div id="loading"></div>
            </div>            
        </form>
</div>
</div>



