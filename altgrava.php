<?php
	//Inicia SessÃ£o
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        session_start();        
        include "validacao.php";
               
	//Captura usuÃ¡rio e senha passados pela funÃ§Ã£o do Jquery por POST
	$catpes = isset($_POST['catpes']) ? $_POST['catpes'] : null;
        $tipava = isset($_POST['tipava']) ? $_POST['tipava'] : null;
	$empche = isset($_POST['empche']) ? $_POST['empche'] : null;
        $matche = isset($_POST['matche']) ? $_POST['matche'] : null;
        $empfunc = isset($_POST['empfunc']) ? $_POST['empfunc'] : null;
	$matfunc = isset($_POST['matfunc']) ? $_POST['matfunc'] : null;
        $ptnforte = isset($_POST['ptnforte']) ? $_POST['ptnforte'] : null;
	$ptnfraco = isset($_POST['ptnfraco']) ? $_POST['ptnfraco'] : null;
	$avaliador = isset($_POST['avaliador']) ? $_POST['avaliador'] : null;
	$motivo = isset($_POST['motivo']) ? $_POST['motivo'] : null;
        $retorno = isset($_POST['retorno']) ? $_POST['retorno'] : null;
        $data = date('d/m/Y');
        
        for ($i=1; $i<=10; $i++) {
            if(!isset($_POST['q'.$i])) {
                $msg[$i] = "<li>Marque a pergunta $i</li>";
                echo "<div>".$msg[$i]."</div>";                
            } else {
              $nt[$i] = $_POST['q'.$i];
            }
            
         }
        
        $v = new validacao;
        
        if (empty($ptnforte)) {
            echo $v->validarCampo("Pontos Fortes", $ptnforte, 300, 1);
        }
                
        if (empty($ptnfraco)) {
            echo $v->validarCampo("Pontos Fracos", $ptnfraco, 300, 1);
        }
        
        if ($tipava == 1) {
            if (!isset($retorno)) {
                echo "Marque se há possibilidade de retorno";
            }
        }
        
          
        if ($v->verifica()) {
            require 'conexao.php';
            $conexao = Conectar();
            for ($d=1; $d<=10; $d++) {
                gravalt($empche,$matche,$empfunc,$matfunc,$data,$catpes,$d,$nt[$d],$tipava);    
            }
            if ($tipava == 1) {
            $qrb = "UPDATE usu_rpespto
                    SET usu_mesano = to_date(sysdate, 'dd/mm/YY'),
                      usu_ponfor   = '$ptnforte',
                      usu_ponfra   = '$ptnfraco',
                      USU_HAVRET   = '$retorno',
                      usu_avaliador = '$avaliador',
                      usu_motivo    = '$motivo'
                    WHERE usu_empfun = $empfunc
                    AND usu_cadfun   = $matfunc
                    AND usu_codava   = $tipava";
            } else {
               $qrb = "UPDATE usu_rpespto
                    SET usu_mesano = to_date(sysdate, 'dd/mm/YY'),
                      usu_ponfor   = '$ptnforte',
                      usu_ponfra   = '$ptnfraco'
                    WHERE usu_empfun = $empfunc
                    AND usu_cadfun   = $matfunc
                    AND usu_codava   = $tipava";
            }
            $reb = oci_parse($conexao, $qrb);
            oci_execute($reb);
            $resp = oci_commit($reb);
            if(!$resp){
                echo "Salvo com sucesso";
                echo "<script> document.location = 'index.php?var=2' </script>";
            } else {
                echo 'Erro ao gravar.';
                echo oci_error($resp);
            }
           
        } else {
            exit();            
        }
        
	
	function gravalt($empche,$matche,$empfunc,$matfunc,$data,$catpes,$d,$nota,$tipava){
		/*Aqui pode ser feita uma consulta no banco ex:
		SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha';
		Mas vou deixar um usuÃ¡rio e senha estÃ¡ticos apenas para demonstrar como funciona
		*/
                $e = 0;                
                global $conexao;
                $qrpes = "UPDATE usu_rpesres
                        SET usu_mesano = to_date(sysdate, 'dd/mm/YY'),
                          usu_notite   = $nota
                        WHERE usu_empfun = $empfunc
                        AND usu_cadfun   = $matfunc
                        AND usu_codava   = $tipava
                        and usu_codpes   = $catpes
                        and usu_codite   = $d";
                $inpes = oci_parse($conexao, $qrpes) or die ('Erro');
                oci_execute($inpes, OCI_DEFAULT);
                oci_commit($conexao);
                $result = oci_close($conexao);   
                               	
		//Se senha e usuÃ¡rio eviado for igual as definidas, cria sessÃ£o usuario com o
		//o nome do usuÃ¡rio e chama funÃ§Ã£o javascript que envia para pÃ¡gina principal.php
		if(!$result) {
                    $err[$e] = oci_error($result);
                    $e++;
                } 
                
                if ($err) {
                    print_r($err);
                } 
                	
	}


?>





