<?php
/**
 * @version: $Id: rchooser.php 4387 2015-02-19 12:24:35Z Radek Suski $
 * @package: SobiPro Component for Joomla!

 * @author
 * Name: Sigrid Suski & Radek Suski, Sigsiu.NET GmbH
 * Email: sobi[at]sigsiu.net
 * Url: http://www.Sigsiu.NET

 * @copyright Copyright (C) 2006 - 2015 Sigsiu.NET GmbH (http://www.sigsiu.net). All rights reserved.
 * @license GNU/GPL Version 3
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License version 3 as published by the Free Software Foundation, and under the additional terms according section 7 of GPL v3.
 * See http://www.gnu.org/licenses/gpl.html and http://sobipro.sigsiu.net/licenses.

 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

 * $Date: 2015-02-19 13:24:35 +0100 (Thu, 19 Feb 2015) $
 * $Revision: 4387 $
 * $Author: Radek Suski $
 * $HeadURL: file:///opt/svn/SobiPro/Component/branches/SobiPro-1.1/Site/views/tpl/rchooser.php $
 */

defined( 'SOBIPRO' ) || exit( 'Restricted access' );
?>
<script language="javascript" type="text/javascript">
    function SP_selectCat( sid )
    {
        try {
            SP_id( 'sobiCats_CatUrl' + sid ).focus();
        }
        catch ( e ) {}
        parent.document.getElementById( 'selectedCat' ).value = sid;
        parent.document.getElementById( 'selectedCatName' ).value = SP_id( 'sobiCats_CatUrl' + sid ).innerHTML;
    }
</script>
<div style="margin: 5px; padding: 5px;">
    <div><?php $this->get( 'tree' )->display(); ?></div>
</div>
