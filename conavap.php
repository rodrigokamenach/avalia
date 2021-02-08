<?php   
    session_start();
    $empresa = $_SESSION["empresa"];
    $matricula = $_SESSION["matricula"];
    $nomfun = $_SESSION['nomche'];
    
    require 'conexao.php'; 
    $conexao = Conectar();
?>
<script src="js/jquery-1.9.0.js" type="text/javascript"> </script>
<script type="text/javascript">
	$(document).ready(function(){                
                function loading_show(){
		$('#loading').html("<img src='img/ajax_loader_blue_32.gif'/>").fadeIn('fast');
                }
                //Aqui desativa a imagem de loading
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }              
		//Quando 'btnEntrar' for clicado
                $("#btnEntrar").click(function(){
			//Envia por POST para a página login.php: usuario = valor da textbox usuario
			//e senha = valor da textbox senha (pegando valores pelo ID)
			loading_show();                     
                        var envio = $.post("envavap.php", { 
			tipava: $("#tipava").val(), 
			idava: $("#idava").val(),
                        matricula: $("#nummat").val(),
                        empresa: $("#numemp").val()                     
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
<script type="text/javascript">
function buscar_avaliado(){
      var ava = $('#tipava').val();  //codigo do estado escolhido
      //se encontrou o estado
      if(ava){
        var url = 'ajax_buscar_avaliado.php?ava='+ava;  //caminho do arquivo php que irá buscar as cidades no BD
        $.get(url, function(dataReturn) {
          $('#load_avaliado').html(dataReturn);  //coloco na div o retorno da requisicao
        });
      }
    }
</script>
<script>
$(document).ready(function() {
    $('.selectpicker').selectpicker({
        size: 4
      });
});
</script>
<link href='css/bootstrap-select.css' rel='stylesheet' type='text/css'>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-select.js"></script>
<div id="centro">
    <div id="titulo">
        <h4>Seleção do Avaliado</h4>
        <?php print '<h3><b>Avaliador:</b> '.$nomfun.'</h3>';?>
    </div>
    <br>
    <div id="formulario">
        <form action="" name="" method="post" onSubmit="">
            <div id="resultado">
            <!-- Essa div irá receber todos os resultados --> 
            </div>
            <br></br>
            <div>
            	<label>Tipo de Avaliação:</label>
            </div>
            <div>
                <?php 
                    //CONSULTA TIPO DE AVALIACAO
                    $qrtipava = "SELECT * FROM usu_tipava WHERE USU_DATINI <= TO_DATE(SYSDATE, 'dd/mm/YY') and usu_datfim >= TO_DATE(SYSDATE, 'dd/mm/YY')";
                    $retip = oci_parse($conexao, $qrtipava) or die ("Erro na execução");
                    oci_execute($retip);
                    //IMPRIME SELECT
                    print '<select id="tipava" name="tipava" class="selectpicker" data-width="auto" onchange="buscar_avaliado()">';
                        print '<option value="">Selecione...</option>';
                        while ($rowtipes = oci_fetch_array($retip, OCI_ASSOC)) {
                        print "<option value=".$rowtipes['USU_CODAVA'].">".utf8_encode($rowtipes['USU_NOMAVA'])."</option>";
                        }
                    print '</select>';
                ?>
            </div>
            <br>
            <div>
            	<label>Avaliado:</label>
                <span class="leg1">Já possui avaliação.</span>/                
                <span class="leg2">Não possui avaliação</span>            
            </div>
            <div id="load_avaliado"></div>
            
                <input type="hidden" id="numemp" value="<?php echo $empresa ?>"/>
                <input type="hidden" id="nummat" value="<?php echo $matricula ?>"/>
                <br>
            <div>                
                <input type="button" id="btnEntrar" value="Selecionar" class="send" />
                <input type="button" id="" value="Voltar" class="send" onclick="javascript: location.href='index.php?var=1'"/>
            </div>
            <div id="loading"></div>            
        </form>
        </div>
</div>