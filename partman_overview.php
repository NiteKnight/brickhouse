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
         error_reporting(E_ALL);
         try {
         $pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');

         $statement = $pdo->prepare("SELECT COUNT(*) AS anzahl FROM bdat_parts");
         $statement->execute();
         $anz = $statement->fetch();
         if ($anz['anzahl'] > 0 ) {
                 $sql = "SELECT int_index, vchar_mannumber, vchar_name, float_weight, vchar_defaultimg FROM bdat_parts";
                 $rows = $pdo->query($sql);
                 $pdo = null;
?>

<div class="panel panel-default">
<table class="table table-striped table-hover table-fixed">
         <thead>
                 <tr>
                         <th scope="col" class="col-xs-1"></th>
                         <th scope="col" class="col-xs-2">Herst.-Nummer</th>
                         <th scope="col" class="col-xs-2">Benennung</th>
                         <th scope="col" class="col-xs-2">Gewicht</th>
                         <th scope="col" class="col-xs-5" colspan="2">Aktionen</th>
                 </tr>
         </thead>
<tbody><?php
                 foreach ($rows AS $row) {
                         echo "<tr><td class='col-xs-1'><img class='img-responsive' src='images/parts/".$row['vchar_defaultimg']."' alt=''></td>";
                         echo "<td class='col-xs-2'><b>".$row['vchar_mannumber']."</b></td>";
                         echo "<td class='col-xs-2 align-middle'>".$row['vchar_name']."</td>";
                         echo "<td class='col-xs-2'>".$row['float_weight']."g</td>";
                         echo "<td class='col-xs-2'><a href='partman_editbase.php?index=".$row['int_index']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Bearbeiten</a></td>";
                         echo "<td class='col-xs-2'><a href='partman_operations.php?unit=0&action=0&index=".$row['int_index']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> L&ouml;schen</a></td></tr>";
                 }
?>
</tbody>
</table>
</div>

         <div class="row">
                 <div class="col-md-9">
                         <p class="text-muted"><a href='partman_editbase.php' class='btn btn-primary btn-sm enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-asterisk' aria-hidden='true'></span> Neuer Artikel</a></p>
                 </div>
         </div>

<?php
         } else {
?>
<div class="alert alert-info" role="alert">Es wurden keine Artikel gefunden!</div>
<?php
         }
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