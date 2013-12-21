<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/sociableCore/app/Views/layout.tpl */
function haanga_cd09f7a71753375a57d4c4fdc05ff49cc17480ed($vars152b59423043b1, $return=FALSE, $blocks=array())
{
    extract($vars152b59423043b1);
    if ($return == TRUE) {
        ob_start();
    }
    echo '<!DOCTYPE html>
<html lang="'.htmlspecialchars(strtolower(substr($lang, 0, 2))).'">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <title>';
    $buffer1  = 'Core';
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

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/lib/img/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/lib/img/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/lib/img/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/lib/img/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="/lib/img/favicon.gif">              

        <link href="/lib/plugins/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="/lib/plugins/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.classes.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.icons.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.ui.css" rel="stylesheet">        
        <link href="/lib/css/style.css" rel="stylesheet">        
                
        ';
    $buffer1  = '';
    echo (isset($blocks['css']) ? (strpos($blocks['css'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['css'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['css'])) : $buffer1).'            
    </head>
    <body class="layout">
			
		<div class="ui-layout-north ui-shadow">
			<div class="ui-layout-content noOverflow">
				<div class="row clearfix">
					<div class="col-md-12 column">
						<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
							<div class="navbar-header">
								 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> 
								 	<span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
								 	<span class="icon-bar"></span><span class="icon-bar"></span>
								 </button> 
								 <a class="navbar-brand" href="/">nbonnici.info:~$</a>
							</div>
							
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
									<li';
    if (isset($currentPage) && $currentPage == 'homepage') {
        echo ' class="active"';
    }
    echo '>
										<a href="/" title="'.htmlspecialchars($tr['homepage_tip']).'"><span class="glyphicon glyphicon-home"></span> '.htmlspecialchars($tr['homepage']).'</a>
									</li>
									<li>
										<a href="/portfolio" title="'.htmlspecialchars($tr['portfolio_tip']).'"><span class="glyphicon glyphicon-folder-open"></span> '.htmlspecialchars($tr['portfolio']).'</a>
									</li>
									<li>
										<a href="/contact" title="'.htmlspecialchars($tr['contact_tip']).'"><span class="glyphicon glyphicon-envelope"></span> '.htmlspecialchars($tr['contact']).'</a>
									</li>
								</ul>
								<form class="navbar-form navbar-left" role="search">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="'.htmlspecialchars($tr['search_helper']).'" />
									</div> <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
								</form>
								<ul class="nav navbar-nav navbar-right">
									';
    if (isset($aSession)) {
        echo '
									<li class="dropdown">
										<a data-toggle="dropdown" class="dropdown-toggle" href="#">
											<img src="'.htmlspecialchars($sGravatarSrc16).'" class="ui-nav-avatar" alt="Avatar" />'.htmlspecialchars($aSession['firstname']).' '.htmlspecialchars($aSession['lastname']).' <strong class="caret"></strong>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="/profile"><span class="glyphicon glyphicon-user"></span> '.htmlspecialchars($tr['my_account']).'</a>
											</li>
											<li>
												<a href="/backend/setup/"><span class="glyphicon glyphicon-cog"></span> '.htmlspecialchars($tr['administration']).'</a>
											</li>
											<li>
												<a class="ui-pane-pin" data-pane="west" title="'.htmlspecialchars($tr['toggle_menu_tip']).'">									
													<span class="glyphicon glyphicon-log-in"></span> '.htmlspecialchars($tr['toggle_menu']).'
												</a>
											</li>
											<li class="divider"></li>
											<li><a href="/logout">'.htmlspecialchars($tr['logout']).'</a>
											</li>
										</ul>
									</li> 
									';
    } else {
        echo '
									<li class="">
										<a href="#" class="ui-login-popover"><strong>'.htmlspecialchars($tr['login']).'</strong></a>
										
										<div id="login-popover" class="hide">
											<div class="row clearfix">
												<div class="col-md-12 column">
										        	<form class="form-horizontal well" role="form" method="POST" action="/frontend/auth/">
														<div class="form-group">
															 <label for="emailInput" class="col-sm-2 control-label">Email</label>
															<div class="col-sm-10">
										                    	<input type="email" placeholder="john.doe@youremail.com" class="form-control" id="emailInput" name="email">
															</div>
														</div>
														<div class="form-group">
															 <label for="inputPassword" class="col-sm-2 control-label">Password</label>
															<div class="col-sm-10">
																<input type="password" placeholder="type your password" class="form-control" id="inputPassword" name="password">					
															</div>
														</div>
														<div class="form-group">
															<div class="col-sm-offset-2 col-sm-10">
																<div class="checkbox">
																	 <label><input type="checkbox" /> Remember me</label>
																</div>
															</div>
														</div>
														<div class="form-group">
															<div class="col-sm-offset-2 col-sm-10">
										                         <button type="submit" id="submit" class="btn btn-primary button-loading" data-loading-text="Loading...">Sign in</button>
										                         <button type="button" class="btn btn-secondary button-loading" data-loading-text="Loading...">Forgot Password</button>						 
															</div>
														</div>			
													</form>
												</div>
											</div>
										</div>
									</li>
									';
    }
    echo '
								</ul>
							</div>							
						</nav>
					</div>
				</div>							
			</div>
		</div>
	
		<div class="ui-layout-west whiteBg ui-scrollable GPUrender">
			<div class="ui-layout-content">
				<div class="row">
					<div class="col-md-12">
						<form class="form-search padding">
							<input type="text" class="input-medium search-query"
								placeholder="'.htmlspecialchars($tr['input_search']).'" />
							<button type="submit" class="btn">
								<em class="icon-search"></em>&nbsp;Search
							</button>
						</form>
	
						<div id="ui-menu" class="ui-loadable" data-module="'.htmlspecialchars($sModule).'" data-controller="menu" data-action="index"></div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="ui-layout-center ui-scrollable">
	
			<div class="ui-layout-content transparentBg ui-loadscroll">		
				<div class="container">
					';
    $buffer1  = '';
    echo (isset($blocks['main']) ? (strpos($blocks['main'], '{{block.1b3231655cebb7a1f783eddf27d254ca}}') === FALSE ? $blocks['main'] : str_replace('{{block.1b3231655cebb7a1f783eddf27d254ca}}', $buffer1, $blocks['main'])) : $buffer1).'
				</div>
			</div>
		</div>
	
		<div class="ui-layout-east transparentBlackBg ui-scrollable">
			<div class="ui-layout-content">
				<p>modules list</p>
			</div>
		</div>
	
		<div class="ui-layout-south ui-shadow">
			<div class="ui-layout-content">
				<div id="activityDebug">'.Haanga::Load($sDeBugHelper, $vars152b59423043b1, TRUE, $blocks).'</div>
			</div>
		</div>
	
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