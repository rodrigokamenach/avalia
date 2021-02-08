<?php
require 'conexao.php'; 
$conexao = Conectar();
session_start();

$ava = $_GET['ava']; 
$empresa = $_SESSION["empresa"];
$matricula = $_SESSION["matricula"];

if ($ava == 1) {
	$condicao = "AND (R034FUN.DATAFA >= '01/01/2015' and R034FUN.DATAFA <> '31/12/00')";
} else {
	$condicao = "AND R034FUN.SITAFA   <> 7";
}

$qrfunc = "SELECT A.IDFUNC,
  A.EMPFUNC,
  A.NUMCAD,
  A.NOMFUN,
  A.TITCAR,
  A.NOMLOC,
  NVL(B.USU_CODAVA, 0) AS USU_CODAVA
FROM
  (SELECT CONCAT(R034FUN.NUMEMP, R034FUN.NUMCAD) AS IDFUNC,
    R034FUN.NUMEMP                               AS EMPFUNC,
    R034FUN.NUMCAD,
    R034FUN.NOMFUN,
    R024CAR.TITCAR,
    R016ORN.NOMLOC
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
  $condicao
  AND R034FUN.TIPCOL    = 1
  ) A
LEFT OUTER JOIN
  (SELECT concat(USU_RPESRES.USU_EMPFUN, USU_RPESRES.USU_CADFUN) AS idfunc,
    USU_RPESRES.USU_CODAVA
  FROM USU_RPESRES
  GROUP BY USU_RPESRES.USU_CODAVA,
    USU_RPESRES.USU_CADFUN,
    USU_RPESRES.USU_EMPFUN,
    USU_RPESRES.USU_CADCHE,
    USU_RPESRES.USU_EMPCHE
  HAVING USU_RPESRES.USU_CODAVA = $ava
  AND USU_RPESRES.USU_CADCHE    = $matricula
  AND USU_RPESRES.USU_EMPCHE    = $empresa
  ) B
ON A.IDFUNC = B.idfunc";
$rfunc = oci_parse($conexao, $qrfunc) or die ("Erro na execução");
oci_execute($rfunc);

// IMPRIME SELECT
print '<select id="idava" class="selectpicker" name="idava" data-width="auto" data-live-search="true">';
    print "<option value=''>Selecione...</option>";
    while ($rowfun = oci_fetch_array($rfunc, OCI_ASSOC)) {
        if($rowfun['USU_CODAVA'] == $ava) {
        print "<option class='verde' value=".$rowfun['IDFUNC'].">".$rowfun['EMPFUNC']." - ".$rowfun['NUMCAD']." - ".$rowfun['NOMFUN']." - ".$rowfun['TITCAR']." - ".utf8_encode($rowfun['NOMLOC'])."</option>";
        } else {
            print "<option class='normal' value=".$rowfun['IDFUNC'].">".$rowfun['EMPFUNC']." - ".$rowfun['NUMCAD']." - ".$rowfun['NOMFUN']." - ".$rowfun['TITCAR']." - ".utf8_encode($rowfun['NOMLOC'])."</option>";
        }
    }
print '</select>';
?>
<script>
$(document).ready(function() {
        $('.selectpicker').selectpicker({
        size: 9
      });
});
</script>