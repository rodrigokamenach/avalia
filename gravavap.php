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
        
        for ($i=1; $i<=9; $i++) {
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
        
        if($tipava == 1){
            if (empty($retorno)) {
                echo $v->validarCampo("Possibilidade de Retorno", $retorno, 300, 1);
            }
        }
        
          
        if ($v->verifica()) {
            require 'conexao.php';
            $conexao = Conectar();
            if (!$msg) {
            if ($tipava == 1) {
                    $qrb = "insert into usu_rpespto (usu_empche,usu_cadche,usu_empfun,usu_cadfun,usu_mesano,usu_ponfor, usu_ponfra, usu_havret, usu_codava, usu_avaliador, usu_motivo)
                            values ('$empche', '$matche', '$empfunc', '$matfunc', '$data', '$ptnforte', '$ptnfraco', '$retorno', '$tipava', $avaliador, $motivo)";
                    } else {
                    $qrb = "insert into usu_rpespto (usu_empche,usu_cadche,usu_empfun,usu_cadfun,usu_mesano,usu_ponfor, usu_ponfra, usu_codava, usu_avaliador)
                            values ('$empche', '$matche', '$empfunc', '$matfunc', '$data', '$ptnforte', '$ptnfraco', '$tipava', $avaliador)";    
                    }
                    $reb = oci_parse($conexao, $qrb);
                    $resp = oci_execute($reb, OCI_COMMIT_ON_SUCCESS);
                    
                for ($d=1; $d<=10; $d++) {
                    gravpes($empche,$matche,$empfunc,$matfunc,$data,$catpes,$d,$nt[$d],$tipava);          
                }
                if($resp) {
                    echo "Salvo com sucesso";
                    echo "<script> document.location = 'index.php?var=2' </script>";
                } else {
                    echo '<li>Erro ao gravar respostas.</li>';
                    exit();
                }  
            } else {
                echo '<li>Erro ao gravar pontos.</li>';
                exit();
            }                                     
        } else {
            echo '<li>Erro ao verificar</li>';
            exit();            
        }
        
	
	function gravpes($empche,$matche,$empfunc,$matfunc,$data,$catpes,$d,$nota,$tipava){		
//                $e = 0;                
                global $conexao;
                $qrpes = "insert into usu_rpesres (usu_empche,usu_cadche,usu_empfun,usu_cadfun,usu_mesano,usu_codpes, usu_codite, usu_notite, usu_codava)
                             values ('$empche', '$matche', '$empfunc', '$matfunc', '$data', '$catpes', '$d', '$nota', $tipava)";
                $inpes = oci_parse($conexao, $qrpes) or die ('Erro');
                $result = oci_execute($inpes, OCI_COMMIT_ON_SUCCESS);
                if ($result) {
                    $e = true;
                } else {
                    $e = false;
                }
//                if ($err) {
//                    print_r($err);
//                } 
        }
?>


