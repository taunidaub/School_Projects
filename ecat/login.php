<?
session_start();
if (($admin!=NULL) && (isset($admin)))
	header ("location:index.php");

if ($_SESSION['error']){
	$error_mesg=$_SESSION['error'];
	unset($_SESSION['error']);
}

include_once('../LDAP.class.php');
$login_eid=$_POST['login_eid'];
$pass=$_POST['password'];
if($login_eid!='' && $pass!=''){
	$ADhost='ALBCORPDC11.corp.twcable.com';
	$ds = ldap_connect($ADhost)															// connect to ldap server
		   or die("Could not connect to LDAP server.");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

	if ($ds) {																			// If connection is good then attempt to bind to server
		$ldapbind = @ldap_bind ( $ds,  'TWCCORP\\'.$login_eid,$pass);						// Binding to ldap server

		if ($ldapbind) {										
			$LDAP = new LDAP('TWCCORP\\'.$login_eid,$pass,$ADhost);
			$_SESSION['eid']=$login_eid;
			$_SESSION['pw']=$pass;
			$_SESSION['email']=$LDAP->getEmail($login_eid);
			$_SESSION['firstname']=$LDAP->getFirstName($login_eid);
			$_SESSION['lastname']=$LDAP->getLastName($login_eid);

			include_once ("includes/db.php");	
			$admin=mssql_fetch_array(mssql_query('SELECT * FROM [dbo].[Admin] where [eid] = "'.$login_eid.'"'));
			//echo 'SELECT * FROM [dbo].['.$authTable.'] where [eid] = "'.$_POST['eid'].'"';
			$_SESSION['admin'] = $admin['level'];
				
			if(($_COOKIE['page']!='/index.php')&&($_COOKIE['page']!='/'))
				header("location:". $_COOKIE['page']);
				
			else
				header("location:index.php");
		}
		
		else{
			setcookie('error','Incorrect user name and password combination, please try again.',time()+10);
			header("location:login.php");
		}
	}
}

$_SESSION['debug']=0;
if ($_SESSION['debug']) {
	echo "<pre><strong>\$_REQUEST:</strong><br />"; var_dump($_REQUEST); echo "</pre>";
	echo "<pre><strong>\$_SESSION:</strong><br />"; var_dump($_SESSION); echo "</pre>";
	echo "<pre><strong>\$_GET:</strong><br />"; var_dump($_GET); echo "</pre>";
	echo "<pre><strong>\$_POST:</strong><br />"; var_dump($_POST); echo "</pre>";
	echo "<pre><strong>\$_FILES</strong><br />"; var_dump($_FILES); echo "</pre>";
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Enhancing Communication Across TWC</title>
<link href="includes/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="logo">
			<h1><a href="#">ECAT</a></h1>
			<p>Enhancing Communication Across TWC</p>
		</div>
		</div>
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="index.php">Home</a></li>
			<li><a href="contact.php">Contact</a></li>
			<? 
			if (($login!=NULL) && (isset($login)))
				echo("<li><a href='admin.php'>Administration</a></li>");
			else
				echo("<li><a href='login.php'>Login</a></li>");
			?>
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="content">
					<div class="post">
						<h2 class="header">Login</h2>
						<div class="entry">
						<?	 if($_session['error']){  ?>
							<p class="error"><? echo $_COOKIE['error'] ?> </p>
							<? } ?><br />
							<form action="login.php" method="post">
							<table align="center">
								<tr>
								<td><input name="login_eid" type="text" class="textbox" value="E123456" size="12" onclick="this.value=''" /><br />EID</td><td> <input name="password" type="password" class="textbox"  size="12" /><br />Password</td><td valign="top"><input type="image" src="images/login.png" class="btn" title="Submit" /></td>
								</tr>
							</table>
							</form>
							<br /><br />
							<p class="highlight">If you require assistance with your EID login please use the <a href="https://passwordhelp.twcable.com" target="_blank">Forgot password?</a> link.</p>

						</div>
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
				<!-- end #content -->
				<div id="sidebar">
					<ul>
						<li>
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
								$archives="SELECT * FROM [dbo].[Announcements] where archived='1' order by last_updated asc";
								$arch_result = @mssql_query($archives,$db);
								if (@mssql_num_rows($arch_result)>0){
									echo("<h2>Archives</h2>
											<ul>");
									
									while($row = @mssql_fetch_array($dept_result))
									{
										echo "<li><a href='index.php?archive=" . $row['id'] . "'>" . $row['archive_name'] . "</a></li>";
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
