<?php
//require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

$check_evento=0;
$contatore_eventi==0;
$contatore_allerte=0;
$descrizione_allerta='Nessuna allerta';
$color_allerta='#5cb85c';

//require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

$query1="SELECT * From \"eventi\".\"t_eventi\" WHERE valido='TRUE' ORDER BY id;";
$result1 = pg_query($conn, $query1);
$contatore_eventi=0;

while($r1 = pg_fetch_assoc($result1)) {
	$check_evento=1; // controllo se evento in corso inizializzato a 1
	$contatore_eventi=$contatore_eventi+1;
	$eventi_attivi[]=$r1["id"];
	$start[]=$r1["data_ora_inizio_evento"];
	$query2="SELECT  b.descrizione From eventi.join_tipo_evento a,eventi.tipo_evento b  WHERE a.id_evento=".$r1["id"]." and a.id_tipo_evento=b.id;";
	//echo $query2;
	$result2 = pg_query($conn, $query2);
	while($r2 = pg_fetch_assoc($result2)) {
		$tipo_eventi_attivi[]=array($r1["id"],$r2["descrizione"]);
	}
	$query2="SELECT  nota From eventi.t_note_eventi WHERE id_evento=".$r1["id"]." ;";
	//echo $query2;
	$result2 = pg_query($conn, $query2);
	while($r2 = pg_fetch_assoc($result2)) {
		$nota_eventi_attivi[]=array($r1["id"],$r2["nota"]);
	}	
	$query3="SELECT  b.nome_munic From eventi.join_municipi a,geodb.municipi b  WHERE a.id_evento=".$r1["id"]." and a.id_municipio::integer=b.codice_mun::integer;";
	//echo $query3;
	$result3 = pg_query($conn, $query3);
	while($r3 = pg_fetch_assoc($result3)) {
		$municipi[]=array($r1["id"],$r3["nome_munic"]);
	}
	
	
}

if($contatore_eventi==0) {
	$preview_eventi="Nessun evento in corso";
} else if ($contatore_eventi==1){
	$preview_eventi="Evento in corso";
} else{
	$preview_eventi="Eventi in corso";
}


$query1="SELECT count(id) as conto FROM eventi.t_eventi WHERE valido is null;";
$result1 = pg_query($conn, $query1);
while($r1 = pg_fetch_assoc($result1)) {
	$contatore_eventi_chiusura=$r1['conto'];
}
// allerta in corso
// RENDI INDIPENDENTI DA COLORI GLI IF


if($contatore_eventi>0) {
	$query="SELECT * FROM eventi.v_allerte WHERE data_ora_inizio_allerta < now() AND data_ora_fine_allerta > now();";
	$contatore_allerte=0;
	$descrizione_allerta='Nessuna allerta';
	$color_allerta='#5cb85c';
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$contatore_allerte=$contatore_allerte+1;
		if ($descrizione_allerta=='Nessuna allerta' OR $descrizione_allerta=='Gialla'){
			$color_allerta=$r["rgb_hex"];
			$descrizione_allerta = $r["descrizione"];
		} else if($descrizione_allerta=='Rossa') { 
			// se rossa mantengo il colore tale
			$color_allerta='#ff0000';
			$descrizione_allerta = 'Rossa';
		} else if ($descrizione_allerta=='Arancione' AND $r["descrizione"]!= 'Gialla'){ 
			//se arancione prendo il colore dell'altra allerta che leggo a meno che non sia gialla
			$color_allerta=$r["rgb_hex"];
		}
	}	
}

if($contatore_allerte==0) {
	$contatore_allerte="-";
	$preview_allerte="Nessun allerta in corso";
} else if ($contatore_allerte==1){
	$preview_allerte="Allerta in corso";
} else{
	$preview_allerte="Allerte in corso";
}



if($contatore_eventi>0) {
	$query="SELECT * FROM eventi.v_foc WHERE data_ora_inizio_foc < now() AND data_ora_fine_foc > now();";
	$contatore_foc=0;
	$descrizione_foc='-';
	$color_foc='#5cb85c';
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$contatore_foc=$contatore_foc+1;
		if ($descrizione_foc=='-' OR $descrizione_foc=='Attenzione'){
			$color_foc=$r["rgb_hex"];
			$descrizione_foc = $r["descrizione"];
		} else if($descrizione_foc=='Allarme') { 
			// se rossa mantengo il colore tale
			$color_foc='#ff0000';
			$descrizione_foc = 'Allarme';
		} else if ($descrizione_foc=='Pre-allarme' AND $r["descrizione"]!= 'Attenzione'){ 
			//se arancione prendo il colore dell'altra allerta che leggo a meno che non sia gialla
			$color_foc=$r["rgb_hex"];
			$descrizione_foc=$r["descrizione"];
		}
	}	
}


if($contatore_eventi>0) {
	$query="SELECT * FROM eventi.t_attivazione_nverde WHERE data_ora_inizio < now() AND data_ora_fine > now();";
	$contatore_nverde=0;
	$descrizione_nverde='Numero verde non attivo';
	$color_nverde='#333333';
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$contatore_nverde=$contatore_nverde+1;
	}	
}

if($contatore_nverde==0) {
	$preview_nverde="Numero verde non attivato";
} else if ($contatore_nverde==1){
	$preview_nverde="Numero verde attivato";
	$color_nverde='#009542';
} else{
	$preview_nverde="Numero verde attivato";
	$color_nverde='#009542';
}



$query1="SELECT * From \"eventi\".\"t_eventi\" WHERE valido IS NULL ORDER BY id;";
$result1 = pg_query($conn, $query1);
$contatore_eventi_c=0;
while($r1 = pg_fetch_assoc($result1)) {
	$check_evento_c=1; // controllo se evento in corso inizializzato a 1
	$contatore_eventi_c=$contatore_eventi_c+1;
	$eventi_attivi_c[]=$r1["id"];
	$start_c[]=$r1["data_ora_inizio_evento"];
	$query2="SELECT  b.descrizione From eventi.join_tipo_evento a,eventi.tipo_evento b  WHERE a.id_evento=".$r1["id"]." and a.id_tipo_evento=b.id;";
	//echo "<br>".$query2;
	$result2 = pg_query($conn, $query2);
	while($r2 = pg_fetch_assoc($result2)) {
		$tipo_eventi_c[]=array($r1["id"],$r2["descrizione"]);
	}
	$query2="SELECT nota From eventi.t_note_eventi WHERE id_evento=".$r1["id"]." ;";
	//echo $query2;
	$result2 = pg_query($conn, $query2);
	while($r2 = pg_fetch_assoc($result2)) {
		$nota_eventi_c[]=array($r1["id"],$r2["nota"]);
	}	
	$query3="SELECT  b.nome_munic From eventi.join_municipi a,geodb.municipi b  WHERE a.id_evento=".$r1["id"]." and a.id_municipio::integer=b.codice_mun::integer;";
	//echo "<br>".$query3;
	$result3 = pg_query($conn, $query3);
	while($r3 = pg_fetch_assoc($result3)) {
		$municipi_c[]=array($r1["id"],$r3["nome_munic"]);
	}
	
	
}




# chiamata alla funzione per la raccolta dei request headers 
$headers = getallheaders();
# visualizzazione dei valori dell'array tramite ciclo
foreach ($headers as $name => $content)
{
	# chiamata alla funzione per la raccolta dei request headers 
$headers = getallheaders();
# visualizzazione dei valori dell'array tramite ciclo
foreach ($headers as $name => $content)
{
  //echo "[$name] = $content<br>";
	if ($name=='comge_codicefiscale'){
		$CF=$content;
	}

}
	if ($name=='comge_codicefiscale'){
		$CF=$content;
	}
	

}




//utenti esterni
	$query= "SELECT * FROM users.v_utenti_esterni WHERE cf='".$CF."';";
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$nome = $r['nome'];
		$cognome = $r['cognome'];
		$codfisc = $r['cf'];
		$matricola_cf=$codfisc;
		$operatore=$matricola_cf;
		$uo_inc='uo_'.$r['id1'];
		$livello1=$r['livello1'];
		$id_livello1=$r['id1'];
		$livello2=$r['livello2'];
		$livello3=$r['livello3'];
	}




	//dipendenti
	$query= "SELECT * FROM varie.dipendenti WHERE codice_fiscale='".$CF."';";
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$nome = $r['nome'];
		$cognome = $r['cognome'];
		$matricola = $r['matricola'];
		$matricola_cf=$matricola;
		$livello1=$r['direzione_area'];
		$livello2=$r['settore'];
		$livello3=$r['ufficio'];
	}
	
	
	$_SESSION['user']=$matricola_cf;
	$operatore=$matricola_cf;
	
	
	$query= "SELECT * FROM users.v_utenti_sistema WHERE matricola_cf ='".$matricola_cf."' and valido='t';";
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$profilo_sistema = $r['id_profilo'];
		$descrizione_profilo = $r['descrizione'];
		$profilo_cod_munic = $r['cod_municipio'];
		$privacy = $r['privacy'];
		if($profilo_sistema==5 or $profilo_sistema==6){
			$profilo_nome_munic = $r['nome_munic'];
		}
		if($profilo_sistema==8){
			$uo_inc=$uo_inc;
		} else {
			$uo_inc=0;
		}
	}
	
	$query= "SELECT cod FROM varie.t_incarichi_comune WHERE profilo ='".$profilo_sistema."';";
	//echo $query;
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$periferico_inc = 'com_'.$r['cod'];
	}
	
	
	$query= "SELECT * FROM users.v_componenti_squadre WHERE matricola_cf ='".$matricola_cf."';";
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$id_squadra_operatore = $r['id'];
		$nome_squadra_operatore = $r['nome_squadra'];
	}
	
	//notifiche
	if ($profilo_sistema == 0 and
	basename($_SERVER['PHP_SELF'])!='divieto_accesso.php' and
	basename($_SERVER['PHP_SELF'])!='add_volontario.php') {
		header("location: ./divieto_accesso.php");
	} else {
		if ($privacy =='f')
		{
			?>
			
			

			<!-- Modal mail-->
						<div id="privacy_modal" class="modal fade" role="dialog">
						  <div class="modal-dialog">
						
						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Informativa ex artt.13 e 14 del Regolamento U.E. 2016/679 per le attività previste
 nell’ambito del <i>nuovo sistema informativo unico di gestione delle emergenze</i></h4>
						      </div>
						      <div class="modal-body">
							   <?php require './informativa_dpo.php'; ?>
						        <form autocomplete="off"  enctype="multipart/form-data"  action="accetto_informativa.php?id=<?php echo $matricola_cf; ?>" method="POST">
									
									<div class="form-group">
									    <label for="address">Cliccando qua io sottoscritto 
									    <?php echo $nome.','.$cognome.'('.$matricola_cf.')'?> 
									    ,ho letto ed accetto le presenti condizioni.</label>  <font color="red">*</font>
									    <input type="checkbox" required="" class="form-control" id="address"  name="address" rows="3"></input>
									</div>
									
						
						        <button  id="conferma" type="submit" class="btn btn-primary">Accetta</button>
						            </form>
						
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
						      </div>
						    </div>
						
						  </div>
						</div>
						
						
			
			<?php
		}
		
	}
	
	//notifiche
	if ($profilo_sistema>0 and $profilo_sistema<=3){
		$profilo_ok=3;
	} else {
   $profilo_ok=$profilo_sistema;
   }
	
	//profili per le squadre
	if ($profilo_sistema < 8 and $profilo_ok!=''){
		$profilo_squadre=$profilo_ok;
	} else {
		//$profilo_squadre=$uo_inc;
		$profilo_squadre=$periferico_inc;
	}
	$query2="SELECT * FROM varie.v_incarichi_mail WHERE profilo = '".$profilo_squadre."'::text ORDER BY descrizione;";
	//echo $query2;
	$result2 = pg_query($conn, $query2);
	//echo $query1;    
	while($r2 = pg_fetch_assoc($result2)) { 
		$cod_profilo_squadra=$r2['cod'];
		$descrizione_profilo_squadra=$r2['descrizione'];
	}
	
	
	
	
	// segnalazioni da elaborare (il resto dei conteggi serve solo alla dashboard)
	$query= "SELECT count(id) FROM segnalazioni.v_segnalazioni WHERE in_lavorazione is null;";
	$result = pg_query($conn, $query);
	while($r = pg_fetch_assoc($result)) {
		$segn_limbo = $r['count'];	
	}

	// Conteggi incarichi
	$query= "SELECT  id, descrizione, id_stato_incarico FROM segnalazioni.v_incarichi_last_update where id_stato_incarico<=2 and (id_profilo='".$profilo_ok."' OR id_uo='".$uo_inc."'  OR id_uo='".$periferico_inc."' ) GROUP BY id,descrizione, id_stato_incarico;";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_i_assegnati_resp=array();
	$descrizione_i_assegnati_resp=array();
	$stato_i_assegnati_resp=array();
	while($r = pg_fetch_assoc($result)) {
		$id_i_assegnati_resp[] = $r['id'];
		$descrizione_i_assegnati_resp[] = $r['descrizione'];
		$stato_i_assegnati_resp[] = $r['id_stato_incarico'];
	}
	$i_assegnati_resp = count($id_i_assegnati_resp);


	// Conteggi incarichi interni
	$query= "SELECT  id, descrizione, id_stato_incarico FROM segnalazioni.v_incarichi_interni_last_update where id_stato_incarico<=2 and id_profilo='".$profilo_ok."' GROUP BY id,descrizione, id_stato_incarico;";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_ii_assegnati_resp=array();
	$descrizione_ii_assegnati_resp=array();
	$stato_ii_assegnati_resp=array();
	while($r = pg_fetch_assoc($result)) {
		$id_ii_assegnati_resp[] = $r['id'];
		$descrizione_ii_assegnati_resp[] = $r['descrizione'];
		$stato_ii_assegnati_resp[] = $r['id_stato_incarico'];
	}
	$ii_assegnati_resp = count($id_ii_assegnati_resp);


	// Conteggi sopralluoghi
	$query= "SELECT  id, descrizione, id_stato_sopralluogo FROM segnalazioni.v_sopralluoghi_last_update where id_stato_sopralluogo<=2 and id_profilo='".$profilo_ok."' GROUP BY id,descrizione,id_stato_sopralluogo;";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_s_assegnati_resp=array();
	$descrizione_s_assegnati_resp=array();
	$stato_s_assegnati_resp=array();
	while($r = pg_fetch_assoc($result)) {
		$id_s_assegnati_resp[] = $r['id'];
		$descrizione_s_assegnati_resp[] = $r['descrizione'];
		$stato_s_assegnati_resp[] = $r['id_stato_sopralluogo'];
	}
	$s_assegnati_resp = count($id_s_assegnati_resp);


	// Conteggi sopralluoghi
	$query= "SELECT  id, descrizione, id_stato_sopralluogo FROM segnalazioni.v_sopralluoghi_mobili_last_update where id_stato_sopralluogo<=2 and id_profilo='".$profilo_ok."' GROUP BY id,descrizione, id_stato_sopralluogo;";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_sm_assegnati_resp=array();
	$descrizione_sm_assegnati_resp=array();
	$stato_sm_assegnati_resp=array();
	while($r = pg_fetch_assoc($result)) {
		$id_sm_assegnati_resp[] = $r['id'];
		$descrizione_sm_assegnati_resp[] = $r['descrizione'];
		$stato_sm_assegnati_resp[] = $r['id_stato_sopralluogo'];
	}
	$sm_assegnati_resp = count($id_sm_assegnati_resp);


	// Conteggi provvedimenti cautelari
	$query= "SELECT  id, tipo_provvedimento,id_stato_provvedimenti_cautelari FROM segnalazioni.v_provvedimenti_cautelari_last_update where id_stato_provvedimenti_cautelari<=2 and (id_profilo='".$profilo_ok."' OR id_squadra='".$uo_inc."' OR id_squadra='".$periferico_inc."') GROUP BY id,tipo_provvedimento, id_stato_provvedimenti_cautelari;";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_pc_assegnati_resp=array();
	$tipo_pc_assegnati_resp=array();
	$stato_pc_assegnati_resp=array();
	while($r = pg_fetch_assoc($result)) {
		$id_pc_assegnati_resp[] = $r['id'];
		$tipo_pc_assegnati_resp[] = $r['tipo_provvedimento'];
		$stato_pc_assegnati_resp[] = $r['id_stato_provvedimenti_cautelari'];
	}
	$pc_assegnati_resp = count($id_pc_assegnati_resp);
	
	
	$count_resp=$i_assegnati_resp + $ii_assegnati_resp + $s_assegnati_resp + $sm_assegnati_resp + $pc_assegnati_resp;
	
	
	//******************************************************
	//notifiche squadra
	
	
	// Conteggi incarichi
	$query= "SELECT  * FROM users.v_squadre_notifica WHERE id=".$id_squadra_operatore.";";
	
	$result = pg_query($conn, $query);
	$id_ii_assegnati_squadra=array();
	$id_s_assegnati_squadra=array();
	$id_pc_assegnati_squadra=array();
	$descrizione_i_assegnati_squadra=array();
	while($r = pg_fetch_assoc($result)) {
		if($r['id_incarico_interno'] > 0 ) {
			$id_ii_assegnati_squadra[] = $r['id_incarico_interno'];
		}
		if($r['id_sopralluogo'] > 0 ) {
			$id_s_assegnati_squadra[] = $r['id_sopralluogo'];
		}
		if($r['id_pc'] > 0 ) {
			$id_pc_assegnati_squadra[] = $r['id_pc'];
		}
	}
	$ii_assegnati_squadra = count($id_ii_assegnati_squadra);
	$s_assegnati_squadra = count($id_s_assegnati_squadra);
	$pc_assegnati_squadra = count($id_pc_assegnati_squadra);  
	// Conteggi incarichi
	/*$query= "SELECT  id, tipo_provvedimento FROM segnalazioni.v_incarichi_last_update where id_stato_incarichi<=2 and id_squadra=".$id_squadra_operatore.";";
	
	$result = pg_query($conn, $query);
	$id_i_assegnati_squadra=array();
	$descrizione_i_assegnati_squadra=array();
	while($r = pg_fetch_assoc($result)) {
		$id_i_assegnati_squadra[] = $r['id'];
		$descrizione_i_assegnati_squadra[] = $r['descrizione'];
	}
	$i_assegnati_squadra = count($id_i_assegnati_squadra);


	// Conteggi incarichi interni
	$query= "SELECT  id, tipo_provvedimento FROM segnalazioni.v_incarichi_interni_last_update where id_stato_incarichi<=2 and id_squadra=".$id_squadra_operatore.";";
	//echo $query;
	$result = pg_query($conn, $query);
	$id_ii_assegnati_squadra=array();
	$descrizione_ii_assegnati_squadra=array();
	while($r = pg_fetch_assoc($result)) {
		$id_ii_assegnati_squadra[] = $r['id'];
		$descrizione_ii_assegnati_squadra[] = $r['descrizione'];
	}
	$ii_assegnati_squadra = count($id_ii_assegnati_squadra);


	// Conteggi sopralluoghi
	$query= "SELECT  id, tipo_provvedimento FROM segnalazioni.v_sopralluoghi_last_update where id_stato_sopralluoghi<=2 and id_squadra=".$id_squadra_operatore.";";
	
	$result = pg_query($conn, $query);
	$id_s_assegnati_squadra=array();
	$descrizione_s_assegnati_squadra=array();
	while($r = pg_fetch_assoc($result)) {
		$id_s_assegnati_squadra[] = $r['id'];
		$descrizione_s_assegnati_squadra[] = $r['descrizione'];
	}
	$s_assegnati_squadra = count($id_s_assegnati_squadra);


	// Conteggi provvedimenti cautelari
	$query= "SELECT  id, tipo_provvedimento FROM segnalazioni.v_provvedimenti_cautelari_last_update where id_stato_provvedimenti_cautelari<=2 and id_squadra=".$id_squadra_operatore.";";
	
	$result = pg_query($conn, $query);
	$id_pc_assegnati_squadra=array();
	$tipo_pc_assegnati_squadra=array();
	while($r = pg_fetch_assoc($result)) {
		$id_pc_assegnati_squadra[] = $r['id'];
		$tipo_pc_assegnati_squadra[] = $r['tipo_provvedimento'];
	}
	$pc_assegnati_squadra = count($id_pc_assegnati_squadra);*/
	
	
	$count_squadra = $ii_assegnati_squadra + $s_assegnati_squadra + $pc_assegnati_squadra;
?>       