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
         } catch (PDOException $e) {
                 print "Error!: " . $e->getMessage() . "<br/>";
                 die();
         }
?>

<form action="partmngmt.php" method="post">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Basisdaten</h3>
  </div>
  <div class="panel-body">
         <div class="row">
                 <div class="col-md-2">
                      <img class="img-responsive" src="images/parts/<?php echo $row['vchar_defaultimg']; ?>">
                 </div>
                 <div class="col-md-10">
                         <div class="row">
                                 <div class="col-md-4">
                                         <div class="form-group">
                                                 <label for="ColorNumber">Herstellernr. <small>(LEGO Artikelnummer)</small></label>
                                                 <input type="text" name="mannumber" class="form-control" id="ManufacturerNumber" value="<?php echo $row['vchar_mannumber']; ?>">
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
                                                         <input type="text" name="partweight"  class="form-control" id="PartWeight" value="<?php echo $row['float_weight']; ?>">
                                                         <span class="input-group-addon" id="basic-addon2">g</span>
                                                 </div>
                                         </div>
                                 </div>
                                 <div class="col-md-2">
                                 </div>
                                 <div class="col-md-7">
                                         <div class="form-group">
                                                 <label for="DefaultImage">Standardbild</label>
                                                 <input type="text" name="defaultimg" class="form-control" id="DefaultImage" value="<?php echo strtoupper($row['vchar_defaultimg']); ?>">
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
                      <a href='partmngmt.php?mode=0' class='btn btn-default enabled' role='button' aria-disabled='true'>Zur&uuml;ck</a>
                 </div>
         </div>
</form>