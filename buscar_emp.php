<?php
require 'conexao.php'; 
$conexao = Conectar();
session_start();

$ava = $_GET['ava']; 
$empresa = $_SESSION["empresa"];
$matricula = $_SESSION["matricula"];
?>
<script type="text/javascript">
function buscar_avaliado(){
      var emp = $('#numempava').val();
      var tipo = "<?php echo $ava;?>";//codigo do estado escolhido
      //se encontrou o estado
      if(emp){
        var url = 'buscar_avaliado.php?emp='+emp+'&tipo='+tipo;  //caminho do arquivo php que irá buscar as cidades no BD
        $.get(url, function(dataReturn) {
          $('#load_avaliado').html(dataReturn);  //coloco na div o retorno da requisicao
        });
      }
    }
</script>
<?php
//$qrfunc = "SELECT A.IDFUNC,
//  A.EMPFUNC,
//  A.NUMCAD,
//  A.NOMFUN,
//  A.TITCAR,
//  A.NOMLOC,
//  NVL(B.USU_CODAVA, 0) AS USU_CODAVA
//FROM
//  (SELECT CONCAT(R034FUN.NUMEMP, R034FUN.NUMCAD) AS IDFUNC,
//    R034FUN.NUMEMP                               AS EMPFUNC,
//    R034FUN.NUMCAD,
//    R034FUN.NOMFUN,
//    R024CAR.TITCAR,
//    R016ORN.NOMLOC
//  FROM R034FUN
//  INNER JOIN R080SUB
//  ON R034FUN.TABORG  = R080SUB.TABORG
//  AND R034FUN.NUMEMP = R080SUB.NUMEMP
//  AND R034FUN.NUMLOC = R080SUB.NUMLOC
//  INNER JOIN R024CAR
//  ON R034FUN.CODCAR = R024CAR.CODCAR
//  INNER JOIN R016ORN
//  ON R016ORN.TABORG     = R080SUB.TABORG
//  AND R016ORN.NUMLOC    = R080SUB.NUMLOC
//  WHERE R034FUN.NUMCAD <> R080SUB.CADCHE
//  AND R080SUB.EMPCHE    = $empresa
//  AND R080SUB.CADCHE    = $matricula
//  AND R034FUN.SITAFA   <> 7
//  AND R034FUN.TIPCOL    = 1
//  ) A
//LEFT OUTER JOIN
//  (SELECT concat(USU_RPESRES.USU_EMPFUN, USU_RPESRES.USU_CADFUN) AS idfunc,
//    USU_RPESRES.USU_CODAVA
//  FROM USU_RPESRES
//  GROUP BY USU_RPESRES.USU_CODAVA,
//    USU_RPESRES.USU_CADFUN,
//    USU_RPESRES.USU_EMPFUN,
//    USU_RPESRES.USU_CADCHE,
//    USU_RPESRES.USU_EMPCHE
//  HAVING USU_RPESRES.USU_CODAVA = $ava
//  AND USU_RPESRES.USU_CADCHE    = $matricula
//  AND USU_RPESRES.USU_EMPCHE    = $empresa
//  ) B
//ON A.IDFUNC                 = B.idfunc
//WHERE NVL(B.USU_CODAVA, 0) <> 0";
//$rfunc = oci_parse($conexao, $qrfunc) or die ("Erro na execução");
//oci_execute($rfunc);

print '<div>';
print '<label>Empresa do avaliado:</label>';
print '</div>';
print '<div>';
    //CONSULTA EMPRESA
    $qremp = "SELECT C.EMPFUNC, D.APEEMP, D.SIGEMP
                FROM
                  (SELECT A.EMPFUNC
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
                    HAVING USU_RPESRES.USU_CODAVA = $ava
                    AND USU_RPESRES.USU_CADCHE    = $matricula
                    AND USU_RPESRES.USU_EMPCHE    = $empresa
                    ) B
                  ON A.IDFUNC                 = B.idfunc
                  WHERE NVL(B.USU_CODAVA, 0) <> 0
                  ) C
                INNER JOIN R030EMP D
                ON C.EMPFUNC = D.NUMEMP
                GROUP BY C.EMPFUNC, D.APEEMP, D.SIGEMP";
    $reemp = oci_parse($conexao, $qremp) or die ("Erro na execução");
    oci_execute($reemp);
    //IMPRIME SELECT
    print '<select id="numempava" name="numempava" class="selectpicker" data-width="auto"  onchange="buscar_avaliado()">';
        print '<option value="">Selecione...</option>';
        while ($rowemp = oci_fetch_array($reemp, OCI_ASSOC)) {
        print "<option value=".$rowemp['EMPFUNC'].">".$rowemp['EMPFUNC'].'-'.$rowemp['SIGEMP'].'-'.utf8_encode($rowemp['APEEMP'])."</option>";
        $_SESSION["tipo"] = $ava;                
        }
    print '</select>'; 
    
print '</div>';
print '<div>';
print '<label>Avaliado:</label>';
print '</div>';
print '<div>';
print '<div id="load_avaliado"></div>';
print '</div>';
?>
<script>
$(document).ready(function() {
        $('.selectpicker').selectpicker({
        size: 9
      });
});
</script>

