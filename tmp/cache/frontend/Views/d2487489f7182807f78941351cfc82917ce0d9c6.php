<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/home/portfolio.tpl */
function haanga_d2487489f7182807f78941351cfc82917ce0d9c6($vars152b593d6b0dd9, $return=FALSE, $blocks=array())
{
    extract($vars152b593d6b0dd9);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'nbonnici.info:~$';
    $blocks['meta_title']  = (isset($blocks['meta_title']) ? (strpos($blocks['meta_title'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_title'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_title'])) : $buffer1);
    $buffer1  = '';
    $blocks['meta_description']  = (isset($blocks['meta_description']) ? (strpos($blocks['meta_description'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_description'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_description'])) : $buffer1);
    $buffer1  = '

';
    $blocks['css']  = (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1);
    $buffer1  = '
<script type="text/javascript" src="/lib/plugins/knob/js/jquery.knob.js"></script>
<<script type="text/javascript">
<!--
$(document).ready(function() {
    // init circular progressbars
    $(\'.ui-circular-progress\').knob();	
});
//-->
</script>
';
    $blocks['js']  = (isset($blocks['js']) ? (strpos($blocks['js'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['js'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['js'])) : $buffer1);
    $buffer1  = '
                <div class="row">
                        <div class="col-md-12">
                                <div class="row well ui-shadow">
                                	<div class="col-md-3">
                                		<h1>PHP5</h1>
										<input type="text" class="ui-circular-progress" value="90" data-fgColor="#669" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">     		
										<h1>Mysql</h1>
										<input type="text" class="ui-circular-progress" value="80" data-fgColor="#e97b00" data-readOnly="true">
										<h1>Oracle SQL</h1>
										<input type="text" class="ui-circular-progress" value="60" data-fgColor="#e97b00" data-readOnly="true">
										<h1>NoSQL (MongoDb)</h1>
										<input type="text" class="ui-circular-progress" value="70" data-fgColor="#e97b00" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">                                	
										<h1>JavaScript</h1>
										<input type="text" class="ui-circular-progress" value="70" data-fgColor="#1eff00" data-readOnly="true">
										<h1>jQuery</h1>
										<input type="text" class="ui-circular-progress" value="95" data-fgColor="#1eff00" data-readOnly="true">
                                	</div>
                                	<div class="col-md-3">     
										<h1>CSS3</h1>
										<input type="text" class="ui-circular-progress" value="85" data-fgColor="#48acde" data-readOnly="true">
										<h1>CSS2</h1>
										<input type="text" class="ui-circular-progress" value="100" data-fgColor="#48acde" data-readOnly="true">
									</div>
                                </div>                    
                        </div>
                </div>



';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load($appLayout, $vars152b593d6b0dd9, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}