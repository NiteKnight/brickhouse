<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Custom styles for this template -->
    <link href="css/sticky-footer.css" rel="stylesheet">

<!-- <link href="css/grid.css" rel="stylesheet">   -->



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
         $section_id = 1;
         $site_id = 0;
         include ("header_nav.php");
 ?>

<div class="container">
<div class="page-header">
  <h1>Startseite <small>&Uuml;bersicht aller Komponenten</small></h1>
</div>


      <div class="row">
        <div class="col-md-6">
          <h4>Stammdaten</h4>
          <div class="row">
            <div class="col-md-6">

<!-- <button type="button" class="btn btn-default btn-block" aria-label="Farben">Farben</button> -->

                 <a href="colormngmt.php?mode=0" class="btn btn-default btn-block enabled" role="button" aria-disabled="true">Farben</a>
            </div>
            <div class="col-md-6">
                 <a href="partman_overview.php" class="btn btn-default btn-block enabled" role="button" aria-disabled="true">Artikel</a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                 <button type="button" class="btn btn-default btn-block" aria-label="Lagerpl&auml;tze">Lagerpl&auml;tze</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <h4>Lagerverwaltung</h4>
        </div>
      </div>

</div>



<footer class="footer">
      <div class="container">
        <p class="text-muted">Client: <?php echo $_SERVER['REMOTE_ADDR'] ?></p>
      </div>
    </footer>

  </body>
</html>