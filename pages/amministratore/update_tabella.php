<?php

session_start();

include '/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php';

$table= $_GET["t"];
$schema= $_GET["s"];





//echo $schema;
//echo "<br>";
//echo $table;

$i=1;
echo "<br>";

$query = "UPDATE ".$schema.".".$table." SET";

foreach ($_POST as $param_name => $param_val) {
	if ($param_name !='id') {
		if ($i > 1){
			$query = $query.",";
		}
   	$query = $query." " .$param_name. " = '" .$param_val. "'";
   	$i=$i+1;
   }
}
$query = $query." WHERE id=".$_POST["id"].";";

echo $query;


$result = pg_query($conn, $query);


//exit;

// redirect verso pagina interna
header("location: ../elenco_amm.php?s=".$schema."&t=".$table."");


?>