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
	</head>
  	
	<body>
    	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    	<!-- Include all compiled plugins (below), or include individual files as needed -->
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<!-- <script src="js/bootstrap.min.js"></script> -->



 	<?php
	 	$section_id = 2;                //Definiert die Hauptgruppe (Startseite, Stammdaten, etc..)
	 	$site_id = 2;                   //Definiert die Unterseite/Funktion (Artikelverwaltung, etc..)
	 	include ("header_nav.php");
	?>

	<div class="container">
		<div class="page-header">
  			<h1>Artikel<br><small>Verwalten Sie hier Ihre Artikel.</small></h1>
		</div>

		<?php
			mainScript();
		?>
	</div>

	<footer class="footer">
    	<div class="container">
	 		<div class="row">
		 		<div class="col-md-3">
			 		<p class="text-muted">Client: <?php echo $_SERVER['REMOTE_ADDR'] ?></p>
		 		</div>
	 		</div>
      	</div>
    </footer>

  	</body>
</html>

<?php
	function deleteSuccessfull() {
		include 'html-parts/messages/partman_deletesuccessfull.php';
	}

	function updateSuccessfull() {
		include 'html-parts/messages/partman_updatesuccessfull.php';
	}

	function mainScript() {
		try {
			$pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');
			//Wenn Formulardaten übermittelt wurden, dann versuchen diese zu speichern...
			if (isset($_POST['partindex'])) {
				//Versuche Formulardaten zu speichern...
				$statement = $pdo->prepare("UPDATE bdat_parts SET vchar_mannumber = :mannumber_new, vchar_name = :partname_new, float_weight = :weight_new, vchar_defaultimg = :defaultimg_new WHERE int_index = :index");
				$statement->execute(array('index' => $_POST['partindex'], 'mannumber_new' => $_POST['mannumber'], 'partname_new' => $_POST['partname'], 'weight_new' => $_POST['partweight'], 'defaultimg_new' => $_POST['defaultimg']));

				//Erfolgsmeldung anzeigen...
				updateSuccessfull();
				 } else {
						//Wurden keine Formulardaten übermitteln, dann je nach gewünschter Unit
						//die entsprechenden Operationen ausführen, und dann zurück zur letzten Seite.
						//Unitindex:
						//      0 = Overview
						//      1 = Artikel Basiseditor
						$unit = $_GET['unit'];
						if ($unit == 0) {
							if (isset($_GET['action']) AND $_GET['action'] == 0) {
								$statement = $pdo->prepare("DELETE FROM bdat_parts WHERE int_index = ?");
								$statement->execute(array($_GET['index']));
								//Erfolgsmeldung anzeigen...
								deleteSuccessfull();
							}
						}
						if ($unit == 1) {
							include ("partmngmt_editpart.php"); }
			}   
			$pdo = null;
		   } catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
?>