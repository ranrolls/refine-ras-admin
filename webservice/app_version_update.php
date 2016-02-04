<?php

include_once('config.php');

  

if (isset($_POST['appupdate'])) {
	  
$package_name			= $_POST['package_name']; 
$version                        = $_POST['version'];
$details                        = $_POST['details'];

###############Insert App Update Version############
 
$query2 = "INSERT into ras_app_version(package_name,version,details) 
	            values ('".$package_name."','".$version."','".$details."')";

if($result1 = mysql_query($query2))
{ 	 
$last_restorant_id=mysql_insert_id();

echo "RAS Updated APP Details ";

}


}

  

?> 


<html>

<body>

<strong>RAS App Version Update Entry Form</strong>:-
<br/><br/>
<form name="form1" id="mainForm" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']?>">
  <label>APP Package Name:</label><input type="text" name="package_name" placeholder="APP Package Name..." class="txtInput" required="" /> <br />
   <br />
 
 <label>Vesrion:</label> <input type="text" name="version" placeholder="Version..." class="txtInput" required="" /><br />
<label>App Details:</label> <input type="text" name="details" placeholder="Details..." class="txtInput" required="" /><br /><br />


  <input type="submit" name="appupdate" id="appupdate" value="Add RAS APP UPDATE"/>

  </form>
   
  <!--- End Here ----->
  </body>
</html>