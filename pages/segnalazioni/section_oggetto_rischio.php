<?php 
// cerco l'oggetto a rischio
$check_or=0;
$query_or="SELECT * FROM segnalazioni.join_oggetto_rischio WHERE id_segnalazione=".$id_segnalazione." AND attivo='t';";
$result_or=pg_query($conn, $query_or);
while($r_or = pg_fetch_assoc($result_or)) {
	$check_or=1;
	$id_tipo_oggetto_rischio=$r_or['id_tipo_oggetto'];
	$id_oggetto_rischio=$r_or['id_oggetto'];
}
//echo $query_or;
// cerco i dettagli dell'oggetto a rischio
$query_or2="SELECT * from segnalazioni.tipo_oggetti_rischio where id= ".$id_tipo_oggetto_rischio.";";
//echo $query_or2;
$result_or2=pg_query($conn, $query_or2);
while($r_or2 = pg_fetch_assoc($result_or2)) {
	$nome_tabella_oggetto_rischio=$r_or2['nome_tabella'];
	$descrizione_oggetto_rischio=$r_or2['descrizione'];
	$nome_campo_id_oggetto_rischio=$r_or2['campo_identificativo'];
}
if($check_or==1) {
	echo "<h4> Oggetto a rischio </h4>";
	echo "<b>Tipo oggetto a rischio</b>:".$descrizione_oggetto_rischio;
	echo "<br><b>Id oggetto a rischio </b>:".$id_oggetto_rischio;
} else if ($check_or==0) {
	echo "<h4> Nessun oggetto a rischio segnalato.</h4>";
}
// eventualmente da tirare fuori altri dettagli
//$query_or3="SELECT * from ".$nome_tabella_oggetto_rischio."  where ".$nome_campo_id_oggetto_rischio." = ".$id_oggetto_rischio.";";

?>	