<?php
	//Inicia SessÃ£o
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        session_start();        
        include "validacao.php";
        require 'conexao.php'; 
        $conexao = Conectar();
                       
	//Captura usuÃ¡rio e senha passados pela funÃ§Ã£o do Jquery por POST
	$idava = isset($_POST['idava']) ? $_POST['idava'] : null;
        $empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
        $matricula = isset($_POST['matricula']) ? $_POST['matricula'] : null;
        $tipava = isset($_POST['tipava']) ? $_POST['tipava'] : null;            
        
        $v = new validacao;
        if (empty($tipava)) {
            echo $v->validarCampo("Tipo de Avaliação", $tipava, 2, 1);
        }    
        if (empty($idava)) {
            echo $v->validarCampo("Avaliado", $idava, 20, 1);
        }                                           
            
        $valava = "SELECT concat(usu_rpesres.USU_EMPFUN, usu_rpesres.USU_CADFUN) AS idfunc,
                    usu_rpesres.USU_CODAVA
                  FROM usu_rpesres
                  WHERE concat(usu_rpesres.USU_EMPFUN, usu_rpesres.USU_CADFUN) = $idava
                  GROUP BY usu_rpesres.USU_CADFUN,
                    usu_rpesres.USU_CODAVA,
                    usu_rpesres.USU_EMPFUN,
                    usu_rpesres.USU_CADCHE,
                    usu_rpesres.USU_EMPCHE
                  HAVING usu_rpesres.USU_CODAVA = $tipava
                  AND usu_rpesres.USU_CADCHE    = $matricula
                  AND usu_rpesres.USU_EMPCHE    = $empresa";
        $reval = oci_parse($conexao, $valava) or die ("Erro na execução");
        oci_execute($reval);
        
        if ($v->verifica()) {
            if (oci_fetch_all($reval, $w) > 0) {
                // POSSUI AVALIACAO VAI ALTERAR
                altfunc($empresa,$matricula,$tipava, $idava);
            } else {
                // NAO POSSUI VAI INCLUIR
                busfunc($empresa,$matricula,$tipava, $idava);                
            }
        }                                               
              
	function busfunc($empresa,$matricula,$tipava, $idava){		
                $conexao = Conectar();      
		$qrava = "SELECT A.IDFUNC,
                            A.EMPFUNC,
                            A.NUMCAD,
                            A.NOMFUN,
                            A.TITCAR,
                            A.NOMLOC,
                            R034FUN.NOMFUN AS NOMCHE
                          FROM
                            (SELECT CONCAT(R034FUN.NUMEMP, R034FUN.NUMCAD) AS IDFUNC,
                              R034FUN.NUMEMP                               AS EMPFUNC,
                              R034FUN.NUMCAD,
                              R034FUN.NOMFUN,
                              R024CAR.TITCAR,
                              R016ORN.NOMLOC,
                              R080SUB.EMPCHE,
                              R080SUB.CADCHE
                            FROM R034FUN
                            INNER JOIN R080SUB
                            ON R034FUN.TABORG  = R080SUB.TABORG
                            AND R034FUN.NUMEMP = R080SUB.NUMEMP
                            AND R034FUN.NUMLOC = R080SUB.NUMLOC
                            INNER JOIN R024CAR
                            ON R034FUN.CODCAR = R024CAR.CODCAR
                            INNER JOIN R016ORN
                            ON R016ORN.TABORG     = R080SUB.TABORG
                            AND R016ORN.NUMLOC    = R080SUB.NUMLOC
                            WHERE R034FUN.NUMCAD <> R080SUB.CADCHE
                            AND R080SUB.EMPCHE    = $empresa
                            AND R080SUB.CADCHE    = $matricula
                            --AND R034FUN.SITAFA   <> 7
                            --AND (R034FUN.DATAFA = NULL OR R034FUN.DATAFA > '01/06/2015')
                            ) A
                          INNER JOIN R034FUN
                          ON R034FUN.NUMEMP = A.EMPCHE
                          AND A.CADCHE      = R034FUN.NUMCAD
                          WHERE A.IDFUNC    = $idava";
		//echo $qrava;
                $reava = oci_parse($conexao, $qrava) or die ("Erro na execução 1");
                oci_define_by_name($reava,'EMPFUNC',$empfunc);
                oci_define_by_name($reava,'NUMCAD',$numfunc);
                oci_define_by_name($reava,'NOMFUN',$nomfunc);
                oci_define_by_name($reava,'TITCAR',$titcar);
                oci_define_by_name($reava,'NOMLOC',$nomloc);
                oci_define_by_name($reava,'NOMCHE',$nomche);
                oci_execute($reava); oci_fetch($reava);
                
		if(oci_num_rows($reava) > 0){
			$_SESSION['empresa'] = $empresa;
                        $_SESSION['matricula'] = $matricula;
                        $_SESSION['empfunc'] = $empfunc;
                        $_SESSION['numfunc'] = $numfunc;
                        $_SESSION['nomfunc'] = $nomfunc;
                        $_SESSION['titcar'] = $titcar;
                        $_SESSION['nomloc'] = $nomloc;
                        $_SESSION['idava'] = $idava;                        
                        $_SESSION['nomche'] = $nomche;
                        $_SESSION['tipava'] = $tipava;
                        echo "<script> document.location = 'index.php?var=3' </script>";
		}
		//Se nÃ£o existir exibe ALERT com o erro
		else{ echo "<p>Somente para seus subordinados</p>";}		
	}         
        
        function altfunc($empresa,$matricula,$tipava, $idava){
		$conexao = Conectar();      
		$qrava = "SELECT A.IDFUNC,
                            A.EMPFUNC,
                            A.NUMCAD,
                            A.NOMFUN,
                            A.TITCAR,
                            A.NOMLOC,
                            R034FUN.NOMFUN AS NOMCHE
                          FROM
                            (SELECT CONCAT(R034FUN.NUMEMP, R034FUN.NUMCAD) AS IDFUNC,
                              R034FUN.NUMEMP                               AS EMPFUNC,
                              R034FUN.NUMCAD,
                              R034FUN.NOMFUN,
                              R024CAR.TITCAR,
                              R016ORN.NOMLOC,
                              R080SUB.EMPCHE,
                              R080SUB.CADCHE
                            FROM R034FUN
                            INNER JOIN R080SUB
                            ON R034FUN.TABORG  = R080SUB.TABORG
                            AND R034FUN.NUMEMP = R080SUB.NUMEMP
                            AND R034FUN.NUMLOC = R080SUB.NUMLOC
                            INNER JOIN R024CAR
                            ON R034FUN.CODCAR = R024CAR.CODCAR
                            INNER JOIN R016ORN
                            ON R016ORN.TABORG     = R080SUB.TABORG
                            AND R016ORN.NUMLOC    = R080SUB.NUMLOC
                            WHERE R034FUN.NUMCAD <> R080SUB.CADCHE
                            AND R080SUB.EMPCHE    = $empresa
                            AND R080SUB.CADCHE    = $matricula
                            --AND R034FUN.SITAFA   <> 7
                            ) A
                          INNER JOIN R034FUN
                          ON R034FUN.NUMEMP = A.EMPCHE
                          AND A.CADCHE      = R034FUN.NUMCAD
                          WHERE A.IDFUNC    = $idava";
                $reava = oci_parse($conexao, $qrava) or die ("Erro na execução 1");
                oci_define_by_name($reava,'EMPFUNC',$empfunc);
                oci_define_by_name($reava,'NUMCAD',$numfunc);
                oci_define_by_name($reava,'NOMFUN',$nomfunc);
                oci_define_by_name($reava,'TITCAR',$titcar);
                oci_define_by_name($reava,'NOMLOC',$nomloc);
                oci_define_by_name($reava,'NOMCHE',$nomche);
                oci_execute($reava); oci_fetch($reava);
                
		if(oci_num_rows($reava) > 0){
			$_SESSION['empresa'] = $empresa;
                        $_SESSION['matricula'] = $matricula;
                        $_SESSION['empfunc'] = $empfunc;
                        $_SESSION['numfunc'] = $numfunc;
                        $_SESSION['nomfunc'] = $nomfunc;
                        $_SESSION['titcar'] = $titcar;
                        $_SESSION['nomloc'] = $nomloc;
                        $_SESSION['idava'] = $idava;                        
                        $_SESSION['nomche'] = $nomche;
                        $_SESSION['tipava'] = $tipava;
                        //echo "<script> document.location = 'index.php?var=9' </script>";
		}
		//Se nÃ£o existir exibe ALERT com o erro
		else{ echo "Nao existe avaliação do tipo selecionado.";}		
	}
?>


