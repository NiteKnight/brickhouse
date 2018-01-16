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
                         echo "<td class='col-xs-2'><a href='partmngmt.php?mode=1&index=".$row['int_index']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Bearbeiten</a></td>";
                         echo "<td class='col-xs-2'><a href='partmngmt.php?mode=0&action=0&index=".$row['int_index']."' class='btn btn-default btn-sm btn-block enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> L&ouml;schen</a></td></tr>";
                 }
?></tbody>
</table>
</div>

         <div class="row">
                 <div class="col-md-9">
                         <p class="text-muted"><a href='partmngmt.php?mode=1&action=1' class='btn btn-primary btn-sm enabled' role='button' aria-disabled='true'><span class='glyphicon glyphicon-asterisk' aria-hidden='true'></span> Neuer Artikel</a></p>
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
</body>
</html>