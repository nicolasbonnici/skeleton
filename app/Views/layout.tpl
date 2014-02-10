<!DOCTYPE html>
<html lang="{{lang|Substr: '0,2'|lower}}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{% block meta_title %}Core{% endblock %}</title>

        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="/lib/img/favicon.gif">              
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/lib/img/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/lib/img/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/lib/img/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/lib/img/apple-touch-icon-57-precomposed.png">

        <meta name="description" content="{% block meta_description %}{% endblock %}" />
        <meta name="keywords" content="{% block meta_keyword %}{% endblock %}" />
        <meta name="application-name" content="{% block meta_app %}sociableCore{% endblock %}" />
        <meta name="author" content="{% block meta_author %}Nicolas BONNICI{% endblock %}" />        

        <!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
        <!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
        <!--script src="js/less-1.3.3.min.js"></script-->
        <!--append ‘#!watch’ to the browser URL, then refresh the page. -->

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->

        <link href="/lib/plugins/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="/lib/plugins/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/lib/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.classes.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.icons.css" rel="stylesheet">
        <link href="/lib/plugins/core/css/core.ui.css" rel="stylesheet">        
        <link href="/lib/css/style.css" rel="stylesheet">        
               
        {% block css %}{% endblock %}            
    </head>
    <body class="layout">
    
		<div class="ui-layout-north ui-shadow">
			<div class="ui-layout-content noOverflow">
				<div class="row clearfix">
					<div class="col-md-12 column">
						<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">							
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
									<li{% if sAction|Exists && sAction == 'indexAction' %} class="active"{% endif %}>
										<a href="/" title="{{tr['homepage_tip']}}"><span class="glyphicon glyphicon-home"></span> {{tr['homepage']}}</a>
									</li>
									<li{% if sAction|Exists && sAction == 'lifestreamAction' %} class="active"{% endif %}>
										<a href="/lifestream" title="{{tr['lifestream_tip']}}"><span class="glyphicon glyphicon-globe"></span> {{tr['lifestream']}}</a>
									</li>
									<li{% if sAction|Exists && sAction == 'portfolioAction' %} class="active"{% endif %}>
										<a href="/portfolio" title="{{tr['portfolio_tip']}}"><span class="glyphicon glyphicon-folder-open"></span> {{tr['portfolio']}}</a>
									</li>
									<li{% if sAction|Exists && sAction == 'contactAction' %} class="active"{% endif %}>
										<a href="/contact" title="{{tr['contact_tip']}}"><span class="glyphicon glyphicon-envelope"></span> {{tr['contact']}}</a>
									</li>
									<li class="dropdown{% if sAction|Exists && sAction == 'homepage' %} active{% endif %}">
										<a href="#" title="{{tr['search_tip']}}" class="dropdown-toggle" data-toggle="dropdown">
											<span class="glyphicon glyphicon-search"></span> {{tr['search']}}
										</a>									
										<ul class="dropdown-menu">
											<form class="navbar-form navbar-left" role="search">
												<div class="form-group">
													<input type="text" class="form-control" placeholder="{{tr['search_helper']}}" />
												</div> 
											</form>	
										</ul>
									</li>									
								</ul>
								<ul class="nav navbar-nav navbar-right">
								{# @todo faire un filtre haanga pour s assurer de l integrite de la session #}
									{% if aSession|Exists %}
									<li class="dropdown">
										<a data-toggle="dropdown" class="dropdown-toggle" href="#">
											<img src="{{sGravatarSrc16}}" class="ui-nav-avatar" alt="Avatar" />{{aSession['firstname']}} {{aSession['lastname']}} <strong class="caret"></strong>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="/profile"><span class="glyphicon glyphicon-user"></span> {{tr['my_account']}}</a>
											</li>
											<li>
												<a href="/backend/setup/"><span class="glyphicon glyphicon-cog"></span> {{tr['administration']}}</a>
											</li>
											<li>
												<a class="ui-pane-pin" data-pane="west" title="{{tr['toggle_menu_tip']}}">									
													<span class="glyphicon glyphicon-log-in"></span> {{tr['toggle_menu']}}
												</a>
											</li>
											<li class="divider"></li>
											<li>
												<a href="/logout">{{tr['logout']}}</a>
												<div id="login-popover" class="hide">
													<div class="row clearfix">
														<div class="col-md-12 column">
											            	<a href="/logout" class="btn btn-danger" data-loading-text="Loading...">{{tr['logout']}}</a>	
														</div>
													</div>
												</div>												
											</li>
										</ul>
									</li> 
									{% else %}
									<li class="">
										<a href="/login" class=""><strong>{{tr['login']}}</strong></a>
										
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
									{% endif %}
								</ul>
							</div>							
						</nav>
					</div>
				</div>							
			</div>
		</div>
	
		<div class="ui-layout-west whiteBg ui-scrollable">
			<div class="ui-layout-content">
				<div class="row">
					<div class="col-md-12">
						<form class="form-search padding">
							<input type="text" class="input-medium search-query"
								placeholder="{{tr['input_search']}}" />
							<button type="submit" class="btn">
								<em class="icon-search"></em>&nbsp;Search
							</button>
						</form>
	
						<div id="ui-menu" class="" data-module="{{sModule}}" data-controller="menu" data-action="index"></div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="ui-layout-center ui-scrollable">
			<div class="ui-layout-content transparentBg ui-loadscroll">		
				<div class="container">				
					{% block main %}{% endblock %}
				</div>
			</div>
		</div>
	
		<div class="ui-layout-east ui-scrollable">
			<div class="ui-layout-content transparentBlackBg">
                <div class="row">
	                <div class="col-md-12 column">
	                   <div class="btn-group-vertical">
                            
                        </div>
	                </div>
                </div>
			</div>
		</div>
	
		<div class="ui-layout-south ui-shadow">
			<div class="ui-layout-content">
				<div id="activityDebug">{% include sDeBugHelper %}</div>
			</div>
		</div>
	
		{% block modal %}{% endblock %}
		
		<script	type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script	type="text/javascript" src="/lib/plugins/layout/js/jquery.layout.min.js"></script>
		<script type="text/javascript" src="/lib/plugins/bootstrap3/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/lib/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
		<script type="text/javascript" src="/lib/plugins/pnotify/js/jquery.pnotify.js"></script>
		<script type="text/javascript" src="/lib/plugins/core/js/ui.core.js"></script>
		<script type="text/javascript" src="/lib/plugins/core/js/core.js"></script>
		{% block js %}{% endblock %}
	
	</body>
</html>