<?php

session_start();

//echo $_SESSION['user'];

include explode('emergenze-pcge',getcwd())[0].'emergenze-pcge/conn.php';


//echo "<h2> La gestione dei sopralluoghi e' attualmente in fase di test and debug. Ci scusiamo per il disagio</h2>";


//$id=$_GET["id"];
$id=str_replace("'", "", $_GET['id']); //sopralluogo

$id_lavorazione=$_POST["id_lavorazione"];
$note_rifiuto= str_replace("'", "''", $_POST["note_rifiuto"]);
$uo=$_POST["uo"];
$squadra=$_POST["squadra"];


echo "sopralluogo:".$id. "<br>";


//exit;


$query= "UPDATE segnalazioni.t_sopralluoghi_mobili SET note_ente='".$note_rifiuto."', time_stop=now() 
WHERE id=".$id.";";
echo $query."<br>";
//exit;
$result=pg_query($conn, $query);



$query= "INSERT INTO segnalazioni.stato_sopralluoghi_mobili(id_sopralluogo, id_stato_sopralluogo";

//values
$query=$query.") VALUES (".$id.", 3 ";

$query=$query.");";

echo $query."<br>";
//exit;
$result=pg_query($conn, $query);



$query="UPDATE users.t_squadre SET id_stato=2 WHERE id=".$squadra.";";
echo $query;
//exit;
$result=pg_query($conn, $query);




if ($id_lavorazione!=''){
	$query= "INSERT INTO segnalazioni.t_storico_segnalazioni_in_lavorazione(id_segnalazione_in_lavorazione, log_aggiornamento";
	
	//values
	$query=$query.") VALUES (".$id_lavorazione.", 'Presidio ".$id." chiuso dalla seguente squadra: ".$uo." con il seguente messaggio: <br><i>".$note_rifiuto." </i><br>- <a class=\"btn btn-info\" href=\"dettagli_sopralluogo.php?id=".$id."\"> Visualizza dettagli </a>'";
	
	$query=$query.");";
	
	
	echo $query."<br>";
	//exit;
	$result=pg_query($conn, $query);
}

$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('sopralluoghi','".$operatore ."', 'presidio mobile(o sopralluogo) ".$id." chiuso');";
echo $query_log."<br>";
$result = pg_query($conn, $query_log);


//exit;
header("location: ../dettagli_sopralluogo_mobile.php?id=".$id);


?>
