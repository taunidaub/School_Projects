<?php
session_start();
$q=$_GET["q"];

include_once 'includes/db.php';

$sql="SELECT * FROM [dbo].[Announcements] WHERE ([announcement] like '%".$q."%' or [title] like '%".$q."%') ";

$sresult = @mssql_query($sql,$db) or die("Error in query: $sql. ");
//echo($sql);
echo "<H2>Results</H2>
<ul>";
//echo(mssql_num_rows($sresult));
while($srow = @mssql_fetch_array($sresult))
  {
  if($srow['user_eid']== $eid){
	  echo "<li><a href='admin.php?edit=" . $srow['id'] . "'>" . $srow['title'] . "</a></li>";

	}
  else if ($admin=='Admin'){
  	  echo "<li><a href='admin.php?edit=" . $srow['id'] . "'>" . $srow['title'] . "</a></li>";

  }
  else{
	  echo "<li><a href='index.php?id=" . $srow['id'] . "'>" . $srow['title'] . "</a></li>";
	  }
  }
echo "</ul>";
?> 