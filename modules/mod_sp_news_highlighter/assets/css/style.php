<?php
header("Content-Type: text/css");
$uniqid 		= $_GET['uniqid'];
$width 			= $_GET['width'];
$height 		= $_GET['height'];
$linkcolor		= $_GET['linkcolor'];
$linkhover		= $_GET['linkhover'];
$arrows			= $_GET['arrows'];
?>
.button_area {float:left;position:relative}
span.text {margin:0 0 0 5px;}
span.title{margin-right:5px;}
span.date {font-size:70%;font-style:italic;}
a.news_link {color:#<?php echo $linkcolor; ?>}
a.news_link:hover {color:#<?php echo $linkhover; ?>}
.sp-nh-prev,.sp-nh-next{width: 13px; height: <?php echo $height; ?>px; position: absolute; top:0;cursor:pointer;background-image: url(../images/<?php echo $arrows; ?>);background-repeat:no-repeat;}
.sp-nh-prev{right:20px; background-position: 0 50%;}
.sp-nh-next{right:5px; background-position: -26px 50%;}
.sp-nh-prev:hover{background-position: -13px 50%;}
.sp-nh-next:hover{background-position: -39px 50%;}