<?php

function Conectar(){
        $ora_user = "vetorh";
        $ora_senha = "rec07gf7";
        $ora_bd = "(DESCRIPTION =
                                                (ADDRESS_LIST =
                                                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.49.11)(PORT = 1521))
                                                )
                                                (CONNECT_DATA =
                                                  (SERVICE_NAME = SENIOR)
                                                  (SERVER = DEDICATED)
                                                )
                                          )
                                                ";

        if(!$conn = oci_connect($ora_user, $ora_senha, $ora_bd, 'WE8ISO8859P1')){
                echo 'Erro ao Conectar ao Oracle.';
        }
        else {
          return $conn;
          echo 'funcionou';
	}
}

?>