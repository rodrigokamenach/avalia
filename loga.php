<?php
	//Inicia SessÃ£o
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

        session_start();
        
        include "validacao.php";
               
	//Captura usuÃ¡rio e senha passados pela funÃ§Ã£o do Jquery por POST
	$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
	$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : null;
        $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
        
	
        $v = new validacao;
        
        if (empty($empresa)) {
            echo $v->validarCampo("Empresa", $empresa, 10, 1);
        }
        else {
            echo $v->validarNumero("Empresa", $empresa);
        }
        
        if (empty($matricula)) {
            echo $v->validarCampo("Matricula", $matricula, 10, 1);
        }
        else {
            echo $v->validarNumero("Matrícula", $matricula);
        }       
        
        if (empty($cpf)) {
            echo $v->validarCampo("CPF", $cpf, 11, 11);
        }
        else {
            echo $v->validarCpf($cpf);
        }                
                
        if ($v->verifica()) {
	logar($empresa,$matricula,$cpf,$data);	
        } else {
            exit();
        }
        
	
	function logar($empresa,$matricula,$cpf){
		/*Aqui pode ser feita uma consulta no banco ex:
		SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha';
		Mas vou deixar um usuÃ¡rio e senha estÃ¡ticos apenas para demonstrar como funciona
		*/
                require 'conexao.php';
                $conexao = Conectar();      
		$qrloga = "SELECT COUNT(*) AS vcheck FROM vetorh.r034fun a WHERE
                a.numemp = $empresa
                AND a.numcad = $matricula
                AND a.numcpf = $cpf";              
                //AND b.CADCHE = $matricula
                //AND b.NUMEMP = $empresa";
                
                $re = oci_parse($conexao, $qrloga) or die ($qrloga);
                oci_define_by_name($re,'VCHECK',$vcheck);
                oci_execute($re);
                oci_fetch($re);
                	
		//Se senha e usuÃ¡rio eviado for igual as definidas, cria sessÃ£o usuario com o
		//o nome do usuÃ¡rio e chama funÃ§Ã£o javascript que envia para pÃ¡gina principal.php
		if($vcheck > 0){
			$_SESSION['empresa'] = $empresa;
                        $_SESSION['matricula'] = $matricula;
                        $_SESSION['cpf'] = $cpf;
                        echo "<script> document.location = 'index.php?var=1' </script>";
		}
		//Se nÃ£o existir exibe ALERT com o erro
		else{
			echo "Você não possui acesso (Somente Gestores)";	
		}		
	}


?>
