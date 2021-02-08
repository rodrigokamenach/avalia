<?php

//Inicia SessÃ£o
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
session_start();
include "validacao.php";

//Captura usuÃ¡rio e senha passados pela funÃ§Ã£o do Jquery por POST
$empresaava = isset($_POST['empresaava']) ? $_POST['empresaava'] : null;
$matriculaava = isset($_POST['matriculaava']) ? $_POST['matriculaava'] : null;
$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
$matricula = isset($_POST['matricula']) ? $_POST['matricula'] : null;
$tipava = isset($_POST['tipava']) ? $_POST['tipava'] : null;

//session_destroy();
$v = new validacao;

if (empty($empresaava)) {
    echo $v->validarCampo("Empresa", $empresaava, 10, 1);
} else {
    echo $v->validarNumero("Empresa", $empresaava);
}

if (empty($matriculaava)) {
    echo $v->validarCampo("Avaliado", $matriculaava, 10, 1);
} else {
    echo $v->validarNumero("Avaliado", $matriculaava);
}

if (empty($tipava)) {
    echo $v->validarCampo("Tipo de Avaliação", $tipava, 1, 1);
}

if ($matricula == $matriculaava) {
    echo 'Insira a matrícula de algum subordinado.';
    exit();
} else {

    if ($v->verifica()) {
        busfunc($empresaava, $matriculaava, $empresa, $matricula, $tipava);
    } else {
        exit();
    }
}

function busfunc($empresaava, $matriculaava, $empresa, $matricula, $tipava) {
    /* Aqui pode ser feita uma consulta no banco ex:
      SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha';
      Mas vou deixar um usuÃ¡rio e senha estÃ¡ticos apenas para demonstrar como funciona
     */
    session_start();
    require'conexao.php';    
    $conexao = Conectar();
    
// CONSULTA NOME, CARGO, LOCAL E CHEFE
    $qrava = "SELECT f.NOMFUN,
  d.titcar,
  e.nomloc,
  c.NOMFUN AS chefe,
  h.apeemp,
  h.sigemp
FROM vetorh.r038hlo l ,
  vetorh.r034fun f ,
  vetorh.r080sub g ,
  vetorh.r034fun c ,
  vetorh.r024car d ,
  vetorh.r016orn e,
  vetorh.r030emp h
WHERE f.numemp = l.numemp
AND f.tipcol   = l.tipcol
AND f.numcad   = l.numcad
AND c.numemp   = g.empche
AND c.tipcol   = g.tipche
AND c.numcad   = g.cadche
AND l.numemp   = g.numemp
AND l.taborg   = g.taborg
AND l.numloc   = g.numloc
AND f.numemp   = $empresaava
AND f.tipcol   = 1
AND f.numcad   = $matriculaava
AND c.numemp   = $empresa
AND c.tipcol   = 1
AND c.numcad   = $matricula
--AND f.sitafa  <> 7
AND f.codcar   = d.codcar
AND l.NUMLOC   = e.NUMLOC
AND h.numemp = f.numemp
--AND (c.datafa = null or c.datafa > '01/06/2015')";
    $reava = oci_parse($conexao, $qrava) or die ("Erro na execução");
    oci_define_by_name($reava,'NOMFUN',$nomfunc);
    oci_define_by_name($reava,'TITCAR',$titcar);
    oci_define_by_name($reava,'NOMLOC',$nomloc);
    oci_define_by_name($reava,'CHEFE',$nomche);
    oci_define_by_name($reava,'APEEMP',$apeemp);
    oci_define_by_name($reava,'SIGEMP',$sigemp);
    oci_execute($reava); oci_fetch($reava);
     
//    CALCULA MEDIA
    $qrm = "select avg(usu_notite) media_funci 
    from usu_rpesres 
    where usu_cadfun = $matriculaava
     and usu_codava = $tipava";
    $rem = oci_parse($conexao, $qrm) or die ("erro");
    oci_define_by_name($rem, 'MEDIA_FUNCI', $mediafun);
    oci_execute($rem);
    oci_fetch($rem);
    
    $qrdt = "SELECT r.usu_mesano
            FROM usu_rpesres r ,
              usu_rpestab t
            WHERE r.usu_codpes   = t.usu_codpes
            AND r.usu_codite   = t.usu_codite
            AND r.usu_empfun   = $empresaava
            AND r.usu_cadfun   = $matriculaava
            AND r.usu_codava   = $tipava
            GROUP BY r.USU_MESANO";
    $rdt = oci_parse($conexao, $qrdt) or die ("erro");
    oci_define_by_name($rdt, 'USU_MESANO', $datava);
    oci_execute($rdt);
    oci_fetch($rdt);
    
    if (isset($mediafun)) {
        $_SESSION['matriculaava'] = $matriculaava;
        $_SESSION['nomfunc'] = $nomfunc;
        $_SESSION['titcar'] = $titcar;
        $_SESSION['nomloc'] = $nomloc;
        $_SESSION['nomche'] = $nomche;
        $_SESSION['mediafun'] = $mediafun;
        $_SESSION['empresaava'] = $empresaava;
        $_SESSION['tipava'] = $tipava;
        $_SESSION['apeemp'] = $apeemp;
        $_SESSION['sigemp'] = $sigemp;
        $_SESSION['datava'] = $datava;
        echo "<script> document.location = 'index.php?var=10' </script>";
    }
    //Se nÃ£o existir exibe ALERT com o erro
    else {
        echo "Nao existe avaliação do tipo selecionado.";    
    }
}
?>