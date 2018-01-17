<!DOCTYPE html>
<html lang="de">
  	<head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Custom styles for this template -->
    	<link href="css/sticky-footer.css" rel="stylesheet">
    	<link href="css/tables.css" rel="stylesheet">

		<!-- <link href="css/grid.css" rel="stylesheet"> -->


    	<title>BrickHouse 0.1.001a</title>

    	<!-- Bootstrap -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    	<!--[if lt IE 9]>
      	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    	<![endif]-->

   		<script>
        	function reloadFilelisting() {
            	tbxManNumber = document.getElementById("ManufacturerNumber");
            	tbxPartname = document.getElementById("PartName");
            	tbxWeight = document.getElementById("PartWeight");
            	window.document.location.href = "partman_editbase.php?index=<?php echo $_GET['index'] ?>&mannbr=" + tbxManNumber.value + "&pname=" + tbxPartname.value + "&pweight=" + tbxWeight.value;
        	}

        	function changePartImage() {
            	imgPartImage = document.getElementById("PartImage");
            	strFilename = document.getElementById("DefaultImage").value;
            	if (strFilename == "") {
                	imgPartImage.src = "images/misc/noimage.png";
            		} else {
                    			imgPartImage.src = "images/parts/" + strFilename;
            	}
         	}
   		</script>
	</head>
	<body>
    	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    	<!-- Include all compiled plugins (below), or include individual files as needed -->
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
		<?php
    		$section_id = 2;                //Definiert die Hauptgruppe (Startseite, Stammdaten, etc..)
    		$site_id = 2;                   //Definiert die Unterseite/Funktion (Artikelverwaltung, etc..)
    		include ("header_nav.php");

			try {
				$pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');
				if (isset($_GET['index']) == false) {
					$statement = $pdo->prepare("INSERT INTO bdat_parts (vchar_mannumber, vchar_name, float_weight, vchar_defaultimg) VALUES (?, ?, ?, ?)");
					$statement->execute(array('0000', 'Neuer Artikel', 0.0,'default.png'));
					$index = $pdo->lastInsertId();
					} else {
						  	$index = $_GET['index'];
		  		}
  
				//Artikel-Basisdaten abfragen...  
				$sql = "SELECT vchar_mannumber, vchar_name, float_weight, vchar_defaultimg FROM bdat_parts WHERE int_index = ".$index;
				$row = $pdo->query($sql)->fetch();
				$nbr = $row['vchar_mannumber'];
				//Basisdaten der Artikelvarianten abfragen...  
			  	$sql = $pdo->prepare("SELECT `bdat_variations`.`int_index` AS `varindex`, `bdat_variations`.`vchar_artnumber` AS `artnumber`, UNIX_TIMESTAMP(`bdat_variations`.`time_created`) AS `varcreated`, UNIX_TIMESTAMP(`bdat_variations`.`time_changed`) AS `varchanged`, `bdat_variations`.`float_wantedprice` AS `varprice`, `bdat_variations`.`vchar_image` AS `varimage`, `bdat_variations`.`vchar_description` AS `vardesc`, `bdat_colors`.`vchar_name` AS `varcolname` FROM `bdat_colors`
					LEFT JOIN `bdat_variations` ON `bdat_variations`.`int_colindex` = `bdat_colors`.`int_index`
				  	WHERE int_partindex = ?
					ORDER BY `bdat_variations`.`int_index` ASC");
				$sql->bindParam(1, $index);
				if ($sql->execute() != false) {
					$variantrows = $sql->fetchAll();
					//print_r($variantrows);
					} else {
						//echo 'Kein Ergebnis';
				} 
			  	$pdo = null;

  
		  		//Herstellernummer auf die gänderte setzen...
		  		if (isset($_GET['mannbr'])) {
			  		$row['vchar_mannumber'] = $_GET['mannbr'];
			  		$nbr = $row['vchar_mannumber'];
		  		}
		  		if (isset($_GET['pname'])) {
			  		$row['vchar_name'] = $_GET['pname'];
		  		}
		  		if (isset($_GET['pweight'])) {
			  		$row['float_weight'] = $_GET['pweight'];
		  		}
  
				//Die Dateinamen aller Artikelbilder in ein Array lesen...
				$allfiles = scandir('images/parts'); //Ordner "images/parts" auslesen
				//Das vorher erstellte Array nach Dateien mit der gewünschten Herstellernummer filtern..
				$filteredNames = array_filter($allfiles, function($name)use($nbr) {
				return strpos($name, (string)$nbr) !== FALSE;
				});
  
				$img_dir = "images/parts/";
				if ($row['vchar_defaultimg'] == NULL Or count($filteredNames) == 0) {
					$img_dir = "images/misc/noimage.png";
					} else {
							if (strpos($row['vchar_defaultimg'],$nbr) === FALSE) {
								$fnames = array_values($filteredNames);
								$img_dir = "images/parts/" . $fnames[0];
							} else {
								$img_dir = "images/parts/" . $row['vchar_defaultimg'];
							}
				}    
		   	} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}	
  		?>
		<div class="container">
			<form action="partman_operations.php" method="post">

				<div class="panel panel-primary">
  					<div class="panel-heading">
    					<h3 class="panel-title">Stammdaten >> Artikel >> Basisdaten</h3>
  					</div>
  					<div class="panel-body">
         				<div class="row">
                			<div class="col-xs-2">
                    			<img class="img-responsive" src="<?php echo $img_dir; ?>" id="PartImage">
                 			</div>
                			<div class="col-xs-10">
                    			<div class="row">
                        			<div class="col-xs-4">
                            			<div class="form-group">
                                			<label for="ColorNumber">Herstellernr. <small>(LEGO Artikelnummer)</small></label>
                                			<input type="text" name="mannumber" class="form-control input-sm" id="ManufacturerNumber" value="<?php echo $row['vchar_mannumber']; ?>" onchange="reloadFilelisting()">
                            			</div>
                        			</div>
                        			<div class="col-xs-6">
                            			<div class="form-group">
                                			<label for="PartName">Bezeichnung</label>
                                			<input type="text" name="partname"  class="form-control input-sm" id="PartName" value="<?php echo $row['vchar_name']; ?>">
                            			</div>
                        			</div>
									<div class="col-xs-2">
										<input type="hidden" name="partindex" value="<?php echo $index ?>">
										<button type="submit" class="btn btn-primary btn-block"><span class='glyphicon glyphicon-floppy-disk' aria-hidden='true'></span> Speichern</button>
									</div>
								</div>
                    			<div class="row">
                    				<div class="col-xs-3">
                            			<div class="form-group">
                                			<label for="PartWeight">Gewicht <small>(in Gramm)</small></label>
                                			<div class="input-group">
                                    			<input type="text" name="partweight"  class="form-control input-sm" style="text-align:right;" id="PartWeight" value="<?php echo $row['float_weight']; ?>">
                                    			<span class="input-group-addon" id="basic-addon2">g</span>
                                			</div>
                            			</div>
                        			</div>
									<div class="col-xs-3">
									</div>
                        			<div class="col-xs-4">
                            			<div class="form-group">
                                			<label for="DefaultImage">Standardbild</label>
                            				<select name="defaultimg" id="DefaultImage" class="form-control input-sm" onchange="changePartImage()">
                                				<?php
                                    				foreach($filteredNames as $element){
														if ($element == $row['vchar_defaultimg']) {
															echo "<option value='".$element."' selected>".$element."</option>";															
															} else {
																	echo "<option value='".$element."'>".$element."</option>";
														}
                                    				}
                                    				echo "<option value='".NULL."'>Kein Bild</option>";
                                				?>
                                			</select>
                            			</div>
                        			</div>
									<div class="col-xs-2">
                    					<a href='partman_overview.php' class='btn btn-default btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-triangle-left' aria-hidden='true'></span> Zur&uuml;ck</a>
                					</div>									
                    			</div>
                			</div>
         				</div>
         				<div class="row">
              				<div class="col-xs-12">
                 				<div class="panel panel-info">
                         			<div class="panel-heading">
										<h3 class="panel-title">Artikelvarianten</h3>
                         			</div>
                                		<!-- Variantenübersichtstabelle ab hier... -->
											<table class="table-mytable table-striped table-hover table-condensed">
         										<thead>
                									<tr class="bg-info">
                         								<td scope="col" class="col-xs-1"></td>
                         								<td scope="col" class="col-xs-4"><b>Beschreibung</b><br>
														 								<small>Farbe</small><br>
																						<small><i>Artikelnummer</i><small></td>
                         								<td scope="col" class="col-xs-1 align-top"><b>Preis</b></td>
                         								<td scope="col" class="col-xs-2 align-top"><b>zul. Bearbeitet</b></td>
                         								<td scope="col" class="col-xs-2 align-top"><b>Aktionen</b></td>
                         								<td scope="col" class="col-xs-1 align-top">
															<br><a href='colormngmt.php?mode=1&action=1' class='btn btn-primary btn-sm enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-asterisk' aria-hidden='true'></span> Variante hinzufügen</a>
														</td>
                 									</tr>
         										</thead>
												<tbody>
													<?php
                 										foreach($variantrows AS $vrow) {
															if ($vrow['varimage'] == NULL) {
																$vrow['varimage'] = "images/misc/noimage.png";
																} else {
																	$vrow['varimage'] = "images/parts/" . $vrow['varimage'];
																}
                         									echo "<tr><td class='col-xs-1'><img class='img-responsive img-xs' src='".$vrow['varimage']."' alt=''></td>";
															 echo "<td class='col-xs-4'><b>".$vrow['vardesc']."</b><br>
																	 <small>".$vrow['varcolname']."<br><i>".
																	 $vrow['artnumber']."</i></small>";
															echo "<td class='col-xs-1' align='left'>€ ".$vrow['varprice']."</td>";
															$datum = date("d.m.Y - H:i:s", $vrow['varchanged']);
                         									echo "<td class='col-xs-2'>".$datum."</td>";
                         									echo "<td class='col-xs-2'><a href='partman_editbase.php?index=".$vrow['varindex']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Bearbeiten</a></td>";
                         									echo "<td class='col-xs-2'><a href='partman_operations.php?unit=0&action=0&index=".$vrow['varindex']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> L&ouml;schen</a></td></tr>";
                 										}
													?>
												</tbody>
											</table>
								</div>
              				</div>
         				</div>
  					</div>
				</div>
			</form>
		</div>

		<footer class="footer">
      		<div class="container">
         		<div class="row">
                 	<div class="col-xs-3">
                        <p class="text-muted">Client: <?php echo $_SERVER['REMOTE_ADDR'] ?></p>
                 	</div>
         		</div>
      		</div>
    	</footer>
  	</body>
</html>