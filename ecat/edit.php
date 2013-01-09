<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?
ini_set('default_charset', 'UTF-8');
session_start();
include_once 'includes/functions.php';

@extract($_GET);
if ($submit=='Edit Announcement'){
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
		//echo $title;
		
		$sql='UPDATE [dbo].[Announcements] set [title]="'.$title.'", [announcement]="'.$announcement.'", [ready]="'.$_POST['ready'].'", [department]="'.$_POST['department'].'", [category]="'.$category.'", [archived]="'.$_POST['archived'].'", [last_updated]="'.date("Y-m-d H:i:s").'" where id = "'.$_POST['id'].'"';
		$query = mssql_query($sql);
		//echo $sql;
	}
include('build_rss.php');
header("location:admin.php");
?>
