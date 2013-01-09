<?
ini_set('default_charset', 'UTF-8');
session_start();
if (($admin=='') || !(isset($admin)))
	header ("location:login.php");
include_once 'includes/functions.php';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
			<li><a href="index.php">Home</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="ecat.xml">RSS Info</a></li>
			<? 
			if (($admin!=NULL) && (isset($admin)))
				echo("<li class='current_page_item'><a href='admin.php'>Administration</a></li>");
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
						<h2 class="title">Add & Edit Announcements</h2>
						<div class="entry">
						<? 
						$edit=$_GET['edit'];
						if($edit!=''){
							?>
						<table width="100%" cellpadding=5 cellspacing=0 border=0>
						<form name="edit" method="post" action="edit.php">
						<input type="hidden" name="id" value="<? echo $edit ?>" />
						<?
						include_once 'includes/db.php';
					
						$query = mssql_query("SELECT * FROM [dbo].[Announcements] where id='$edit'");
						
						while($row1 = @mssql_fetch_array($query))

							for ($i = 0; $i < mssql_num_fields($query); $i++) {
								$field = mssql_fetch_field($query, $i);
								$label=strtoupper(str_replace("_"," ",$field->name));
								if
								((strtoupper($field->name)=='REQUESTOR')
								||(strtoupper($field->name)=='STATUS')
								||(strtoupper($field->name)=='USER_EID')
								||(strtoupper($field->name)=='LAST_UPDATED')
								||(strtoupper($field->name)=='LAST_EDITED_BY')
								||(strtoupper($field->name)=='DATE_COMPLETED')
								||(strtoupper($field->name)=='DATE_ADDED')){ }
						
								else if(strtoupper($field->name)=='DEPARTMENT'){
										echo '<tr><td>'.$label.':</td>';
										echo '<td><select name="'.$field->name.'">';
										$departments="SELECT * FROM [dbo].[Departments] order by department_name asc";
										$dept_result = @mssql_query($departments,$db);
											
										if (@mssql_num_rows($dept_result)>0){			
											while($row2 = @mssql_fetch_array($dept_result))
											{
												if($row1[$field->name]==$row2['id'])
												echo "<option value='" . $row2['id'] . "' selected>" . $row2['department_name'] . "</option>";
												else
												echo "<option value='" . $row2['id'] . "'>" . $row2['department_name'] . "</option>";
											}
										
										}
									echo '</select></td></tr>';
									}	
						
								else if(strtoupper($field->name)=='CATEGORY'){
										echo '<tr><td>'.$label.':</td>';
										echo '<td><select name="'.$field->name.'">';
										$categories="SELECT * FROM [dbo].[Categories] order by category_name asc";
										$cat_result = @mssql_query($categories,$db);
										if (@mssql_num_rows($cat_result)>0){			
											while($row2 = @mssql_fetch_array($cat_result))
											{
												if($row1[$field->name]==$row2['id'])
												echo "<option value='" . $row2['id'] . "' selected>" . $row2['category_name'] . "</option>";
												else
												echo "<option value='" . $row2['id'] . "'>" . $row2['category_name'] . "</option>";

											}
										
										}
									echo '</select>&nbsp;&nbsp;<input type="text" name="'.$field->name.'_new" value="Add New Category"></td></tr>';
									}	
									
								else if(strtoupper($field->name)=='READY'){
										echo '<tr><td>'.$label.'</td>';
										if($row1[$field->name]=='1'){
											$yes="checked=checked";
											$no='';
										}
										else {
											$no="checked=checked";
											$yes='';
										}
										
									?>
										 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='1' <? echo $yes ?>>&nbsp;&nbsp;Yes&nbsp;&nbsp;<input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='0' <? echo $no ?>>&nbsp;&nbsp;No&nbsp;&nbsp;</td></tr>
						<?
									}						
								else if(strtoupper($field->name)=='ARCHIVED'){
										echo '<tr><td>'.$label.'</td>';
										if($row1[$field->name]=='1'){
											$yes="checked=checked";
											$no='';
										}
										else {
											$no="checked=checked";
											$yes='';
										}
									?>
										 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='1' <? echo $yes ?>>&nbsp;&nbsp;Yes&nbsp;&nbsp;<input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='0' <? echo $no ?>>&nbsp;&nbsp;No&nbsp;&nbsp;</td></tr>
						<?
									}						
						
								else if(strtoupper($field->type)=='CHAR'){
								echo '<tr>';
										echo '<td valign="top">'.$label.':</td>';
										echo '<td><input type="text" name='.$field->name.' value="'.stripslashes_mssql($row1[$field->name]).'"></td>';
										echo '</tr>';
										}
								else if(strtoupper($field->type)=='TEXT'){
									echo '<tr>';
										echo '<td  valign="top">'.$label.':</td>';
										echo '<td><textarea name='.$field->name.' cols="60" rows="5">'.stripslashes_mssql($row1[$field->name]).'</textarea></td>';
										echo '</tr>';
										}
							
								else if(strtoupper($field->type)=='DATETIME'){
										echo '<td valign="top">'.$label.'</td>';
									?>
										 <tr><td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='text' size='25' value='<? echo(date('Y-m-d'));?>'>&nbsp;&nbsp;<a href="javascript:NewCal('<? echo $field->name; ?>','yyyymmdd',true, 24,'arrow',true);"><img src="images/cal.gif" width='16' height='16' border='0' alt='Pick a date'></a></td></tr>
						<?
									}	
									
									
									else if(strtoupper($field->type)=='TYPE'){
										echo '<td>'.$label.'</td>';
									?>
										 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='text' size='25' value='<? echo(date('Y-m-d'));?>'>&nbsp;&nbsp;<a href="javascript:NewCal('<? echo $field->name; ?>','yyyymmdd',true, 24,'arrow',true);"><img src="images/cal.gif" width='16' height='16' border='0' alt='Pick a date'></a></td></tr>
						<?
									}			
											
							}
							?>
						
							<tr><td colspan="2" align="center"><hr />
									<input type="submit" name="submit" value="Edit Announcement" /><br />
						<br /></td></tr>
						</form>
						</table>
	
							<?	
													
						}
						else{
						?>					
						
						<table width="100%" cellpadding=5 cellspacing=0 border=0>
						<form name="add" method="post" action="add.php">
						<?
						include_once 'includes/db.php';
					
						$query = mssql_query('SELECT * FROM [dbo].[Announcements]');
						for ($i = 0; $i < mssql_num_fields($query); $i++) {
							$field = mssql_fetch_field($query, $i);
							// Print the row
							$label=strtoupper(str_replace("_"," ",$field->name));
							if
							((strtoupper($field->name)=='REQUESTOR')
							||(strtoupper($field->name)=='STATUS')
							||(strtoupper($field->name)=='USER_EID')
							||(strtoupper($field->name)=='LAST_UPDATED')
							||(strtoupper($field->name)=='LAST_EDITED_BY')
							||(strtoupper($field->name)=='DATE_COMPLETED')
							||(strtoupper($field->name)=='DATE_ADDED')){ }
					
							else if(strtoupper($field->name)=='ADDRESS'){
									echo '<tr><td>'.$label.':</td>';
									echo '<td><select name="'.$field->name.'">';
									echo('<option value="">Please Select One</option>');	
									$LDAPSEARCH->getLocation();
									echo '</select></td></tr>';
								}	
							else if(strtoupper($field->name)=='CATEGORY'){
									echo '<tr><td>'.$label.':</td>';
									echo '<td><select name="'.$field->name.'">';
									$categories="SELECT * FROM [dbo].[Categories] order by category_name asc";
									$cat_result = @mssql_query($categories,$db);
									echo('<option value="">Please Select One</option>');	
									if (@mssql_num_rows($cat_result)>0){			
										while($row2 = @mssql_fetch_array($cat_result))
										{
											echo "<option value='" . $row2['id'] . "'>" . $row2['category_name'] . "</option>'";
										}
									
									}
								echo '</select>&nbsp;&nbsp;<input type="text" name="'.$field->name.'_new" value="Add New Category"></td></tr>';
								}	
								
							else if(strtoupper($field->name)=='DEPARTMENT'){
									echo '<tr><td>'.$label.':</td>';
									echo '<td><select name="'.$field->name.'">';
									$departments="SELECT * FROM [dbo].[Departments] order by department_name asc";
									$dept_result = @mssql_query($departments,$db);
									echo('<option value="">Please Select One</option>');	
									if (@mssql_num_rows($dept_result)>0){			
										while($row2 = @mssql_fetch_array($dept_result))
										{
											echo "<option value='" . $row2['id'] . "'>" . $row2['department_name'] . "</option>'";
										}
									
									}
								echo '</select></td></tr>';
								}	
					
							else if(strtoupper($field->name)=='READY'){
									echo '<tr><td>'.$label.'</td>';
								?>
									 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='1'>&nbsp;&nbsp;Yes&nbsp;&nbsp;<input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='0' checked="checked">&nbsp;&nbsp;No&nbsp;&nbsp;</td></tr>
					<?
								}						
							else if(strtoupper($field->name)=='ARCHIVED'){
									echo '<tr><td>'.$label.'</td>';
								?>
									 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='1'>&nbsp;&nbsp;Yes&nbsp;&nbsp;<input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='radio' value='0' checked="checked">&nbsp;&nbsp;No&nbsp;&nbsp;</td></tr>
					<?
								}						
					
							else if(strtoupper($field->type)=='CHAR'){
							echo '<tr>';
									echo '<td>'.$label.':</td>';
									echo '<td><input type="text" name='.$field->name.'></td>';
									echo '</tr>';
									}
							else if(strtoupper($field->type)=='TEXT'){
								echo '<tr>';
									echo '<td>'.$label.':</td>';
									echo '<td><textarea name='.$field->name.'></textarea></td>';
									echo '</tr>';
									}
						
							else if(strtoupper($field->type)=='DATETIME'){
									echo '<td>'.$label.'</td>';
								?>
									 <tr><td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='text' size='25' value='<? echo(date('Y-m-d'));?>'>&nbsp;&nbsp;<a href="javascript:NewCal('<? echo $field->name; ?>','yyyymmdd',true, 24,'arrow',true);"><img src="images/cal.gif" width='16' height='16' border='0' alt='Pick a date'></a></td></tr>
					<?
								}	
								
								
								else if(strtoupper($field->type)=='TYPE'){
									echo '<td>'.$label.'</td>';
								?>
									 <td><input id='<? echo $field->name; ?>' name='<? echo $field->name; ?>' type='text' size='25' value='<? echo(date('Y-m-d'));?>'>&nbsp;&nbsp;<a href="javascript:NewCal('<? echo $field->name; ?>','yyyymmdd',true, 24,'arrow',true);"><img src="images/cal.gif" width='16' height='16' border='0' alt='Pick a date'></a></td></tr>
					<?
								}			
										
						}
						?>
					
						<tr><td colspan="2" align="center"><hr />
								<input type="submit" name="submit" value="Add Announcement" /><br />
					<br /></td></tr>
					</form>
					</table>

<?	
					} //end else
mssql_close();

?>

<br><br>

						</div>
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
				<!-- end #content -->
				<div id="sidebar">
					<div id="livesearch"></div>
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
									$count=0;
									while(($row = @mssql_fetch_array($arch_result))&&($count<'10'))
									{
										echo "<li><a href='archive.php?archive=" . $row['id'] . "'>" . stripslashes($row['title']) . "</a></li>";
										$count++;
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
