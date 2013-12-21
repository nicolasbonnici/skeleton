<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/frontend/Views/home/index.tpl */
function haanga_a580d0528c34dacab489391ed23770c4b04570a2($vars152b59376e7d43, $return=FALSE, $blocks=array())
{
    extract($vars152b59376e7d43);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'Test';
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

                <div class="row transparentBlackBg margin ui-shadow rounded">
                        <div class="col-md-12">
                                        <h1>
                                                Hello, world!
                                        </h1>
                                        <p>'.htmlspecialchars($tr['hello']).' template engine! (rendered by haanga)</p>

                                        <p>
                                                <a class="btn btn-primary btn-large" href="#">Learn more »</a>
                                        </p>
                        </div>
                </div>
				<br />
				<div class="row">
					<div id="ui-grid" class="ui-loadable ui-scroll-loadable ui-grid" data-module="frontend" data-controller="home" data-action="list">
			
					</div>    				
				</div>



';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load($appLayout, $vars152b59376e7d43, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}