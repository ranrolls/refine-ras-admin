<?php
 
$per_quize_cost=$_REQUEST["coupon[per_quiz_cost]"];
$Totalbudget=$_REQUEST['Totalbudget']; 
$questiontitle1=$_REQUEST['question-title1'];  
$answertitle11=$_REQUEST['answer-title1-1'];  
$answertitle12=$_REQUEST['answer-title1-2'];  
$answertitle13=$_REQUEST['answer-title1-3'];  
$correct_answer1=$_REQUEST['correct_answer1'];
$questiontitle2=$_REQUEST['question-title2'];
$answertitle21=$_REQUEST['answer-title2-1'];
$correct_answer2=$_REQUEST['correct_answer2'];  
$answertitle22=$_REQUEST['answer-title2-2']; 
$answertitle23=$_REQUEST['answer-title2-3']; 
$questiontitle3=$_REQUEST['question-title3']; 
$answertitle31=$_REQUEST['answer-title3-1'];  
$answertitle32=$_REQUEST['answer-title3-2'];
$correct_answer3=$_REQUEST['correct_answer3'];
$answertitle33=$_REQUEST['answer-title3-3'];
$questiontitle4=$_REQUEST['question-title4'];
$answertitle41=$_REQUEST['answer-title4-1'];
$answertitle42=$_REQUEST['answer-title4-2']; 
$answertitle43=$_REQUEST['answer-title4-3']; 
$correct_answer4=$_REQUEST['correct_answer4']; 
$questiontitle5=$_REQUEST['question-title5']; 
$answertitle51=$_REQUEST['answer-title5-1']; 
$answertitle52=$_REQUEST['answer-title5-2']; 
$answertitle53=$_REQUEST['answer-title5-3']; 
$correct_answer5=$_REQUEST['correct_answer5']; 
$total_questions=$_REQUEST['total_questions']; 
$auth_token=$_REQUEST['auth_token']; 



$url="http://api.mydeals247.com/coupons/edit_questionaire/<Ad-ID>/update.json?=coupon[per_quize_cost]=".urlencode($per_quize_cost)."&Totalbudget=".urlencode($Totalbudget)."&question-title1=".urlencode($questiontitle1)."&answer-title1-1 =".urlencode($answertitle11)."&answer-title1-2 =".urlencode($answertitle12)."&answer-title1-3=".urlencode($answertitle13)."&correct_answer1=".urlencode($correct_answer1)."&question-title2=".urlencode($questiontitle2)."&answer-title2-1=".urlencode($answertitle21)."&correct_answer2=".urlencode($correct_answer2)."&answer-title2-2=".urlencode($answertitle22)."&answer-title2-3=".urlencode($answertitle23)."&question-title3=".urlencode($questiontitle3)."&answer-title3-1=".urlencode($answertitle31)."&answer-title3-2=".urlencode($answertitle32)."&correct_answer3=".urlencode($correct_answer3)."&answer-title3-3=".urlencode($answertitle33)."&question-title4 =".urlencode($questiontitle4)."&answer-title4-1=".urlencode($answertitle41)."&answer-title4-2=".urlencode($answertitle42)."&answer-title4-3=".urlencode($answertitle43)."&correct_answer4=".urlencode($correct_answer4)."&question-title5=".urlencode($questiontitle5)."&answer-title5-1=".urlencode($answertitle51)."&answer-title5-2=".urlencode($answertitle52)."&answer-title5-3=".urlencode($answertitle53)."&correct_answer5=".urlencode($correct_answer5)."&total_questions=".urlencode($total_questions)."&auth_token=".urlencode($auth_token);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);



    echo $Jsoncallback . '(' . $data . ');';



?>