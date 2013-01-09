<?
session_start();
setcookie('page', $_SERVER['REQUEST_URI']);

if (($admin=='') || !(isset($admin)))
	header ("location:login.php");
	
include_once 'includes/db.php';
include_once 'includes/functions.php';
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Enhancing Communication Across TWC</title>
<link href="includes/style.css" rel="stylesheet" type="text/css" media="screen" />
<script language="javascript" src="includes/ecat.js"></script>

</head>
<body>
<div id="wrapper">
	<div id="header"> 
		<div id="logo">
			<h1><a href="#">ECAT</a></h1>
			<p>Enhancing Communication Across TWC</p>
		</div>
		<div id="search">
			<form method="get" action="">
			<fieldset>Search<br />
				<input type="text" size="30" id="search-text" onkeyup="showResult(this.value)" value="enter keywords here" onclick="this.value=''"/>
			</fieldset>
			</form>
		</div>	
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="index.php">Home</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="ecat.xml">RSS Info</a></li>
			<? 
			if (($admin!=NULL) && (isset($admin)))
				echo("<li><a href='admin.php'>Administration</a></li>");
			else
				echo("<li><a href='login.php'>Login</a></li>");
			?>
			<li><a href="ECAT_Documentation.pdf">Ecat Documentation</a></li>
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<h2 class="header">Announcements</h2>
					<? 
					include_once('../LDAP.class.php');
					$ADhost='ALBCORPDC11.corp.twcable.com';
					$ds = ldap_connect($ADhost)															// connect to ldap server
						   or die("Could not connect to LDAP server.");
					ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
				
					if ($ds) {																			// If connection is good then attempt to bind to server
						$ldapbind = @ldap_bind ( $ds,  'TWCCORP\\'.$eid,$pw);						// Binding to ldap server
				
						if ($ldapbind) 									
							$LDAP = new LDAP('TWCCORP\\'.$eid,$pw,$ADhost);
					}	
					$id=$_GET['id'];
					if ($id!='')	
					$sql="SELECT * FROM [dbo].[Announcements] where id='$id' order by last_updated desc";
					else
					$sql="SELECT * FROM [dbo].[Announcements] where [archived]='0' order by last_updated desc";
					
					
					$category= $_GET['category'];
					if ($category!='')
						$sql="SELECT * FROM [dbo].[Announcements] where category='$category' order by last_updated desc";
		
					$department= $_GET['department'];
					if ($department!='')
						$sql="SELECT * FROM [dbo].[Announcements] where department='$department' order by last_updated desc";
		
		
					$result = @mssql_query($sql,$db);
					//echo("<br>".$result."<br>".mssql_num_rows($result));
			
					if (@mssql_num_rows($result)>0){	
										
						while($row = @mssql_fetch_array($result))
						{
							$name=$LDAP->getFirstName($row['user_eid'])." ".$LDAP->getLastName($row['user_eid']);
							$email=$LDAP->getEmail($row['user_eid']);
							if	($row['ready'] == '1')
								$ready= "Ready to Launch";
							else
								$ready= "Not Ready";
							if($row['user_eid']==$eid){
								echo "<a href='admin.php?edit=" . $row['id'] . "'><h2 class='title'>$ready: " . stripslashes_mssql($row['title']) . "</h2></a>";
								echo "<div class='meta'>Posted by <a href='mailto:".$email."'>".$name."</a> on ". $row['date_added']."</div>";
								echo "<div class='entry'><p>".stripslashes_mssql($row['announcement'])."</p></div>";
							}
							else if ($admin=='Admin'){
								echo "<a href='admin.php?edit=" . $row['id'] . "'><h2 class='title'>$ready: " . stripslashes_mssql($row['title']) . "</h2></a>";
								echo "<div class='meta'>Posted by <a href='mailto:".$email."'>".$name."</a> on ". $row['date_added']."</div>";
								echo "<div class='entry'><p>".stripslashes_mssql($row['announcement'])."</p></div>";
							}

							else{
								echo "<a href='index.php?id=" . $row['id'] . "'><h2 class='title'>$ready: " . stripslashes($row['title']) . "</h2></a>";
								echo "<div class='meta'>Posted by <a href='mailto:".$email."'>".$name."</a> on ". $row['date_added']."</div>";
								echo "<div class='entry'>".stripslashes($row['announcement'])."</div>";	
							}
						}
					}						
					?>
					
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
				<!-- end #content -->
				<div id="sidebar">
				<ul>			
						<li>
						<div id="livesearch"></div>
						</li>
						<li>
							<?
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
							?> 
							
						</li>
						<li>
							<?
								$departments="SELECT * FROM [dbo].[Departments] order by department_name asc";
								$dept_result = @mssql_query($departments,$db);
								if (@mssql_num_rows($dept_result)>0){
									echo("<h2>Departments</h2>
											<ul>");
									
									while($row = @mssql_fetch_array($dept_result))
									{
										echo "<li><a href='index.php?department=" . $row['id'] . "'>" . $row['department_name'] . "</a></li>";
									}
								echo("</ul>");
								}
							?> 
							
						</li>
						<li>
							<?
								$archives="SELECT * FROM [dbo].[Announcements] where archived='1' order by last_updated desc";
								$arch_result = @mssql_query($archives,$db);
								if (@mssql_num_rows($arch_result)>0){
									echo("<h2>Archives</h2>
											<ul>");
									
									while($row = @mssql_fetch_array($arch_result))
									{
										echo "<li><a href='archive.php?archive=" . $row['id'] . "'>" . stripslashes($row['title']) . "</a></li>";
									}
								echo("</ul>");
								}
							?> 
							
						</li>
					</ul>
				</div>
				<!-- end #sidebar -->
				<div style="clear: both;">&nbsp;</div>
			</div>
		</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
	<p>© <? echo date('Y'); ?> <a href="http://www.timewarnercable.com/">Time Warner Cable Inc. </a> All rights reserved.</p>
</div>
<!-- end #footer -->
</body>
</html>
