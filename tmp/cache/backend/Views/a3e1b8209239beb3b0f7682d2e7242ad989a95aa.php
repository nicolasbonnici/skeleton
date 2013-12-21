<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/modules/backend/Views/home/index.tpl */
function haanga_a3e1b8209239beb3b0f7682d2e7242ad989a95aa($vars152b59423043b1, $return=FALSE, $blocks=array())
{
    extract($vars152b59423043b1);
    if ($return == TRUE) {
        ob_start();
    }
    $buffer1  = 'Test';
    $blocks['meta_title']  = (isset($blocks['meta_title']) ? (strpos($blocks['meta_title'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_title'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_title'])) : $buffer1);
    $buffer1  = '';
    $blocks['meta_description']  = (isset($blocks['meta_description']) ? (strpos($blocks['meta_description'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_description'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_description'])) : $buffer1);
    $buffer1  = '
<script type="text/javascript" src="/lib/plugins/hotkeys/js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-wysiwyg/js/bootstrap-wysiwyg.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
';
    $blocks['js']  = (isset($blocks['js']) ? (strpos($blocks['js'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['js'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['js'])) : $buffer1);
    $buffer1  = '
<link href="/lib/plugins/bootstrap-wysiwyg/css/bootstrap-wysiwyg.css" rel="stylesheet">
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
';
    $blocks['css']  = (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1);
    $buffer1  = '
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>
					Example page header <small>Subtext for header</small>
				</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div id="ui-grid" class="ui-loadable ui-loadscroll ui-grid" data-module="frontend" data-controller="home" data-action="list">

		</div>    				
	</div>
';
    $blocks['main']  = (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1);
    echo Haanga::Load('layout.tpl', $vars152b59423043b1, TRUE, $blocks);
    if ($return == TRUE) {
        return ob_get_clean();
    }
}