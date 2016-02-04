<?php defined('_JEXEC') or die('Restircted access'); ?>
<!-- SiteSearch Google -->
<form method="get" action="http://www.google.com/custom" target="_top">
        <input type="text" style="border:1px solid #A5ACB2;font-size:0.85em;width:10em;height:14px;" name="q" size="12" maxlength="255" placeholder="Search" value="" onfocus="this.select()" />
        <input type="submit" style="border:none;background-image:url(<?php echo $button; ?>);width:58px;height:20px;vertical-align:middle;" class="button" name="sa" value="" />

        <input type="hidden" name="domains" value="<?php echo $main_url; ?>" />
        <input type="hidden" name="sitesearch" value="<?php echo $main_url; ?>" id="ss1" />
        <input type="hidden" name="client" value="<?php echo $client_id; ?>" />
        <input type="hidden" name="forid" value="1" />
        <input type="hidden" name="ie" value="UTF-8" />
        <input type="hidden" name="oe" value="UTF-8" />

        <input type="hidden" name="safe" value="active" />
        <input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;LH:50;LW:234;L:http://<?php echo $main_url, '/', $logo; ?>;S:http://<?php echo $main_url; ?>;FORID:1" />
        <input type="hidden" name="hl" value="en" />
</form>
<!-- End SiteSearch Google -->