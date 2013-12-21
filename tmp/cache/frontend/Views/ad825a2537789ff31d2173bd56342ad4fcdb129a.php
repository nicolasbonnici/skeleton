<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/menu/index.tpl */
function haanga_ad825a2537789ff31d2173bd56342ad4fcdb129a($vars152b5938a95998, $return=FALSE, $blocks=array())
{
    extract($vars152b5938a95998);
    if ($return == TRUE) {
        ob_start();
    }
    echo '	<div class="row clearfix">		
		<div class="col-md-12 column">
			<div class="panel-group" id="ui-mvc-menu">
				<div class="panel panel-default">
					<div class="panel-heading">
						 <a class="panel-title" data-toggle="collapse" data-parent="#ui-mvc-menu" href="#ui-mvc-menu-all">Frontend</a>
					</div>
					<div id="ui-mvc-menu-all" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="list-group">
								<a href="#" class="list-group-item active">Home</a>
								<div class="list-group-item">
								';
    foreach ($aControllers as  $sController => $aActions) {
        echo '
									<h4 class="list-group-item-heading">
										'.htmlspecialchars($sController).'
									</h4>									
									';
        foreach ($aActions as  $sAction) {
            echo '
									<p class="list-group-item-text">
										<a href="/'.htmlspecialchars($sModule).'/'.htmlspecialchars($sController).'/'.htmlspecialchars($sAction).'">'.htmlspecialchars($sAction).'</a>
									</p>				
									';
        }
        echo '		
								';
    }
    echo '									
								</div>
								<div class="list-group-item">
									<span class="badge">14</span>Help
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
								
								';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}