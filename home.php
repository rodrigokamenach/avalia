<?php    
    require 'conexao.php'; 
    $conexao = Conectar();
?>
<script>
$(document).ready(function() {
    $('.emp').selectpicker({
        size: 6
      });
});
</script>
<link href='css/bootstrap-select.css' rel='stylesheet' type='text/css'>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-select.js"></script>
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
                        var envio = $.post("loga.php", { 
			empresa: $("#numemp").val(), 
			matricula: $("#numcad").val(),
                        cpf: $("#numcpf").val()
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
<div id="centro">
    <div id="titulo">
        <h4>Formúlario de Acesso</h4>
    </div>    
        </br>
        <div id="formulario" class="form-group">
        <form action="" name="" method="post" onSubmit="">
            <div id="resultado">
            <!-- Essa div irá receber todos os resultados --> 
            </div>
        </br>
            <div>
            	<label>Empresa:</label>
            </div>
            <div>
                <?php 
                    //CONSULTA TIPO DE AVALIACAO
                    $qremp = "SELECT numemp, apeemp, sigemp FROM r030emp where sigemp != ' ' ORDER BY NUMEMP";
                    $reemp = oci_parse($conexao, $qremp) or die ("Erro na execução");
                    oci_execute($reemp);
                    //IMPRIME SELECT
                    print '<select id="numemp" name="numemp" class="emp" data-width="auto">';
                        print '<option value="">Selecione...</option>';
                        while ($rowemp = oci_fetch_array($reemp, OCI_ASSOC)) {
                        print "<option value=".$rowemp['NUMEMP'].">".$rowemp['NUMEMP'].'-'.$rowemp['SIGEMP'].'-'.utf8_encode($rowemp['APEEMP'])."</option>";
                        }
                    print '</select>';
                ?>
<!--                <input type="text" required="required" name="numemp" id="numemp" class="form-control" pattern="[0-9]+$" />-->
            </div>
            <div>
            	<label>Matricula:</label></div>
            <div>
                <input type="text" id="numcad" pattern="[0-9]+$" title="Apenas números" class="form-control" required />
            </div>
            <div>
                <label>CPF:</label>
            </div>
            <div>
                <input id="numcpf" type="password" required pattern="[0-9]+$" class="form-control" title="Apenas números"/>
            </div>
<!--            <div>
                <label>Data de Nascimento:</label>
            </div>
            <div>
                <input type="date" maxlength="10" id="datnas" pattern="[0-9]{2}\/[0-9]{2}\/[0-9]{4}$" required/>
            </div>-->
            <div>
                <input type="button" id="btnEntrar" value="Acessar" class="send" />
            </div>
            <div id="loading"></div>
        </form>
        </div>
</div>