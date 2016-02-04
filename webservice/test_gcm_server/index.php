<?php


$con = mysql_connect('localhost','rasmento_rasras','zzU5k]T!]R@w') or die("unable to connect database"); 
mysql_set_charset('utf8', $con);
$db  = mysql_select_db("rasmento_ras",$con) or die("unable to select db");


if (isset($_POST["deletebuton"]))  {
   
$result="TRUNCATE TABLE ras_other_mobile_device_tokens";

$rs_username  = mysql_query($result);
if($rs_username)
{
 echo 'Data Deleted.';
}
else
{
 echo 'Data Not Deleted.';
}
 

}



/*if (isset($_POST["send_btn"]))  {

  $message=$_POST['message'];
   //echo "UPDATE ".$prefix."ras_other_mobile_device_tokens set message= '$message'  where 1";
 $result= mysql_query("UPDATE ras_other_mobile_device_tokens set message= '$message'  where 1 ") ;

 $rs_username  = mysql_query($result);
 if($rs_username)
 {
   echo 'Message Not Updated.';
 }
   else
 {
    echo 'Message Updated.';
   }
 

}*/






?>

<form id="deleteform" name="deleteform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
   
 <!--<input type="submit" class="send_btn" id="deletebuton" name="deletebuton" value="Delete Records"/>-->
 </form>

 
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Message by Push Notification</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
     <script type="text/javascript">
            $(document).ready(function(){
               
            });
            function sendPushNotification(id){
                var data = $('form#'+id).serialize();
                $('form#'+id).unbind('submit');                
                $.ajax({
                    url: "send_message.php?mode=sendmessage",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
                        
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("send sucuessfully.");
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        
                    }
                });
                return false;
            }
        </script> 

        <style type="text/css">
		body{ margin:0px; padding:0px;}
            .container{width: 950px;margin: 0 auto;padding: 0;     margin-top:60px;        }
            h1{font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 24px;color: #777;}
            div.clear{
                clear: both;
            }
            ul.devices{
                margin: 0;
                padding: 0;
            }
            ul.devices li{
                float: left;
                list-style: none;
                border: 1px solid #dedede;
                padding: 10px;
                margin: 0 15px 25px 0;
                border-radius: 3px;
                -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #555; width:100%;
            }
            ul.devices li label, ul.devices li span{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: bold;
                color: #393939;
                display: block; width:100%;
                float: left;
            }
            ul.devices li label{
                height: 25px;width:100%;
                        
            }
            ul.devices li textarea{
                float: left;width:100%;
                resize: none;
            }
            ul.devices li .send_btn{
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -moz-linear-gradient(center top, #0096FF, #005DFF);
                background: linear-gradient(#0096FF, #005DFF);
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
                border-radius: 3px; width:130px; height:40px;
                color: #fff;
            }
			header{ background:#1a3867; padding-bottom:5px; position:absolute; top:0px; width:100%;}
			footer{ background:#ededed; border-top:1px solid #dddddd; padding:10px;}
        </style>
    </head>
    <body>
    <header><a href="http://www.rasmentorshipforum.com/administrator/index.php"><img src="home_icon.jpg" alt="" style="float:left; padding:5px; margin-top:5px;"></a><a href="http://www.rasmentorshipforum.com/administrator/index.php"><img src="http://www.rasmentorshipforum.com/administrator/templates/isis/images/logo.png" alt="" class="pull-right" style="float:right; padding:5px;" ></a>
    <div class="clear"></div></header>
        <?php
        include_once 'db_functions.php';
        $db = new DB_Functions();
        $users = $db->getAllUsers();
        if ($users != false)
            $no_of_users = mysql_num_rows($users);
        else
            $no_of_users = 0;
        ?>
        <div class="container">
            <h1> No of Devices Registered: <?php echo $no_of_users; ?></h1>
            <hr/>
             <ul class="devices">
                <?php
                if ($no_of_users > 0) {
                    ?>
                    <?php
                    while ($row = mysql_fetch_array($users)) { 
                        ?>
                        <li>
                            <form id="<?php echo $row["id"] ?>" name="" method="post" onsubmit="return sendPushNotification('<?php echo $row["id"] ?>')">
                                <label>Device Plateform: </label> <span><?php echo $row["deviceplatform"] ?></span>
                                <div class="clear"></div>
                                <label>Device ID:</label> <span><?php echo $row["deviceid"] ?></span>
                                <div class="clear"></div>
                                <div class="send_container">                                
                                    <textarea rows="3" name="message" cols="25" class="txt_message" placeholder="Ras Mentorship"></textarea>
                                    <input type="hidden" name="regId" value="<?php echo $row["token"] ?>"/>
                                    <input type="submit" class="send_btn" value="Send" onclick=""/>

                                </div>
                                
                            </form> 
                        </li>
                    <?php }
                } else { ?> 
                    <li>
                        No Users Registered Yet!
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="clear"></div>
        <footer>.</footer>
    </body>
</html>