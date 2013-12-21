<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/home/list.tpl */
function haanga_05733201839e3d6edbbbd29d5a56676ed9045184($vars152b5938aa902b, $return=FALSE, $blocks=array())
{
    extract($vars152b5938aa902b);
    if ($return == TRUE) {
        ob_start();
    }
    if (isset($oItems)) {
        echo '
	';
        foreach ($oItems as  $oItem) {
            echo '
	
		';
            $sItemType  = '';
            $vars152b5938aa902b['sItemType']  = $sItemType;
            echo '
		';
            if ($oItem->feeds_idfeed == 1) {
                echo '
			';
                $sItemType  = 'twitter';
                $vars152b5938aa902b['sItemType']  = $sItemType;
                echo '
		';
            }
            echo '
		';
            if ($oItem->feeds_idfeed == 2) {
                echo '
			';
                $sItemType  = 'google';
                $vars152b5938aa902b['sItemType']  = $sItemType;
                echo '
		';
            }
            echo '
		';
            if ($oItem->feeds_idfeed == 3) {
                echo '
			';
                $sItemType  = 'google+';
                $vars152b5938aa902b['sItemType']  = $sItemType;
                echo '
		';
            }
            echo '	
	
		<div class="item '.htmlspecialchars($sItemType).' col-md-12 well ui-shadow">
			';
            if (isset($oSession)) {
                echo '
			<span>
				<a href="#" class="ui-editable" data-url="/backend/feedsItem/update/id/'.htmlspecialchars($oItem->status).'" data-type="text" data-pk="'.htmlspecialchars($oItem->idfeedItem).'" data-name="title" title="'.htmlspecialchars($tr['update']).'">'.htmlspecialchars($oItem->status).'</a>
			</span>
			';
            }
            echo '
			<h4>
			';
            if ($sItemType == 'twitter') {
                echo '
				<img alt="feed icon" src="http://twitter.com/favicon.ico">
			';
            }
            echo '
			';
            if ($sItemType == 'google') {
                echo '
				<img alt="feed icon" src="http://google.com/favicon.ico">
			';
            }
            echo '
			';
            if ($sItemType == 'google+') {
                echo '
				<img alt="feed icon" src="http://plus.google.com/favicon.ico">
			';
            }
            echo '
				&nbsp;<a href="'.htmlspecialchars($oItem->permalink).'">'.$oItem->title.'</a>
				
			</h4>
			<span class="ui-timestamp" data-timestamp="'.htmlspecialchars($oItem->created).'"></span>
		</div>	
		
	';
        }
        echo '	
';
    }
    echo '		';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}