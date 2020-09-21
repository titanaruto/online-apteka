<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");



try {
    $serverName='mssrv1c03:1433';
$dbname='ProductRemains';
$username='astor';
$password='12345678';


    $dbh = new PDO ("dblib:host=$serverName;dbname=$dbname",$username,$password);

} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    exit;
}

$sql = "SELECT * FROM ProductRemains.dbo.Remains";
//$sql = "SELECT * FROM ProductRemains.dbo.RemainsDate";
$count =0;
foreach ($dbh->query($sql) as $row) {
  if ($count >10){
      break;
  }
   print_r($row);
  $count++;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>