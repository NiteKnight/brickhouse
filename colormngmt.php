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
         function checkInput() {
                 var tbxHEXCode = document.getElementById("HEXCode");
                 var chooseColValue = document.getElementById("ColorValue");
                 tbxHEXCode.value = tbxHEXCode.value.toUpperCase();
                 if (tbxHEXCode.value.match(/^#[\da-f]{3}([\da-f]{3}|)$/i)) {
                         chooseColValue.value = tbxHEXCode.value
                 } else {
                         alert("Der HEX-Code ist ungültig!");
                 }
         }

         function updateTextbox() {
                 var tbxHEXCode = document.getElementById("HEXCode");
                 var chooseColValue = document.getElementById("ColorValue");
                 tbxHEXCode.value = chooseColValue.value;
                 tbxHEXCode.value = tbxHEXCode.value.toUpperCase();
         }
</script>
  </head>
  <body>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- <script src="js/bootstrap.min.js"></script> -->



 <?php
         $section_id = 2;                //Definiert die Hauptgruppe (Startseite, Stammdaten, etc..)
         $site_id = 1;                   //Definiert die Unterseite/Funktion (Artikelverwaltung, etc..)
         include ("header_nav.php");
 ?>


<div class="container">
<div class="page-header">
  <h1>Farben<br><small>Verwalten Sie hier die m&ouml;glichen Farben Ihrer Artikel.</small></h1>
</div>


<?php
         try {
                 $pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');
                 //Wenn Formulardaten übermittelt wurden, dann versuchen diese zu speichern...
                 if (isset($_POST['colindex'])) {
                         //Versuche Formulardaten zu speichern...
                         $statement = $pdo->prepare("UPDATE bdat_colors SET int_number = :colnumber_new, vchar_name = :colname_new, vchar_colcode = :colcode_new WHERE int_index = :index");
                         $statement->execute(array('index' => $_POST['colindex'], 'colnumber_new' => $_POST['colnumber'], 'colname_new' => $_POST['colname'], 'colcode_new' => $_POST['colvalue']));

                         //Farbübersicht laden...
                         include ("colormngmt_overview.php");
                  } else {
                         //Wurden keine Formulardaten übermitteln, dann je nach gewünschtem Modus
                         //die Übersicht, oder den Editor anzeigen...
                         $mode = $_GET['mode'];
                         if ($mode == 0) {
                                 if (isset($_GET['action']) AND $_GET['action'] == 0) {
                                         $statement = $pdo->prepare("DELETE FROM bdat_colors WHERE int_index = ?");
                                         $statement->execute(array($_GET['index']));
                                 }

                                 include ("colormngmt_overview.php");

                        }
                         if ($mode == 1) {
                                 include ("colormngmt_editcolor.php"); }
                 }

         $pdo = null;
        } catch (PDOException $e) {
                 print "Error!: " . $e->getMessage() . "<br/>";
                 die();
         }
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