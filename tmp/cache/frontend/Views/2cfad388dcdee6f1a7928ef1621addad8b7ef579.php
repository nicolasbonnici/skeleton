<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/app/Views/loginLayout.tpl */
function haanga_2cfad388dcdee6f1a7928ef1621addad8b7ef579($vars152b59416bc238, $return=FALSE, $blocks=array())
{
    extract($vars152b59416bc238);
    if ($return == TRUE) {
        ob_start();
    }
    echo '<!DOCTYPE html>
<html lang="'.htmlspecialchars(strtolower(substr($lang, 0, 2))).'">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <title>';
    $buffer1  = ''.htmlspecialchars($tr['login']);
    echo (isset($blocks['meta_title']) ? (strpos($blocks['meta_title'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_title'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_title'])) : $buffer1).'</title>
        
        <meta name="description" content="';
    $buffer1  = '';
    echo (isset($blocks['meta_description']) ? (strpos($blocks['meta_description'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_description'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_description'])) : $buffer1).'" />
        <meta name="keywords" content="';
    $buffer1  = '';
    echo (isset($blocks['meta_keyword']) ? (strpos($blocks['meta_keyword'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_keyword'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_keyword'])) : $buffer1).'" />
        <meta name="application-name" content="';
    $buffer1  = 'sociableCore';
    echo (isset($blocks['meta_app']) ? (strpos($blocks['meta_app'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_app'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_app'])) : $buffer1).'" />
        <meta name="author" content="';
    $buffer1  = 'Nicolas BONNICI';
    echo (isset($blocks['meta_author']) ? (strpos($blocks['meta_author'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['meta_author'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['meta_author'])) : $buffer1).'" />        

        <!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
        <!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
        <!--script src="js/less-1.3.3.min.js"></script-->
        <!--append ‘#!watch’ to the browser URL, then refresh the page. -->

        <link href="/lib/plugins/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="/lib/plugins/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.classes.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.icons.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.ui.css" rel="stylesheet">               

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/lib/img/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/lib/img/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/lib/img/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/lib/img/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="/lib/img/favicon.png">        
        
        ';
    $buffer1  = '';
    echo (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1).'            
        
    </head>
    <body class="GPUrender">        

        ';
    $buffer1  = '';
    echo (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1).'  

		<script	type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script	type="text/javascript" src="/lib/plugins/layout/js/jquery.layout.min.js"></script>
		<script type="text/javascript" src="/lib/plugins/bootstrap3/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/lib/plugins/pnotify/js/jquery.pnotify.js"></script>
		';
    $buffer1  = '';
    echo (isset($blocks['js']) ? (strpos($blocks['js'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['js'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['js'])) : $buffer1).'
		<script type="text/javascript" src="/lib/plugins/core/js/ui.core.js"></script>
		<script type="text/javascript" src="/lib/plugins/core/js/core.js"></script>
    </body>
</html>';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}