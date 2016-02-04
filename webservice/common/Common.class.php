<?php 
/*
Class Name : Common
Uses of Class : It contains the common methods.
*/
class Common {
	private static $instance;
	public static $start;
	public static $last;
	public $subject, $fullName, $filename, $tableName, $labelArray, $fieldArray, $emailID, $message, $valid, $totalRecord, $totalRecordChecked, $currentpageurl, $recordChecked;
	public $ematcherror		=array();
	/*
		It is used prevent multiple instance of  class.
	
	*/
	public static function singleton() {
		
		if (!isset(self::$instance)) {
        		$c = __CLASS__;
        		self::$instance = new $c;
		}
		
		return self::$instance;
    
    }
	public function __construct(){
		self::$start = 0;
		self::$last = 0;
	}
	 
	/*
		It is used to get data of post and get or Request.
	
	*/
	
	public function replaceEmpty($variable,$value,$editor = false,$type=''){
		if (isset($_GET[$variable])){
			$rtn = $_GET[$variable];
		}
		elseif (isset($_POST[$variable])){
			$rtn = $_POST[$variable];
		}
		
		else{
			$rtn = $value;
		}
		
		if (!get_magic_quotes_gpc()) {
			if (! is_array($rtn)){
				if(!$editor){
					$rtn = htmlentities($rtn);
				}
				return mysql_real_escape_string(addslashes($rtn));
			}
			else {
				$newRtn = array();
				foreach($rtn as $key=>$value){
					if(!$editor){
						$value = htmlentities($value);
					}
					$newRtn[$key] = mysql_real_escape_string(addslashes($value));
				}
				return $newRtn;
			}	
		}
		else {
			if (! is_array($rtn)){
				if(!$editor){
					$rtn = htmlentities($rtn);
				}
				return mysql_real_escape_string(addslashes($rtn));
			}
			else {
				$newRtn = array();
				foreach($rtn as $key=>$value){
					if(!$editor){
						$value = htmlentities($value);
					}
					$newRtn[$key] = mysql_real_escape_string(addslashes($value));
				}
				return $newRtn;
			}	
			//return mysql_real_escape_string(stripslashes($rtn));
		}
	}
	
	#################################################################################
	
	
	/*
		It is used to validate the string is not blank.
	
	*/
	public function isValidStr($Value, $MaxLength = 255){
		if (strlen(trim($Value)) > 0 && strlen(trim($Value)) <= 255){
			return true;
		}
		else {
			return false;
		}
	}	
	/*
		It is used to validate that the email ID is correct or not.
	
	*/
	public function isValidEmail($email){
		$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
		if (@eregi($pattern, $email)){
			 return true;
		}
		else {
			return false;
		}   
	}
	
	
	/*
		It is used to show the message stored in session.
	
	*/
	public function showMsg($Msg = "", $MsgType = "info"){
		if ($Msg == ""){
			if ($this->getSession('sewamsg') != ""){            
				$Msg = $this->getSession('sewamsg');
				if ($this->getSession('sewamsgtype') != ""){
					$MsgType = $this->getSession('sewamsgtype');
				}
				$this->setMsg("","");
			}
			else {
				$this->setMsg("","");
			}
		}

		if ($Msg != ""){
			?>
			<div style='padding:3px 15px 5px 5px;'><div id = "info" class="<?php echo $MsgType;?>"><?php echo $Msg ?></div></div>
			<?php
		}
	}
	/*
		It is used to set the message in session.
	
	*/
	public function setMsg($Msg = "", $MsgType = "info"){
		$this->setSession( 'sewamsg', $Msg);
		$this->setSession( 'sewamsgtype', $MsgType);
	}
	/*
		It is used to generate the random password.
	
	*/
	public function generatePassword($length=9, $strength=0) {
       $vowels = 'aeuy';
       $consonants = 'bdghjmnpqrstvz';
       if ($strength & 1) {
               $consonants .= 'BDGHJLMNPQRSTVWXZ';
       }
       if ($strength & 2) {
               $vowels .= "AEUY";
       }
       if ($strength & 4) {
               $consonants .= '23456789';
       }
       if ($strength & 8) {
               $consonants .= '@#$%';
       }


       $password = '';
       $alt = time() % 2;
       for ($i = 0; $i < $length; $i++) {
               if ($alt == 1) {
                       $password .= $consonants[(rand() % strlen($consonants))];
                       $alt = 0;
               } else {
                       $password .= $vowels[(rand() % strlen($vowels))];
                       $alt = 1;
               }
       }
       return $password;
	}
	/*
		It is used to redirect the self window.
	
	*/
	public function redirectSelfUrl($url){
		if (!headers_sent()) {
			header("Location: $url");
			exit;
		}else{
			echo "<script type='text/javascript'>top.document.location.href='$url';</script>";
		}
	}
	
	/*
		It is used to send mail.
	
	*/
	public function sendMail($HTML,$from,$to,$subject,$fromName = ''){
		global $conf;
		$headers = "From: $from\r\n"; 
		$headers .= "MIME-Version: 1.0\r\n"; 
		$boundary = uniqid("HTMLEMAIL"); 
		$headers .= "Content-Type: multipart/alternative;"."boundary = $boundary\r\n\r\n"; 
		$headers .= "This is a MIME encoded message.\r\n\r\n"; 
		$headers .= "--$boundary\r\n"."Content-Type: text/plain; charset=ISO-8859-1\r\n"."Content-Transfer-Encoding: base64\r\n\r\n"; 
		$headers .= chunk_split(base64_encode(strip_tags($HTML))); 
		$headers .= "--$boundary\r\n"."Content-Type: text/html; charset=ISO-8859-1\r\n"."Content-Transfer-Encoding: base64\r\n\r\n"; 
		$headers .= chunk_split(base64_encode($HTML)); 
		$headers  = "From: $from\r\n"; 
		$headers .= "Content-type: text/html\r\n";
		$headers  = "MIME-Version: 1.0 ". "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		if($fromName == ''){
			$headers .= "From: $conf[FROMEMAILNAME] <$from>" . "\r\n";
			@mail($to,$subject,$HTML,$headers);
		}else{
			$headers .= "From: $fromName <$from>" . "\r\n";
			if(@mail($to,$subject,$HTML,$headers)){
				return true;
			}else{
				return false;
			}
		}
		
		
	}
	
	public function setSession( $name, $value )
	{
		global $conf;
		$_SESSION[$conf["SESSION_PREFIX"].$name]=$value;
		
		if( isset($_SESSION[$conf["SESSION_PREFIX"].$name]))
			return true;
		else
			return false;
	}
	/*
		It is used to get the session with given name.	
	*/
	public function getSession( $name )
	{
		global $conf;
		if( isset($_SESSION[$conf["SESSION_PREFIX"].$name]) )
			return $_SESSION[$conf["SESSION_PREFIX"].$name];
		else
			return false;
	}
	
	
	/* This function is used to get the url for admin data sorting. */
	
	public function printAdminSortingUrl($sortOn = '',$sortByData = ''){
		global $conf;
		$this->currentpageurl = selfURL();
		if($sortOn == ''){
			$sortOn = $this->replaceEmpty('sortOn',$sortOn);
		}
		$sortBy = $this->replaceEmpty('sortBy',null);
		if(isset($sortBy) && strlen($sortBy)>0){
			if($sortBy == 'ASC'){
				$sortBy = 'DESC';
			}else{
				$sortBy = 'ASC';
			}
		}else{
			$sortBy = $sortByData;
		}
		// Getting url start
			$pageArray = @explode('?',$this->currentpageurl);
			$sortingUrl = 'sortOn='.$sortOn.'&sortBy='.$sortBy;
		if(count($pageArray)>0){
			$pageTempValueArray = @explode('&',$pageArray[1]);
			foreach($pageTempValueArray as $tempVal){
				$sortingUrl = $sortingUrl.'&';
				if(substr($tempVal,0,5) == "page=" || substr($tempVal,0,7) == "sortOn=" || substr($tempVal,0,7) == "sortBy="){
					$page = str_replace('page=','',$tempVal);
				}else{
					$sortingUrl = $sortingUrl.$tempVal;
				}
				$sortingUrl = str_replace('&&','&',$sortingUrl);
			}
		}
		echo $pageArray[0].'?'.trim($sortingUrl,'&');
		// Getting page number from url end
	}
	
	
	public function stopDuplicateUrl($string = '',$i=2,$pageID,$pageModule){
	global $dbObj;
		if($string !=''){
			if($dbObj->isValueExist($dbObj->addPrefix('pagealias'),'pageAlias','pageAliasID',$string,-1," pageID !='$pageID' and pageModule!='$pageModule'")){
				$string = $string.$i;
				$i = $i++;
				$string = $this->stopDuplicateUrl($string,$i,$pageID,$pageModule);
			}else{
				return $string;
			}		
		}else{
			return false;
		}
	}
		
	
	public function generateSlug($string = '',$pageID = '',$pageModule = ''){
		global $dbObj,$conf;
		if($string !=''){
			$string = strtolower($string);
			$string = str_replace(array('/', '-', '+', '.', '&amp;', '&'), ' ', $string);
			$string = str_replace(array('ë','é', 'è'), 'e', $string);
			$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
			$string = preg_replace("/[\s-]+/", " ", $string);
			$string = preg_replace("/[\s_]/", "-", $string);
			//$string = trim(substr($string, 0, 45);
			$i = 1;
			if($dbObj->isValueExist($dbObj->addPrefix('pagealias'),'pageAlias','pageAliasID',$string,-1," pageID !='$pageID' and pageModule!='$pageModule'")){
				$string = $string.$i;
				$i = $i++;
				$string = $this->stopDuplicateUrl($string,$i,$pageID,$pageModule);
			
			}
			return $string;
		 
		}	

	}
	
	public function parseNull($data)
	{
			// Be sure your data is escaped before you use this function
		if (chop($data) != "")
			if (strtolower(chop($data)) == "null")
				return "NULL";
			else
				return "'" . $data . "'";
		else
			return "NULL";
	}
	
	// to use this function to empty a directory, write:
 // recursive_remove_directory('path/to/full_directory',TRUE);

 public function recursive_remove_directory($directory, $empty=FALSE)
 {
    // if the path has a slash at the end we remove it here
    if(substr($directory,-1) == '/')    {
		$directory = substr($directory,0,-1);
     }
  
     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
		{
         // ... we return false and exit the function
         return FALSE;
  
     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;
  
     // ... else if the path is readable
     }else{
  
         // we open the directory
         $handle = opendir($directory);
  
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
  
                 // if the new path is a directory
                if(is_dir($path)) 
                 {
                     // we call this function with the new path
                     $this->recursive_remove_directory($path);
  
                 // if the new path is a file
                 }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
  
         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
 }
 // ------------------------------------------------------------
	public function getCorrectStrToTime($dateFormate = ''){
		if($dateFormate !=""){
			$dateArray = explode(' ',$dateFormate);
			$date = $dateArray[0].' '.$dateArray[1].' '.$dateArray[2].' '.$dateArray[3].' '.$dateArray[4];
			return strtotime($date);
			
		}
	
	}

/*
		It is used to delete session with given name.	
	*/
	
	function deleteSession( $name )
	{
		global $conf;
		if( isset($_SESSION[$conf["SESSION_PREFIX"].$name]) ){
		unset($_SESSION[$conf["SESSION_PREFIX"].$name]);
		
			return 1;
			}
		else
			return false;
	}
	
	public function createFolder($dataID='',$putInTo=''){
		global $conf;
		$userFolder = $conf['BASE_DATA_DIR'].$putInTo.$dataID;
		
		if(!file_exists($userFolder)){
			mkdir($userFolder);
		}
		return true;;
	}
	
	
	public function uploadDocument($path,$filename,$dataID,$putInTo,$new_width ='',$new_height=''){
		global $conf;
		if(!empty($dataID))
			$dest = $conf['BASE_DATA_DIR'].$putInTo.$dataID."/".$filename;
		 else
			$dest = $conf['BASE_DATA_DIR'].$putInTo.$filename;
		
		//echo '|'.$path.'|'.$dest;die;
		if(move_uploaded_file($path,$dest)){
		
		// load image and get image size
		 /* $img = imagecreatefromjpeg($dest);
		  $width = imagesx($img);
		  $height = imagesy($img);
		  $tmp_img = imagecreatetruecolor($new_width,$new_height);
		  imagecopyresized($tmp_img,$img,0,0,0,0,$new_width,$new_height,$width,$height );
		  imagejpeg( $tmp_img,$thumb_image,100);
		  imagedestroy($tmp_img);*/
		 
		return true;
		} else {
		return false;
		}
	}
	
		
	public function getExtensionOfFile($filename){
		$filename = strtolower($filename); 
		$exts = split("[/\\.]", $filename); 
		$n = count($exts)-1; 
		$exts = $exts[$n]; 
		return $exts; 
	}
 
 
	
	/**
	 * Makes file name safe to use
	 *
	 * @param   string  $file  The name of the file [not full path]
	 *
	 * @return  string  The sanitised string
	 *
	 * @since   11.1
	 */
	public static function makeSafe($file)
	{
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');

		return preg_replace($regex, '', $file);
	}
	/*This function return pagination url at admin after proccess has been done*/
	
	public function getAdminPaginationUrl($type = ''){
		
		$page = '';
		$this->totalRecord = $this->replaceEmpty('totalrecordonpage',0);
		$callBackUrl = base64_decode($this->replaceEmpty('callbackurl','')); 	# Retunrn url
		$page = $this->replaceEmpty('page',1);
		if($type == 'all'){//echo 'here';print_r($_REQUEST);
			$this->recordChecked = $this->replaceEmpty('checkStatus',array());
			$this->totalRecordChecked = count($this->recordChecked);
		}else{
			$this->totalRecordChecked = 1;
		}
		$recordRemains = $this->totalRecord - $this->totalRecordChecked;
		
		$this->currentpageurl = ($callBackUrl!='')?$callBackUrl:selfURL();
		
		// Getting page number from url start
		if(preg_match('/page=/',$this->currentpageurl)){
			$pageArray = explode('?',$this->currentpageurl);
			$pageTempValueArray = explode('&',$pageArray[1]);
			foreach($pageTempValueArray as $tempVal){
				if(substr($tempVal,0,5) == "page="){
					$page = str_replace('page=','',$tempVal);
				}
			}
			
			$page = ($page=='')?'1':$page;								# If page Value Not Found
		}else{
			$page=1;
		}
		// Getting page number from url end
		
		if($page > 1 && $recordRemains == 0){
			 $oldPage = $page;
			 $page = $page-1;
			 $currpage=selfURL();
			 if($callBackUrl != ''){
			 	$this->currentpageurl = str_replace('page='.$oldPage,'page='.$page,$callBackUrl);
			 }else{
			 	$this->currentpageurl = str_replace('page='.$oldPage,'page='.$page,$currpage);
			 }
		}elseif($page == 1 && $recordRemains == 0){
			$this->currentpageurl = $_SERVER['PHP_SELF'];
		}
		
		return $this->currentpageurl;
	}

	public function setLanguage(){
	global $conf;
	 $language = $this->replaceEmpty('wf_languagetype','');
		 if(!empty($language)){
			$this->setSession('CURRENTLANGUAGEID',$language);
		 }
	}
	
	public function getInternationalization($content,$showType = true){
			$langVariable = $this->getSession('CURRENTLANGUAGECODE').'_labelArray';
			global $$langVariable;
			$langVariable = $$langVariable;
			
			if(isset($langVariable[$content]) && !empty($langVariable[$content]) && strlen($langVariable[$content])>0){
					$content = $langVariable[$content];					
			}
			if($showType){
				echo $content;
			}else{
				return $content;
			}
			
	}
	
	/*
		It is used to remove slashes in a  string
	*/
	public function removeSlashes($variable)
	{
		if($variable)
		{
			$res = stripslashes($variable);
			return $res;
		}
		else
		{
			return false;
		}
	}
	
	/*
		It is used to perform action on single record
	*/
	public function adminActionSingleRecord($action='',$actionField='',$status='',$actionOn='',$actionOnValue='', $actionMessage=''){
		$singleAction = array();
		$singleAction['actionUrl'] = base64_encode($_SERVER['PHP_SELF']);
		$singleAction['action'] = $action;
		
		if($actionMessage == '')
		{
			if($status == '0' || $status == '1'){
				$singleAction['actionMessage'] = base64_encode('Do you want to change display status?');
			}
			else if($status == '2'){
				$singleAction['actionMessage'] = base64_encode('Do you want to delete?');
			}
		}else{
				$singleAction['actionMessage'] = base64_encode($actionMessage);		
		}
		$singleAction['actionField'] = $actionField;
		$singleAction['actionFieldValue'] = $status;
		$singleAction['actionOn'] = $actionOn;
		$singleAction['actionOnValue'] = $actionOnValue;
		$singleAction['actionCallBack'] = base64_encode($_SERVER['REQUEST_URI']);
		if($status == 0){
			$singleAction['actionFieldValue'] = 1;
		}
		else if($status == 1){
			$singleAction['actionFieldValue'] = 0;
		}
		
		$params  = "'$singleAction[action]','$singleAction[actionField]', '$singleAction[actionFieldValue]','$singleAction[actionOn]', '$singleAction[actionOnValue]', '$singleAction[actionCallBack]','$singleAction[actionUrl]','$singleAction[actionMessage]'";
		return $params;
	}
	/*
		This function is used to remove slashes
	*/
	public function removeStripsSlashes($string){
		return stripslashes(stripslashes(htmlspecialchars_decode($string)));
	}
	/*
		It is used for uploading image and to create thumb
	*/
	public function imageUpload($sourceImageName = "",  $destinationImageName = "", $allowedFormat = array(), $allowedImageSize = "", $thumbImageHeight = "", $thumbImageWidth = "", $thumbImageName = "", $uploadPath = "", $thumbUploadPath = "", $thumbCreateStatus = false){
	
		global $conf;
		$imageErrorMessage = array();
		
		$sourceImage     = str_replace("%20", "", $_FILES[$sourceImageName]["name"]);
		$uploadImagePath = $uploadPath.$destinationImageName; 
		$sourceImageType = $_FILES[$sourceImageName]["type"];
		$sourceImageSize = $_FILES[$sourceImageName]["size"];
		
		if(empty($allowedFormat)){
			$imageStatus = true;
		}
		else if(in_array($sourceImageType, $allowedFormat)){
			$imageStatus = true;
		}
		else{
			$imageStatus = false;
			return false;
		}
	
		if($allowedImageSize == ""){
			$imageStatus = true;
		}
		else if($sourceImageSize <= $allowedImageSize){
			$imageStatus = true;
		}
		else{
			$imageStatus = false;
			return false;
		}
		
		if($imageStatus){
			
			if(move_uploaded_file($_FILES[$sourceImageName]["tmp_name"], $uploadImagePath)){
			
				$destinationImage = $uploadPath.$destinationImageName;
				if($thumbCreateStatus && $thumbUploadPath != ""){
				
					$thumbImage = $thumbUploadPath.$thumbImageName;
					switch($sourceImageType){
						case 'image/jpeg':
							$img = imagecreatefromjpeg($destinationImage);
							break;
						case 'image/jpg':
							$img = imagecreatefromjpeg($destinationImage);
							break;
						case 'image/gif':
							$img = imagecreatefromgif($destinationImage);
							break;
						case 'image/png':
							$img = imagecreatefrompng($destinationImage);
							break;
						case 'image/x-png':
							$img = imagecreatefrompng($destinationImage);
							break;
						case 'image/bmp':
							$img = imagecreatefromwbmp($destinationImage);
							break;
						default :
							$img = imagecreatefromgd2($destinationImage);
							break;
					}	
						
					$sourceImageWidth = imagesx($img);
					$sourceImageHeight = imagesy($img);
					$tmp_img = imagecreatetruecolor($thumbImageWidth,$thumbImageHeight);
					imagecopyresized($tmp_img,$img,0,0,0,0,$thumbImageWidth,$thumbImageHeight,$sourceImageWidth,$sourceImageHeight );
					
					switch($sourceImageType){
						case 'image/jpeg':
							imagejpeg($tmp_img,$thumbImage,100);
							break;
						case 'image/jpg':
							imagejpeg($tmp_img,$thumbImage,100);
							break;
						case 'image/gif':
							imagegif($tmp_img,$thumbImage,100);
							break;
						case 'image/png':
							imagepng($tmp_img,$thumbImage,100);
							break;
						case 'image/x-png':
							imagepng($tmp_img,$thumbImage,100);
							break;
						case 'image/bmp':
							imagewbmp($tmp_img,$thumbImage,100);
							break;
					}		
					
					imagedestroy($tmp_img);
				}
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	

	public function checkForAlias($aliasID = ''){
	global $dbObj;
		if($aliasID > 0){
			$sql = "select status from ".$dbObj->addPrefix('pagealias')." where pageAliasID = '$aliasID' ";
			$rs = $dbObj->runQuery($sql);
			if($rw = mysql_fetch_assoc($rs)){
				return $rw['status'];
			}			
		}
	}
	/*Front Pagination with Get(Request) URL*/
	public function frontPagination($sql,$pageUrl = '', $echo='1'){
		global $dbObj, $conf, $action, $module, $params, $sorton,$actionID, $sortby, $alias;
		$rs=mysql_query($sql);
		//echo $sql;
		$nrows=mysql_num_rows($rs);
		if(mysql_num_rows($rs)>$conf['WEBSITEPAGESIZE'])
		{
				
				$page = $this->replaceEmpty('page',1);
				$currpage = $_SERVER['REQUEST_URI'];
				if(!strpos($currpage,"?")){
					$currpage .= "?";	
				}else{
					$pageArray = explode($currpage,"?");
					$currpage = $pageArray[0];
				}
				$currpage = str_replace('&page='.$page,'',$currpage);
				
				if($page==1)
				{
					//$pagelinks.="&lt; PREV ";//ok
				}
				else
				{
					$pageprev = $page-1;
					
					$pagelinks .= '<a href="'.$currpage.'&page='.$pageprev.$pageUrl.'" class="iconPrev">&lt;&nbsp;</a>';
					$pagelinks .= '<a href="'.$currpage.'&page=1'.$pageUrl.'">First </a>';
				}					
				$numofpages=ceil($nrows/$conf['PAGEENDLIMIT']);
				$range=4;
				if($alias->pageModule == 'mobile'){
					$range=1;
				} 
				//echo $range;
				$lrange=max(1,$page-(($range-1)/2));
				$rrange=min($numofpages,$page +(($range-1)/2));
				if(($rrange-$lrange) < ($range-1))
				{
					if($lrange==1)
					{
						$rrange=min($lrange+($range-1),$numofpages);
					}
					else
					{
						$lrange=max($rrange-($range-1),0);	
					}
										
				}
				if($lrange>1)
				{
					$pagelinks.="<span>&nbsp;...&nbsp;";
					
				}
				else
				{
					$pagelinks.="<span>";
				}
				$lastPaginationRange = 1;
				$lastPagination = false;
				$middlePagination = false;
				$middlePaginationRange = 1;
				//echo 'numpage'.$numofpages;
				for($i=1,$l=1;$i<=$numofpages;$i++,$l++)
				{
					if(($l+$range) == $numofpages){
						$pagelinks.="....";
						$pagelinks.="&nbsp;";
						$lastPagination = true;
						$lastPaginationRange = $i;
					}
					if($l == ($numofpages/2)){
						$pagelinks.="....";
						$pagelinks.="&nbsp;";
						$middlePagination = true;
						$middlePaginationRange = $i;
						//echo 'asd'.($numofpages/2);
					}
					if($numofpages > 1 && $i==$page)
					{
						$pagelinks.=$i;	
						$pagelinks.="&nbsp;";
					}
					else if($numofpages > 1)
					{
						if($lrange<=$i && $i<=$rrange)
						{
							$pagelinks.='<a href="'.$currpage.'&page='.$i.$pageUrl.'" class="number">'.$i.'</a>';	
							$pagelinks.="&nbsp;";
						}else{
							if($lastPagination && $lastPaginationRange < $numofpages && $lastPaginationRange > ($numofpages-$range)-1){
									$pagelinks.='<a href="'.$currpage.'&page='.$i.$pageUrl.'" class="number">'.$i.'</a>';	
									$pagelinks.="&nbsp;";
								}else{
									//echo 'entered'.$middlePagination.'|sdfs'.$middlePaginationRange;
									if($middlePagination && $middlePaginationRange >= ($numofpages/2) &&$middlePaginationRange < ($numofpages/2)+$range+1){
										//echo 'asdfjas';
										$pagelinks.='<a href="'.$currpage.'&page='.$i.$pageUrl.'" class="number">'.$i.'</a>';	
										$pagelinks.="&nbsp;";
										$middlePaginationRange = $middlePaginationRange+2;
									}
								}
							
						}
					}	
					
					
				}
				if($rrange<$numofpages)
				{
					$pagelinks.="&nbsp;...&nbsp;</span>";	
				}
				else
				{
					$pagelinks.="</span>";					
				}
				if($nrows-($conf['PAGEENDLIMIT'] * $page)>0)
				{
					$pagelinks.='<a href="'.$currpage.'&page='.$numofpages.$pageUrl.'">Last </a>';
					$pagenext=$page+1;
					$pagelinks.='<a href="'.$currpage.'&page='.$pagenext.$pageUrl.'" class="iconNext">&nbsp;	</a>';
				}
				else
				{
					//$pagelinks.=' NEXT &gt;';//ok
				}
			if( $echo==1){
				print  $pagelinks;
			}else{
				return $pagelinks;
			}
		}
		else
		{
			//$pagelinks= '&lt; PREV &nbsp;&nbsp;&nbsp; NEXT &gt;&nbsp;&nbsp;';
		}
	}
	public function updateActivityLog($activityID = 1){
		global $dbObj;
		$userID = $this->getSession('ACMEMBERID');
		$sql = "Insert into  ".$dbObj->addPrefix('user_activity')." set userID = '$userID' and activitydoneID = '$activityID'";
		$dbObj->runQuery($sql);
		$recentActivityID = mysql_insert_id();
		$this->setSession('RECENTACTIVITY',$recentActivityID);
	}
	public function  checkForAuthenticationLocal(){
		global $dbObj,$conf,$action;
		// $url = 'http://audigyceo.com/botUser/?';
		$sessionKey = '';
		$defaultSessionStatus = false;
$loginfoObj = new Loginfo();
		if(isset($_SESSION[$conf["SESSION_PREFIX"].'SESSIONKEY'])){
			$sessionKey = $this->getSession('SESSIONKEY');
			$defaultSessionStatus = true;
		}else{
			$sessionKey = $action;
		}
		if(!($defaultSessionStatus)){

			$loginfoObj->memberLoginCheckLocal($result);
			header("location:".$conf['ROOT_URL']);
		}else{
						
			return true;
		}
	}
	public function checkForAuthentication(){
		global $dbObj,$conf,$action;
		$finalAuthStatus = false;
		//$url = 'http://audigyceo.com/botUser/?';
		$sessionKey = '';
		$defaultSessionStatus = false;
		if(isset($_SESSION[$conf["SESSION_PREFIX"].'SESSIONKEY'])){
			$sessionKey = $this->getSession('SESSIONKEY');
			$defaultSessionStatus = true;
		}else{
			$sessionKey = $action;
		}
		$fields = array(
						'command' => urlencode('getuser'),
						'DomainToken' => urlencode($conf['DOMAINKEY']),
						'SessionKey' => urlencode($sessionKey)
								
					);

		//url-ify the data for the POST
		$fields_string = '';

		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');
//echo $fields_string;
		//open connection
		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $conf['BOTUSERAPIURL']);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_VERBOSE, false);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
//sdfs
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			


		//execute post
		if(!curl_errno($ch)){
			$result = curl_exec($ch);
			//echo 'Result';
//print_r($result);die;
			$result = explode("\r", $result);

			
			$errorIDArray = explode('=',$result[0]);


			$errorID = $errorIDArray[1];
			$loginfoObj = new Loginfo();
			if($errorID == 0 ){
				
				if(!($defaultSessionStatus)){

					$loginfoObj->memberLogin($result);
					header("location:".$conf['ROOT_URL']);
				}else{
								
					return true;
				}
			}else{
			
				$loginfoObj->doLogOut($sessionKey);
				return false;
			}
			
			
			
			
		}
		//close connection
		curl_close($ch);

	}
	/*
	*	This is a recursive function to rename a file to something that doesn't already exist.
	*       Modified in version 3.2.0 to place a counter within the filename (previously was placed at end)
	*        to ensure documents opened correctly by external browser viewers. If the counter is at the
        *        end of the file, then will use it (to continue to work with older files), however all new
	*        counters will be placed within filenames. 
	*/
	function _rename_file($fname) {
		$file = basename($fname);
		$fparts = split("\.",$fname);
		$path = dirname($fname);
	        if (count($fparts) > 1) {
		  if (is_numeric($fparts[count($fparts) -2]) && (count($fparts) > 2)) {
                        //increment the counter in filename
			$fparts[count($fparts) -2] = $fparts[count($fparts) -2] + 1;
		        $fname = join(".",$fparts);
		  }
		  elseif (is_numeric($fparts[count($fparts) -1]) && $fparts[count($fparts) -1] < 1000) {
		        //increment counter at end of filename (so compatible with previous openemr version files
			$fparts[count($fparts) -1] = $fparts[count($fparts) -1] + 1;
		        $fname = join(".",$fparts);
		  }
	          elseif (is_numeric($fparts[count($fparts) -1])) {
		        //leave date at end and place counter in filename
			array_splice($fparts, -1, 0, "1");
		        $fname = join(".",$fparts);
		  } 		    
		  else {
		        //add the counter to filename
		        array_splice($fparts, -1, 0, "1");
		        $fname = join(".",$fparts);
		  }
	        }
	        else { // (count($fparts) == 1)
		  //place counter at end of filename
		  array_push($fparts,"1");
		  $fname = join(".",$fparts);
		}
	    
		if (file_exists($fname)) {
			return $this->_rename_file($fname);
		}
		else {
			return($fname);	
		}
	}
	
}//end of class

?>

