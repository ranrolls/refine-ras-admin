<?php

Class BlogCommentClass{

		public function __construct(){
		
		}
		   
		   
			public function blogCommentDetails() 
			{
				global $dbObj,$common; 
				$action = $common->replaceEmpty('action','');

				$retvalArray = array();
				$finalArray=array();

			if ($action='blogcomment'){  
			 
		 $query	= "SELECT ab.id AS catid, ab.name AS catname, ab.alias AS catalias, ac.id AS cid, ac.title, ac.alias, 
					ac.introtext, ac.fulltext, ac.created, ac.params FROM ras_k2_categories as ab 
					inner join ras_k2_items as ac on ab.id = ac.catid where ac.id = '".$_REQUEST['id']."' "; 
						  
				$rs = $dbObj->runQuery($query); 
				
		      if(mysql_num_rows($rs) > 0)
			    {   
					$data=mysql_fetch_assoc($rs); 
					
					$tempArray = array();
					
					$queryComment	= $dbObj->runQuery("SELECT * FROM ras_k2_comments where itemID = '".$data['cid']."' ");
					$no_comment =  mysql_num_rows($queryComment);
					
					$tempArray['no_comment']  	= $no_comment;
					$tempArray['cid']     		= $data['cid'];
					$tempArray['catid']     	= $data['catid'];
					$tempArray['catname']     	= $data['catname'];
					$tempArray['catalias']     	= $data['catalias'];
					$tempArray['image'] =  "http://ras.refine-dev.com/media/k2/items/cache/".md5("Image".$data['cid'])."_L.jpg";
					$tempArray['title']     = $data['title'];
					$tempArray['alias']     = $data['alias']; 
					$tempArray['introtext'] = strip_tags(mb_convert_encoding($data['introtext'], 'HTML-ENTITIES', 'utf-8')); 
					//$tempArray['params']    = $data['params'];
					$tempArray['fulltext']  = strip_tags(mb_convert_encoding($data['fulltext'], 'HTML-ENTITIES', 'utf-8')); 
					$tempArray['created']   = $data['created'];
					$paramsArray = array();
					$paramsArray 			= json_decode($data['images'], true);
					$tempArray['images']    = 'http://ras.refine-dev.com/newras/'.$paramsArray['image_intro'];
					$paramsArray = array();
					$paramsArray 		= json_decode($data['urls'], true);
					$tempArray['urls']   	= $paramsArray['image_intro'];
					$tempArray['urla']    	= $paramsArray['urla'];
					$tempArray['urlatext']   = $paramsArray['urlatext'];
					$tempArray['urlb']    	 = $paramsArray['urlb'];
					$tempArray['urlbtext']   = $paramsArray['urlbtext'];
					$tempArray['urlc']    	 = $paramsArray['urlc'];
					$tempArray['urlctext']   = $paramsArray['urlctext'];
					$tempArray['urls']      = $data['urls'];
					$tempArray['attribs']   = $data['attribs'];  
					
					
					$finalArray[] = $tempArray;
					
					header('Content-Type: application/json; Charset=UTF-8');
					$dat = array('status'=>'1','result'=>$finalArray);
					echo json_encode($dat); 
					
					
					
				  
                }
					else
					{
					header('Content-Type: application/json; Charset=UTF-8');
					$retvalArray['status'] = '0';  
					echo json_encode($retvalArray);exit; 
					} 
			}  
			
	      }	
			
} //Closing of Class
	
?>
		
		
