<?php
         try {
                 $pdo = new PDO('mysql:host=localhost;dbname=brickhouse', 'root', 'root');
                 if (isset($_GET['index']) == false) {

                         $statement = $pdo->prepare("INSERT INTO bdat_colors (int_number, vchar_name, vchar_colcode) VALUES (?, ?, ?)");
                         $statement->execute(array('-1', 'Neue Farbe', '#000000'));

                         $index = $pdo->lastInsertId();
                 } else {
                          $index = $_GET['index'];
                         }

                 $sql = "SELECT int_number, vchar_name, vchar_colcode FROM bdat_colors WHERE int_index = ".$index;
                 $row = $pdo->query($sql)->fetch();
                 $pdo = null;
         } catch (PDOException $e) {
                 print "Error!: " . $e->getMessage() . "<br/>";
                 die();
         }
?>

<form action="colormngmt.php" method="post">
         <div class="row">
                 <div class="col-md-3">
                         <div class="form-group">
                                 <label for="ColorNumber">Farbnummer <small>(lt. Bricklink)</small></label>
                                 <input type="number" name="colnumber" class="form-control" id="ColorNumber" value="<?php echo $row['int_number']; ?>">
<!--     <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                         </div>
                 </div>
                 <div class="col-md-9">
                         <div class="form-group">
                                 <label for="ColorName">Name</label>
                                 <input type="text" name="colname"  class="form-control" id="ColorName" value="<?php echo $row['vchar_name']; ?>">
                         </div>
                 </div>
         </div>

         <div class="row">
                 <div class="col-md-2">
                         <div class="form-group">
                                 <label for="ColorValue">Farbwert</label>
                                 <input type="color" name="colvalue"  class="form-control" id="ColorValue" value="<?php echo $row['vchar_colcode']; ?>" onchange="updateTextbox()">
                         </div>
                 </div>
                 <div class="col-md-2">
                 </div>
                 <div class="col-md-2">
                         <div class="form-group">
                                 <label for="HEXCode">HEX-Code</label>
                                 <input type="text" name="hexcode" id="HEXCode" value="<?php echo strtoupper($row['vchar_colcode']); ?>" onchange="checkInput()">
                         </div>
                 </div>
         </div>
         <div class="row">
                 <div class="col-md-2">
                      <input type="hidden" name="colindex" value="<?php echo $index ?>">
                      <button type="submit" class="btn btn-primary">Speichern</button>
                 </div>
                 <div class="col-md-2">
                      <a href='colormngmt.php?mode=0' class='btn btn-default enabled' role='button' aria-disabled='true'>Zur&uuml;ck</a>
                 </div>
         </div>
</form>