<?php

include explode('emergenze-pcge',getcwd())[0].'emergenze-pcge/conn.php';

$getfiltri=$_GET["f"];
$filtro_evento_attivo=$_GET["a"];
$filtro_municipio=$_GET["m"];
$filtro_from=$_GET["from"];
$filtro_to=$_GET["to"];
$resp=$_GET["r"];

$pagina=$_POST["pagina"];

$query='SELECT * FROM segnalazioni.tipo_criticita where valido=\'t\' ;';
$result = pg_query($conn, $query);
#echo $result;
//exit;
//$rows = array();
$filter='';
while($r = pg_fetch_assoc($result)) {
    $name='filter'.$r['id'];
    if ($_POST["$name"]==1) {
        $filter=$filter.'1';
    } else {
         $filter=$filter.'0';
    }
}


header("Location: ../".$pagina."?r=".$resp."&f=".$filter."&a=".$filtro_evento_attivo."&from=".$filtro_from."&to=".$filtro_to."&m=".$filtro_municipio."");

echo $filter; 



?>
