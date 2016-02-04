<?php
    /*------------------------------------------------------------------------
    # mod_sp_countdown - Countdown module by JoomShaper.com
    # ------------------------------------------------------------------------
    # author    JoomShaper http://www.joomshaper.com
    # Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
    # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
    # Websites: http://www.joomshaper.com
    -------------------------------------------------------------------------*/

    // no direct access
    defined('_JEXEC') or die('Restricted access');
    //Parameters
    $ID 				= $module->id;
    $document 			= JFactory::getDocument();
    $document->addStylesheet(JURI::base(true) . '/modules/'.basename(dirname(__FILE__)).'/assets/css/sp_countdown.css');
    require(JModuleHelper::getLayoutPath(basename(dirname(__FILE__)))); 
?>

<script type="text/javascript">
//<![CDATA[
    window.addEvent('domready', function() {
            function calcage(secs, num1, num2, starthtml, endhtml, singular, plural) {
                s = ((Math.floor(secs/num1))%num2).toString();
                z = ((Math.floor(secs/num1))%num2);
                if (LeadingZero && s.length < 2)
                    {
                    s = "0" + s;
                }
                return starthtml 
                + '<div class="sp_countdown_int"> '
                + s + '</div>' 
                +  '<div class="sp_countdown_string"> '
                + ((z<=1)?singular:plural) 
                + '</div>' 
                + endhtml;
            }

            function CountBack(secs) {
                if (secs < 0) {
                    document.getElementById("sp_countdown_cntdwn<?php echo $module->id?>").innerHTML = '<div class="sp_countdown_finishtext">'+FinishMessage+'</div>';
                    return;
                }
                DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000, 
                        '<div class="sp_countdown_days">','</div>',' <?php echo $params->get("day_text","Day")?>', ' <?php echo $params->get("day_text","Day")?>s'));
                DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24, 
                        '<div class="sp_countdown_hours">','</div>',' <?php echo $params->get("hr_text","Hr")?>', ' <?php echo $params->get("hr_text","Hr")?>s'));
                DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60, 
                        '<div class="sp_countdown_mins">','</div>', ' <?php echo $params->get("min_text","Min")?>', ' <?php echo $params->get("min_text","Min")?>s'));
                DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60, 
                        '<div class="sp_countdown_secs">','</div>', ' <?php echo $params->get("sec_text","Sec")?>', " <?php echo $params->get("sec_text","Sec")?>s"));

                document.getElementById("sp_countdown_cntdwn<?php echo $module->id?>").innerHTML = DisplayStr;
                if (CountActive)
                    setTimeout(function(){

                        CountBack((secs+CountStepper))  

                    }, SetTimeOutPeriod);
            }

            function putspan(backcolor, forecolor) {
            
            }

            if (typeof(BackColor)=="undefined")
                BackColor = "";
            if (typeof(ForeColor)=="undefined")
                ForeColor= "";
            if (typeof(TargetDate)=="undefined")
                TargetDate = "<?php echo $params->get("date_start","12/31/2013") .' '. $params->get("time","12:00 AM")?>";
            if (typeof(DisplayFormat)=="undefined")
                DisplayFormat = "%%D%%  %%H%%  %%M%%  %%S%% ";
            if (typeof(CountActive)=="undefined")
                CountActive = true;
            if (typeof(FinishMessage)=="undefined")
                FinishMessage = "<?php echo $params->get("finish_text","")?>";
            if (typeof(CountStepper)!="number")
                CountStepper = -1;
            if (typeof(LeadingZero)=="undefined")
                LeadingZero = true;

            CountStepper = Math.ceil(CountStepper);
            if (CountStepper == 0)
                CountActive = false;
            var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
            putspan(BackColor, ForeColor);
            var dthen = new Date(TargetDate);
            var dnow = new Date();
            if(CountStepper>0)
                ddiff = new Date(dnow-dthen);
            else
                ddiff = new Date(dthen-dnow);
            gsecs = Math.floor(ddiff.valueOf()/1000);
            CountBack(gsecs);
    });
//]]>	
</script>