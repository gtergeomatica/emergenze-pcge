<?php

session_start();
require('../validate_input.php');

//echo $_SESSION['user'];

include explode('emergenze-pcge',getcwd())[0].'emergenze-pcge/conn.php';


echo "<h2> La gestione degli incarichi e' attualmente in fase di test and debug. Ci scusiamo per il disagio</h2>";


//$id=$_GET["id"];
$id=str_replace("'", "", $_GET['id']); //incarico

$id_lavorazione=$_POST["id_lavorazione"];
$note_rifiuto= str_replace("'", "''", $_POST["note_rifiuto"]);
$uo=$_POST["uo"];

echo "Incarico:".$id. "<br>";


//exit;


$query= "UPDATE segnalazioni.t_incarichi SET note_ente='".$note_rifiuto."', time_stop=now() 
WHERE id=".$id.";";
echo $query."<br>";
//exit;
$result=pg_query($conn, $query);



$query= "INSERT INTO segnalazioni.stato_incarichi(id_incarico, id_stato_incarico";

//values
$query=$query.") VALUES (".$id.", 3 ";

$query=$query.");";

echo $query."<br>";
//exit;
$result=pg_query($conn, $query);






$query= "INSERT INTO segnalazioni.t_storico_segnalazioni_in_lavorazione(id_segnalazione_in_lavorazione, log_aggiornamento";

//values
$query=$query.") VALUES (".$id_lavorazione.", ' Incarico ".$id." chiuso dalla seguente U.O.: ".$uo." con il seguente messaggio: <br><i>".$note_rifiuto." </i><br>- <a class=\"btn btn-info\" href=\"dettagli_incarico.php?id=".$id."\"> Visualizza dettagli </a>'";

$query=$query.");";


echo $query."<br>";
//exit;
$result=pg_query($conn, $query);


$query_log= "INSERT INTO varie.t_log (schema,operatore, operazione) VALUES ('segnalazioni','".$operatore ."', 'Incarico ".$id." chiuso');";
echo $query_log."<br>";
$result = pg_query($conn, $query_log);


//exit;
header("location: ../dettagli_incarico.php?id=".$id);


?>
