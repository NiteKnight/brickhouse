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

<!-- <script src="js/bootstrap.min.js"></script> -->



 <?php
         $section_id = 2;                //Definiert die Hauptgruppe (Startseite, Stammdaten, etc..)
         $site_id = 2;                   //Definiert die Unterseite/Funktion (Artikelverwaltung, etc..)
         include ("header_nav.php");
 ?>


<div class="container">
<div class="page-header">
  <h1>Artikel <small>Verwalten Sie hier Ihre Artikel.</small></h1>
</div>


<?php
         try {
                 $pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');
                 if (isset($_GET['index']) == false) {

                         $statement = $pdo->prepare("INSERT INTO bdat_parts (vchar_mannumber, vchar_name, float_weight, vchar_defaultimg) VALUES (?, ?, ?, ?)");
                         $statement->execute(array('0000', 'Neuer Artikel', 0.0,'default.png'));

                         $index = $pdo->lastInsertId();
                 } else {
                          $index = $_GET['index'];
                         }

                 $sql = "SELECT vchar_mannumber, vchar_name, float_weight, vchar_defaultimg FROM bdat_parts WHERE int_index = ".$index;
                 $row = $pdo->query($sql)->fetch();
                 $pdo = null;
                 $nbr = $row['vchar_mannumber'];

                 //Herstellernummer auf die gänderte setzen...
                 if (isset($_GET['mannbr'])) {
                         $row['vchar_mannumber'] = $_GET['mannbr'];
                         $nbr = $row['vchar_mannumber'];
                 }
                 if (isset($_GET['pname'])) {
                         $row['vchar_name'] = $_GET['pname'];
                         $nbr = $row['vchar_name'];
                 }
                 if (isset($_GET['pweight'])) {
                         $row['float_weight'] = $_GET['pweight'];
                         $nbr = $row['float_weight'];
                 }

                 //Die Dateinamen aller Artikelbilder in ein Array lesen...
                 $allfiles = scandir('images/parts'); //Ordner "files" auslesen
                 //Das vorher erstellte Array nach Dateien mit der gewünschten Herstellernummer filtern..
                 $filteredNames = array_filter($allfiles, function($name)use($nbr) {
                                                 return strpos($name, (string)$nbr) !== FALSE;
                 });

                 $img_dir = "images/parts/";
                 if ($row['vchar_defaultimg'] == NULL) {
                         $img_dir = "images/misc/noimage.png";
                 } else {
                         $img_dir = "images/parts/" . $row['vchar_defaultimg'];
                 }

                 if (count($filteredNames) == 0) {
                         $img_dir = "images/misc/noimage.png";
                 }

         } catch (PDOException $e) {
                 print "Error!: " . $e->getMessage() . "<br/>";
                 die();
         }
?>

<form action="partman_operations.php" method="post">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Basisdaten</h3>
  </div>
  <div class="panel-body">
         <div class="row">
                 <div class="col-md-2">
                      <img class="img-responsive" src="<?php echo $img_dir; ?>" id="PartImage">
                 </div>
                 <div class="col-md-10">
                         <div class="row">
                                 <div class="col-md-4">
                                         <div class="form-group">
                                                 <label for="ColorNumber">Herstellernr. <small>(LEGO Artikelnummer)</small></label>
                                                 <input type="text" name="mannumber" class="form-control" id="ManufacturerNumber" value="<?php echo $row['vchar_mannumber']; ?>" onchange="reloadFilelisting()">
                                         </div>
                                 </div>
                                 <div class="col-md-8">
                                         <div class="form-group">
                                                 <label for="PartName">Bezeichnung</label>
                                                 <input type="text" name="partname"  class="form-control" id="PartName" value="<?php echo $row['vchar_name']; ?>">
                                         </div>
                                 </div>
                         </div>
                         <div class="row">
                                 <div class="col-md-3">
                                         <div class="form-group">
                                                 <label for="PartWeight">Gewicht <small>(in Gramm)</small></label>
                                                 <div class="input-group">
                                                         <input type="text" name="partweight"  class="form-control" style="text-align:right;" id="PartWeight" value="<?php echo $row['float_weight']; ?>">
                                                         <span class="input-group-addon" id="basic-addon2">g</span>
                                                 </div>
                                         </div>
                                 </div>
                                 <div class="col-md-4">
                                         <div class="form-group">
                                                 <label for="DefaultImage">Standardbild</label>
                                                  <select name="defaultimg" id="DefaultImage" class="form-control" onchange="changePartImage()">
                                                         <?php
                                                                 foreach($filteredNames as $element){
                                                                         echo "<option value='".$element."'>".$element."</option>";
                                                                 }
                                                                 echo "<option value='".NULL."'>Kein Bild</option>";
                                                         ?>
                                                  </select>
                                         </div>
                                 </div>
                                 <div class="col-md-4">
                                      <div class="form-group">
                                           <label for="ImageFile">Bild hinzuf&uuml;gen</label>
                                           <input type="file" id="ImageFile">
                                           <p class="help-block">Nur Bilddateien</p>
                                      </div>
                                 </div>
                         </div>
                 </div>
         </div>
         <div class="row">
              <div class="col-md-12">
                 <div class="panel panel-default">
                         <div class="panel-heading">
                                 <h3 class="panel-title">Artikelvarianten</h3>
                         </div>
                         <div class="panel-body">
                                 Panel content
                         </div>
                 </div>
              </div>
         </div>
  </div>
</div>

         <div class="row">
                 <div class="col-md-2">
                      <input type="hidden" name="partindex" value="<?php echo $index ?>">
                      <button type="submit" class="btn btn-primary">Speichern</button>
                 </div>
                 <div class="col-md-2">
                      <a href='partman_overview.php' class='btn btn-default enabled' role='button' aria-disabled='true'>Zur&uuml;ck</a>
                 </div>
         </div>
</form>

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