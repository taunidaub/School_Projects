<?
include_once 'includes/db.php';
$categories="SELECT * FROM [dbo].[Categories] order by category_name asc";
$cat_result = @mssql_query($categories,$db);
if (@mssql_num_rows($cat_result)>0){
	echo("<h2>Categories</h2>
			<ul>");
	
	while($row = @mssql_fetch_array($cat_result))
	{
		echo "<li><a href='index.php?category=" . $row['id'] . "'>" . $row['category_name'] . "</a></li>";
	}
	
	echo("</ul>");
}

include_once 'includes/db.php';
$rss="SELECT * FROM [dbo].[Announcements] where archived='0' order by last_updated desc";
$rss_result = @mssql_query($rss,$db);
if (@mssql_num_rows($rss_result)>0){
	$fp = fopen('ecat.rss', 'w');
	fwrite($fp, "<rss version="2.0">
	<channel>
	<title>ECAT Announcements</title>     
		<link>http://ecat.alb.twcable.com</link>
		");
		while($row = @mssql_fetch_array($cat_result)){
			fwrite($fp, "<description>".$row['title']."</description>");
   			fwrite($fp, "<item>".$row['Announcement']."</item>");
		}
	}
fwrite($fp, "</channel>
</rss>");

fclose($fp);

}		

?> 
							