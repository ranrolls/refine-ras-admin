<?php

Class BlogListClass{
	
 
		public function __construct(){
		
		}
		   
		   
			public function blogCategoryDetails() 
			{
				 global $dbObj,$common; 
				 $action = $common->replaceEmpty('action','');
				
				 $retvalArray = array();
				 $finalArray=array();
				
			if ($action='blogList'){  
			 
		      $query	= "SELECT id, name, alias,description, params FROM ras_k2_categories 
";
                 
                         if(!isset($number) || ($number == "" || $number == 0)){
					  $page = 1;
					}	else{
						$page = $number;
					}
                    
                                        $sql = $dbObj->runQuery($query);	
					$total_records = mysql_num_rows($sql);
					$num_pages = ceil($total_records/10);
					$start =  10* ($page-1);
					 $end = 10; 
				        $query .= "limit $start,$end";  
					$rs = $dbObj->runQuery($query);
	
				  
		      if (mysql_num_rows($rs) > 0){  

			while($data=mysql_fetch_assoc($sql))
			{	 
			
			
                                $tempArray = array();
				                $tempArray['id']     	= $data['id'];
                               //  $tempArray['catid']     	= $data['catid'];
                                 $tempArray['name']     	= $data['name'];
                                  $tempArray['alias']     	= $data['alias'];
 $tempArray['description']     	= $data['description'];

			           
				  $finalArray[] = $tempArray; 

                               } 
                               
                                 header('Content-Type: application/json; Charset=UTF-8');
			         $dat = array('status'=>'1','result'=>$finalArray);
                                 echo json_encode($dat);exit;  
			  
                       }
                              else {
				   header('Content-Type: application/json; Charset=UTF-8');
				   $retvalArray['status'] = '0';  
				   echo json_encode($retvalArray);exit; 
				}
				
			}  
			
	        }	
			
} //Closing of Class
	
?>
		
		
