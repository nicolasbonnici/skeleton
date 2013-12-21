<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/feeds/index.tpl */
function haanga_1c1059816e3153a36ebf683de0e29dc4c399444d($vars152b5942f8d929, $return=FALSE, $blocks=array())
{
    extract($vars152b5942f8d929);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'Feeds';
    $blocks['meta_title']  = (isset($blocks['meta_title']) ? (strpos($blocks['meta_title'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_title'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_title'])) : $buffer1);
    $buffer1  = '';
    $blocks['meta_description']  = (isset($blocks['meta_description']) ? (strpos($blocks['meta_description'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_description'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_description'])) : $buffer1);
    $buffer1  = '

';
    $blocks['css']  = (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1);
    $buffer1  = '

';
    $blocks['js']  = (isset($blocks['js']) ? (strpos($blocks['js'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['js'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['js'])) : $buffer1);
    $buffer1  = '
			';
    if (isset($oNewTwitterActivities) && isset($oNewTwitterActivities->oAddedFeedActivities) && isset($oTwitterFeed)) {
        $buffer1 .= '
				<div class="row-fluid">
					<div class="span12 alert alert-success alert-block">
					  <button type="button" class="close" data-dismiss="alert">×</button>
					  <h4><img src="'.htmlspecialchars($oTwitterFeed->icon).'" alt="Feed icon" />'.htmlspecialchars($oNewTwitterActivities->oAddedFeedActivities->count).'&nbsp;Nouvelle(s) activité(s) sur '.htmlspecialchars($oTwitterFeed->domain).' enregistré(s).</h4>
					  <p>Sur '.htmlspecialchars($oTwitterFeed->domain).' pour le feed '.htmlspecialchars($oTwitterFeed->title).'.</p>
					  ';
        if (isset($oNewTwitterActivities->oAddedFeedActivities)) {
            $buffer1 .= '
					  <ul>
					  	';
            foreach ($oNewTwitterActivities->oAddedFeedActivities as  $oAddedAcitivities) {
                $buffer1 .= '
					  	<li>'.htmlspecialchars($oAddedAcitivities->title).'</li>
					  	';
            }
            $buffer1 .= '					  
					  <ul>
					  ';
        }
        $buffer1 .= '
					  
					  </ul>
					</div>				
				</div>
			';
    }
    $buffer1 .= '				
			';
    if (isset($oNewTwitterActivities)) {
        $buffer1 .= '	
				<div id="ui-grid" class="row-fluid" data-module="frontend" data-controller="home" data-action="list" data-columns>
				
					';
        foreach ($oNewTwitterActivities as  $oItem) {
            $buffer1 .= '
						<div class="item well ui-shadow">
							<h4>
								<a href="'.htmlspecialchars($oItem->permalink).'">'.$oItem->title.'</a>
							</h4>
							';
            if (isset($oItem->content)) {
                $buffer1 .= '
							<div>

							</div>
							';
            }
            $buffer1 .= '
							<span class="timestamp2Date" data-timestamp="'.htmlspecialchars($oItem->created).'"></span>
						</div>	
												
					';
        }
        $buffer1 .= '
						
				</div>    			
			';
    }
    $buffer1 .= '				


';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load($appLayout, $vars152b5942f8d929, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}