<?php    
    session_start();    
    $empresa = $_SESSION["empresa"];
    $matricula = $_SESSION["matricula"];
    $nomfun = $_SESSION['nomche']; 
    require 'conexao.php'; 
    $conexao = Conectar();
?>
<div id="centro">
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
                        var envio = $.post("envind.php", { 
			empresaava: $("#numempava").val(), 
			matriculaava: $("#numcadava").val(),
                        matricula: $("#nummat").val(),
                        empresa: $("#numemp").val(),
                        tipava: $("#tipava").val()
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
$(document).ready(function() {
    $('.emp').selectpicker({
        size: 6
      });     
});
</script>
<script type="text/javascript">
function buscar_emp(){
      var ava = $('#tipava').val();  //codigo do estado escolhido
      //se encontrou o estado
      if(ava){
        var url = 'buscar_emp.php?ava='+ava;  //caminho do arquivo php que irá buscar as cidades no BD
        $.get(url, function(dataReturn) {
          $('#load_emp').html(dataReturn);  //coloco na div o retorno da requisicao
        });
      }
    }
</script>
<link href='css/bootstrap-select.css' rel='stylesheet' type='text/css'>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-select.js"></script>
<div id="colreport">
<div id="titulo">
<h4>Seleção do Avaliado</h4>    
<?php print '<h3><b>Avaliador:</b> '.$nomfun.'</h3>';?>
</div>
    <div id="formulario" class="form-group">
        <form action="" name="" method="post" onSubmit="">
             <div id="resultado">
            <!-- Essa div irá receber todos os resultados --> 
            </div>
        </br>            
            <div>
            	<label>Tipo de Avaliação:</label>
            </div>
            <div>
                <?php                     
                    $qrtippes = "SELECT * FROM usu_tipava";
                    $retip = oci_parse($conexao, $qrtippes) or die ("Erro na execução");
                    oci_execute($retip);
                    
                print '<select id="tipava" name="tipava" class="emp" data-width="auto" onchange="buscar_emp()">';
                    print "<option value=''>Selecione...</option>";
                    while ($rowtipes = oci_fetch_array($retip, OCI_ASSOC)) {
                    print "<option value=".$rowtipes['USU_CODAVA'].">".utf8_encode($rowtipes['USU_NOMAVA'])."</option>";
                    }
                print '</select>';
                ?>
            </div>         
                <div id="load_emp"></div>
<!--                <input type="text" id="numcadava" pattern="[0-9]+$" title="Apenas números" required class="form-control" />-->            
                <input type="hidden" id="numemp" value="<?php echo $empresa ?>"/>
                <input type="hidden" id="nummat" value="<?php echo $matricula ?>"/>
                
            <div>
                <input type="button" id="btnEntrar" value="Selecionar" class="send" />
            </div>
            <div>
                <input type="button" id="" value="Voltar" class="send" onclick="javascript: location.href='index.php?var=6'"/>
            </div>
            <div id="loading"></div>
            </br>                   
        </form>
        </div>
        </div>
</div>
   



