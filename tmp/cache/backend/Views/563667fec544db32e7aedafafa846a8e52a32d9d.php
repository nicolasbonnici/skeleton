<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/app/Views/helpers/debug.tpl */
function haanga_563667fec544db32e7aedafafa846a8e52a32d9d($vars152b59423043b1, $return=FALSE, $blocks=array())
{
    extract($vars152b59423043b1);
    if ($return == TRUE) {
        ob_start();
    }
    echo '            	<div class="row-fluid">	            	
					<div class="span12 alert alert-success alert-block">
					  <button type="button" class="close" data-dismiss="alert">×</button>
					  <h4><em class="icon icon-time"></em>/'.htmlspecialchars($sController).'/'.htmlspecialchars($sAction).'&nbsp;Rendered in '.htmlspecialchars(($render_time - $framework_started)).'</h4>

					  <ul>
					  ';
    foreach ($aLoadedClass as  $sClass) {
        echo '
					  	<li><strong>'.htmlspecialchars($sClass).'</strong></li>
					  ';
    }
    echo '
					  </ul>
					</div>									            	
            	</div>';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}