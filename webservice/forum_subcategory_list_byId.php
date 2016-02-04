 <?php
include("include/config.php");

$forum = new forum_subcategory_list_byId();

$forum->action = $common->replaceEmpty('action','');



switch($forum->action){

case 'forumsubcategorylistByid':$forum->forum_subcategory_list_byId_Details();  
	  
	break;
   
}

 
   
?>