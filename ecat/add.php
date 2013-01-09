<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?
ini_set('default_charset', 'UTF-8');
session_start();
include_once 'includes/functions.php';

@extract($_GET);
if ($submit=='Add Announcement'){
	include_once ("includes/db.php");	
	//include_once('file:///Z|/LDAP.class.php');
	include_once('../LDAPSEARCH.class.php');
	$date=date('Y-m-d');
	if(($_POST['category_new']!="Add New Category")&&($_POST['category']=='')){
		$category=$_POST['category_new'];
		$sqla='INSERT INTO [dbo].[Categories] ([category_name]) VALUES ("'.$category.'")';
		$querya= mssql_query($sqla);
	}
	else
		$category=$_POST['category'];
		$announcement= htmlentities($_POST['announcement'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
		//echo $announcement;
		$title= htmlentities($_POST['title'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
		$sql='INSERT INTO [dbo].[Announcements] ([title],[announcement],[ready],[date_added],[user_eid],[department],[category],[archived],[last_updated]) VALUES ("'.$title.'","'.$announcement.'","'.$_POST['ready'].'","'.date("Y-m-d H:i:s").'","'.$_SESSION['eid'].'","'.$_POST['department'].'","'.$category.'","'.$_POST['archived'].'","'.date("Y-m-d H:i:s").'")';
		$query = mssql_query($sql);
		echo $sql;
	}
include('build_rss.php');
header("location:admin.php");
?>
