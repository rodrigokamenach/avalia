<?php
require 'conexao.php'; 
$conexao = Conectar();
session_start();

$emp = $_GET['emp']; 
$empresa = $_SESSION["empresa"];
$matricula = $_SESSION["matricula"];
$tipo = $_GET['tipo'];

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
  --AND R034FUN.SITAFA   <> 7
  AND R034FUN.TIPCOL    = 1
  AND (R034FUN.DATAFA = NULL OR R034FUN.DATAFA > '01/06/2015')
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
  HAVING USU_RPESRES.USU_CODAVA = $tipo
  AND USU_RPESRES.USU_CADCHE    = $matricula
  AND USU_RPESRES.USU_EMPCHE    = $empresa
  ) B
ON A.IDFUNC = B.idfunc 
WHERE A.EMPFUNC = $emp
AND NVL(B.USU_CODAVA, 0) = $tipo";
$rfunc = oci_parse($conexao, $qrfunc) or die ("Erro na execução");
oci_execute($rfunc);

// IMPRIME SELECT
print '<select id="numcadava" class="selectpicker" name="numcadava" data-width="auto">';
    print "<option value=''>Selecione...</option>";
    while ($rowfun = oci_fetch_array($rfunc, OCI_ASSOC)) {
        if($rowfun['USU_CODAVA'] == $tipo) {
        print "<option value=".$rowfun['NUMCAD'].">".$rowfun['EMPFUNC']." - ".$rowfun['NUMCAD']." - ".$rowfun['NOMFUN']." - ".$rowfun['TITCAR']." - ".utf8_encode($rowfun['NOMLOC'])."</option>";
        } else {
            print "<option value=".$rowfun['NUMCAD'].">".$rowfun['EMPFUNC']." - ".$rowfun['NUMCAD']." - ".$rowfun['NOMFUN']." - ".$rowfun['TITCAR']." - ".utf8_encode($rowfun['NOMLOC'])."</option>";
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