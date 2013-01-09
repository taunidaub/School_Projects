<?
include_once 'includes/db.php';

$rss="SELECT * FROM [dbo].[Announcements] order by last_updated desc";
$rss_result = @mssql_query($rss,$db);
if (@mssql_num_rows($rss_result)>0){
	$fp = fopen('ecat.xml', 'w');
	fwrite($fp, '<?xml version="1.0" encoding="iso-8859-1"?>
	<rss version="2.0">
	<channel>
	<title>ECAT Announcements</title>
	<description>Here is the recent listing of Announcements</description>     
	<link>http://ecat.alb.twcable.com</link>
	');
	while($row = @mssql_fetch_array($rss_result)){
		if	($row['ready'] == '1')
			$ready= "Ready to Launch";
		else
			$ready= "Not Ready";
			
		fwrite($fp, "<item>");
		fwrite($fp, "<title>$ready - ".$row['title']."</title>");
		fwrite($fp, "<description>".$row['announcement']."</description>");
		fwrite($fp, "<link>http://ecat.alb.twcable.com/index.php?id=".$row['id']."</link>");
		fwrite($fp, "<pubDate>".$row['last_updated']."</pubDate>");
		fwrite($fp, "<category>".$row['category']."</category>");
		fwrite($fp, "<department>".$row['department']."</department>");
		fwrite($fp, "</item>");
	}
fwrite($fp, "</channel>
</rss>");

fclose($fp);
}

?> 
							