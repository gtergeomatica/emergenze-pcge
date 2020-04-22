<?php 

$subtitle="Elenco segnalazioni pervenute (eventi attivi e/o in fase di chiusura)";

if(isset($_GET["f"])){
	$getfiltri=$_GET["f"];
}
if(isset($_GET["a"])){
	$filtro_evento_attivo=$_GET["a"];
}
if(isset($_GET["m"])){
	$filtro_municipio=$_GET["m"];
}
if(isset($_GET["from"])){
	$filtro_from=$_GET["from"];
}
if(isset($_GET["to"])){
	$filtro_to=$_GET["to"];
}
if(isset($_GET["r"])){
	$resp=$_GET["r"];
}

//echo $filtro_evento_attivo; 


$uri=basename($_SERVER['REQUEST_URI']);
//echo $uri;

$pagina=basename($_SERVER['PHP_SELF']); 

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="roberto" >

    <title>Gestione emergenze</title>
<?php 
require('./req.php');

require('/home/local/COMGE/egter01/emergenze-pcge_credenziali/conn.php');

require('./check_evento.php');

require('./tables/filtri_segnalazioni.php');
?>
    
</head>

<body>

    <div id="wrapper">

        <?php 
            require('./navbar_up.php')
        ?>  
        <?php 
            require('./navbar_left.php')
        ?> 
            

        <div id="page-wrapper">
            <!--div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Elenco segnalazioni</h1>
                </div>
            </div-->

            
            <br><br>
            <div class="row">

<p>
            <a class="btn btn-primary" data-toggle="collapse" href="#collapsecriticita" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-filter"></i>  Filtra per criticità
          </a>
		  
		  <a class="btn btn-primary" data-toggle="collapse" href="#collapsemunicipio" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-home"></i>  Filtra per municipio
          </a>
		  
		  <a class="btn btn-primary" data-toggle="collapse" href="#collapsedata" role="button" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-hourglass"></i>  Filtra per data
          </a>
			<?php if($filtro_evento_attivo==1){?>
			<a class="btn btn-primary" href="./elenco_segnalazioni.php?r=<?php echo $resp;?>&m=<?php echo $filtro_municipio?>&f=<?php echo $getfiltri?>&to=<?php echo $filtro_to?>&from=<?php echo $filtro_from?>">
            <i class="fas fa-play"></i> <i class="fas fa-pause"></i> Vedi tutti gli eventi (non solo gli attivi)
          </a>
			<?php } else {?>  
           <a class="btn btn-primary" href="./elenco_segnalazioni.php?r=<?php echo $resp;?>&a=1&m=<?php echo $filtro_municipio?>&f=<?php echo $getfiltri?>&to=<?php echo $filtro_to?>&from=<?php echo $filtro_from?>">
            <i class="fas fa-play"></i> Vedi solo eventi attivi
          </a>
		  <?php }?> 
		  
		  <?php if($resp!=''){?>
			<a class="btn btn-primary" href="./<?php echo $pagina?>?u=<?php echo $uo?>&to=<?php echo $filtro_to?>&from=<?php echo $filtro_from?>&f=<?php echo $getfiltri?>&a=<?php echo $filtro_evento_attivo?>&m=<?php echo $filtro_municipio?>">
            <i class="fas fa-users"></i> Vedi tutte le segnalazioni 
            (non solo quelle di cui sei responsabile)
          </a>
			<?php } else {?>  
           <a class="btn btn-primary" href="./<?php echo $pagina?>?r=<?php echo $profilo_ok?>&u=<?php echo $uo?>&to=<?php echo $filtro_to?>&from=<?php echo $filtro_from?>&f=<?php echo $getfiltri?>&a=<?php echo $filtro_evento_attivo?>&m=<?php echo $filtro_municipio?>">
            <i class="fas fa-user-check"></i> Vedi solo le segnalazioni di cui sei responsabile
          </a>
		  <?php }?>
        </p>
        
		
		


		<div class="collapse" id="collapsecriticita">
          <div class="card card-body">
         		  
          <form id="filtro_cr" action="./tables/decodifica_filtro0.php?r=<?php echo $resp;?>&a=<?php echo $filtro_evento_attivo?>&from=<?php echo $filtro_from?>&to=<?php echo $filtro_to?>&m=<?php echo $filtro_municipio?>" method="post">
            <input type="hidden" name="pagina" id="hiddenField" value="<?php echo $pagina; ?>" />
			
			<?php
            $query='SELECT * FROM segnalazioni.tipo_criticita where valido=\'t\' ORDER BY descrizione;';
            $result = pg_query($conn, $query);
	         #echo $result;
	         //exit;
	         //$rows = array();
            //echo '<div class="form-check form-check-inline">';
            echo '<div class="row">';
	         while($r = pg_fetch_assoc($result)) {
					echo '<div class="form-check col-md-3">';
	            echo '  <input class="form-check-input" type="checkbox" id="filtro_cr" name="filter'.$r['id'].'"  value=1" >';
	            echo '  <label class="form-check-label" for="inlineCheckbox1">'.$r['descrizione'].'</label>';
	            echo "</div>";
	            
            }
            echo "</div>";

        ?>
        <!--hr-->
		
			<button id="checkBtn_filtri" type="submit" class="btn btn-primary"> 
			<?php if ($getfiltri=='' or intval($getfiltri)==0) {?>
				Filtra 
			<?php } else {?>
				Aggiorna filtro
			<?php }?>
			</button>
			

        </form>
          </div>
        </div>
		
		<div class="collapse" id="collapsemunicipio">
          <div class="card card-body">
         		  
          <form id="filtro_mun" action="./tables/decodifica_filtro1.php?r=<?php echo $resp;?>&a=<?php echo $filtro_evento_attivo?>&from=<?php echo $filtro_from?>&to=<?php echo $filtro_to?>&f=<?php echo $getfiltri?>" method="post">
            <input type="hidden" name="pagina" id="hiddenField" value="<?php echo $pagina; ?>" />
			<?php
            $query='SELECT * FROM geodb.municipi ORDER BY codice_mun;';
            $result = pg_query($conn, $query);
	         #echo $result;
	         //exit;
	         //$rows = array();
            //echo '<div class="form-check form-check-inline">';
            echo '<div class="row">';
	         while($r = pg_fetch_assoc($result)) {
					echo '<div class="form-check col-md-3">';
	            echo '  <input class="form-check-input" type="checkbox" id="filtro_mun" name="filter'.$r['codice_mun'].'"  value=1" >';
	            echo '  <label class="form-check-label" for="inlineCheckbox1">'.$r['codice_mun'].' - '.$r['nome_munic'].'</label>';
	            echo "</div>";
	            
            }
            echo "</div>";

        ?>
        <!--hr-->
		
			<button id="checkBtn_filtri" type="submit" class="btn btn-primary"> 
			<?php if ($getfiltri=='' or intval($getfiltri)==0) {?>
				Filtra 
			<?php } else {?>
				Aggiorna filtro
			<?php }?>
			</button>
			

        </form>
          </div>
        </div>
		
		
		
		<div class="collapse" id="collapsedata">
          <div class="card card-body">
         		  
          <form id="filtro_data" action="./tables/decodifica_filtro2.php?r=<?php echo $resp;?>&a=<?php echo $filtro_evento_attivo?>&m=<?php echo $filtro_municipio?>&f=<?php echo $getfiltri?>" method="post">
            <input type="hidden" name="pagina" id="hiddenField" value="<?php echo $pagina; ?>" />
			
				<div class="form-check col-md-6">
				<label for="startdate">Da (AAAA/MM/GG HH:MM):</label>
				<input type="text" class="form-control" id="startdate" name="startdate" value=<?php echo str_replace("'", "", $filtro_from)?>>
				<small id="sdateHelp" class="form-text text-muted"> Inserire la data e l'ora (opzionale)</small>
				</div>
				
				
				<div class="form-check col-md-6">
				<label for="todate">A (AAAA/MM/GG HH:MM):</label>
				<input type="text" class="form-control" id="todate" name="todate" value=<?php echo str_replace("'", "", $filtro_to)?>>
				<small id="tdateHelp" class="form-text text-muted"> Inserire la data e l'ora (opzionale)</small>
				</div>
			
			
			<button id="checkBtn_filtri" type="submit" class="btn btn-primary"> 
			<?php if ($getfiltri=='' or intval($getfiltri)==0) {?>
				Filtra 
			<?php } else {?>
				Aggiorna filtro
			<?php }?>
			</button>
			

        </form>
          </div>
        </div>
		
		
		
		
		
		
        <hr>
			<?php
			if (filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[1]>0 or filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[2]>0 or $filtro_evento_attivo!='') {
			    echo '<i class="fas fa-filter"></i> I dati visualizzati sono filtrati';
				if (filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[1]>0){
					echo ' per criticità '.filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[3].',';
				}
				if (filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[2]>0){
					echo ' per municipio '.filtro2($getfiltri, $filtro_municipio, $filtro_from, $filtro_to)[4].',';
				}
				
				echo '. Per modificare il filtro usa i dati qua sopra.';
			?>
			<br><br>
			<a class="btn btn-primary" href="<?php echo $pagina; ?>">
            <i class="fas fa-redo-alt"></i> Torna a visualizzare tutte le segnalazioni
          </a>
          <hr>
			<?php			
			} else {
				echo ' <i class="fas fa-list-ul"></i> Dati completi';
			}
			
			?>

			
        <div id="toolbar">
            <select class="form-control">
                <option value="">Esporta i dati visualizzati</option>
                <option value="all">Esporta tutto (lento)</option>
                <option value="selected">Esporta solo selezionati</option>
            </select>
        </div>
        <div style="overflow-x:auto;">

      	<?php if ($filtro_evento_attivo == 1){
      	?>
        <table  id="segnalazioni" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" 
		data-url="./tables/griglia_segnalazioni_eventi_attivi.php?r=<?php echo $resp;?>&f=<?php echo $getfiltri;?>&from=<?php echo $filtro_from; ?>&to=<?php echo $filtro_to;?>&m=<?php echo $filtro_municipio;?>" 
		 data-show-export="true" data-search="true" data-click-to-select="true" data-pagination="true" 
		data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar">
      	<?php } else { ?>
        <table  id="segnalazioni" class="table-hover" data-toggle="table" data-filter-control="true" 
  data-show-search-clear-button="true" 
  data-url="./tables/griglia_segnalazioni.php?r=<?php echo $resp;?>&f=<?php echo $getfiltri;?>&from=<?php echo $filtro_from; ?>&to=<?php echo $filtro_to;?>&m=<?php echo $filtro_municipio;?>" 
   data-show-export="true" data-search="true" data-click-to-select="true" data-pagination="true" 
  data-sidePagination="true" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-toolbar="#toolbar">
			<?php } ?>

        
        
<thead>

 	<tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-field="id" data-sortable="true" data-formatter="nameFormatterEdit" data-visible="true" data-filter-control="input">Numero</th>
            <th data-field="in_lavorazione" data-sortable="true" data-formatter="nameFormatter" data-visible="true" data-filter-control="select">Stato</th> 
            <th data-field="rischio" data-sortable="true" data-formatter="nameFormatterRischio" data-visible="true">Persone<br>a rischio</th>
            <th data-field="criticita" data-sortable="true"   data-visible="true" data-filter-control="select">Tipo<br>criticità</th>
            <th data-field="data_ora" data-sortable="true"  data-visible="true">Data e ora</th>
            <th data-field="descrizione" data-sortable="true"  data-visible="true">Descrizione</th>
            <th data-field="nome_munic" data-sortable="true"  data-visible="true" data-filter-control="select">Municipio</th>
            <th data-field="localizzazione" data-sortable="true"  data-visible="true" data-filter-control="input" >Civico</th>
            <th data-field="id2" data-sortable="false" data-formatter="nameFormatterMappa1" data-visible="true"> Anteprima<br>mappa</th>
            <th data-field="note" data-sortable="false" data-visible="true" >Note</th>
            <th data-field="id_evento" data-sortable="true"  data-visible="true" data-filter-control="select">Id<br>evento</th>
            <th data-field="tipo_evento" data-sortable="true"  data-visible="true" data-filter-control="select">Tipo<br>evento</th>

    </tr>
</thead>

</table>
</div>



<br><br>

<script>

 function nameFormatterBKP(value) {
        if (value=='t'){
        		return '<i class="fas fa-play" style="color:#5cb85c"></i> in lavorazione';
        } else if (value=='f') {
        	   return '<i class="fas fa-stop"></i> chiusa';
        } else {
        	   return '<i class="fas fa-exclamation" style="color:#ff0000"></i> da prendere in carico';;
        }

    }

 function nameFormatter(value) {
        if (value=='t'){
        		return 'in lavorazione';
        } else if (value=='f') {
        	   return 'chiusa';
        } else {
        	   return 'da prendere in carico';;
        }

    }
    
 function nameFormatterEdit(value) {
        
		return '<a class="btn btn-warning" href=./dettagli_segnalazione.php?id='+value+'> '+value+' </a>';
 
    }

  function nameFormatterRischio(value) {
        //return '<i class="fas fa-'+ value +'"></i>' ;
        
        if (value=='t'){
        		return '<i class="fas fa-exclamation-triangle" style="color:#ff0000"></i>';
        } else if (value=='f') {
        	   return '<i class="fas fa-check" style="color:#5cb85c"></i>';
        }
        else {
        		return '<i class="fas fa-question" style="color:#505050"></i>';
        }
    }


function nameFormatterMappa1(value, row) {
	//var test_id= row.id;
	return' <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myMap'+value+'"><i class="fas fa-map-marked-alt"></i></button> \
    <div class="modal fade" id="myMap'+value+'" role="dialog"> \
    <div class="modal-dialog"> \
      <div class="modal-content">\
        <div class="modal-header">\
          <button type="button" class="close" data-dismiss="modal">&times;</button>\
          <h4 class="modal-title">Anteprima segnalazione '+value+'</h4>\
        </div>\
        <div class="modal-body">\
        <iframe class="embed-responsive-item" style="width:100%; padding-top:0%; height:600px;" src="./mappa_leaflet.php#17/'+row.lat +'/'+row.lon +'"></iframe>\
        </div>\
        <!--div class="modal-footer">\
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
        </div-->\
      </div>\
    </div>\
  </div>\
</div>';
}
	
	





</script>





            </div>
            <!-- /.row -->
    </div>
    <!-- /#wrapper -->



<?php 

require('./footer.php');

require('./req_bottom.php');


?>


    
</body>

<script>
    // DA MODIFICARE NELLA PRIMA RIGA L'ID DELLA TABELLA VISUALIZZATA (in questo caso t_volontari)
    $(function () {
    	var $table = $('#segnalazioni');
        $('#toolbar').find('select').change(function () {
            $table.bootstrapTable('destroy').bootstrapTable({
                exportDataType: $(this).val()
            });
        });
    });
	
	
	

$(document).ready(function(){
    $("form[id=filtro_cr]").submit(function(){
		if ($('input[type=checkbox][id=filtro_cr]').filter(':checked').length < 1){
        alert("Seleziona almeno una criticità!");
		return false;
		}
    });
});

$(document).ready(function(){
    $("form[id=filtro_mun]").submit(function(){
		if ($('input[type=checkbox][id=filtro_mun]').filter(':checked').length < 1){
        alert("Seleziona almeno un municipio!");
		return false;
		}
    });
});
</script>

</html>
