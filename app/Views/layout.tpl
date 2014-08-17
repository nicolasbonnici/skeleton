<!DOCTYPE html>
<html lang="{{lang|Substr: '0,2'|lower}}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{sAppName}} | {% block meta_title %}{% endblock %}</title>

        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="{% block favicon %}/lib/img/favicon.gif{% endblock %}">              
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/lib/img/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/lib/img/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/lib/img/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/lib/img/apple-touch-icon-57-precomposed.png">

        <meta name="viewport" content="width=device-width, user-scalable=no">

        <meta name="description" content="{% block meta_description %}{% endblock %}" />
        <meta name="keywords" content="{% block meta_keyword %}{% endblock %}" />
        <meta name="application-name" content="{% block meta_app %}sociableCore{% endblock %}" />
        <meta name="author" content="{% block meta_author %}Nicolas BONNICI{% endblock %}" />        

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="/lib/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Plugins -->
        <link href="/lib/plugins/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="/lib/plugins/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">

        <!-- sociableUx -->
        <link href="/lib/css/style.min.css" rel="stylesheet">

        <!-- Custom styling -->
        <link href="/lib/css/style.css" rel="stylesheet">

        <!-- Bundle style -->
        <link href="/lib/bundles/{{sBundle}}/css/{{sBundle}}.css" rel="stylesheet">
               
        {% block css %}{% endblock %}
    </head>
    <body>
        <nav id="toolbar" class="greyBg gpu-render ui-noSelect ui-transition ui-shadow navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">

                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-menu">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>
                </div>
                <div class="navbar-collapse collapse" id="app-menu">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#" id="open-left" class="ui-tip showOnHover" title="Afficher le menu">
                                <span class="glyphicon glyphicon-th-list"></span>&nbsp;<span class="targetToShow blackTextShadow">Menu</span>
                            </a>
                        </li>
                        <li{% if sAction|Exists && sAction == 'indexAction' && sBundle === 'frontend' %} class="active transparentBlackBg ui-shadow-inset"{% endif %}>
                            <a href="/" class="ui-tip showOnHover" data-toggle="ui-tip" data-placement="bottom" title="Retourner sur l'accueil de {{sAppName}}">
                                <span class="glyphicon glyphicon-home"></span>&nbsp;<span class="targetToShow blackTextShadow">{{tr['homepage']}}</span>
                            </a>
                        </li>
                        
                        <li{% if sAction|Exists && sAction == 'portfolioAction' %} class="active transparentBlackBg"{% endif %}>
                            <a href="/portfolio" class="ui-tip showOnHover" data-toggle="tooltip" data-placement="bottom" title="{{tr['portfolio_tip']}}">
                                <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<span class="targetToShow blackTextShadow">{{tr['portfolio']}}</span>
                            </a>
                        </li>
                        {#
                        <li{% if sAction|Exists && sAction == 'blogAction' %} class="active transparentBlackBg"{% endif %}>
                        
                            <a href="blog" class="ui-tip showOnHover" data-toggle="tooltip" data-placement="bottom" title="{{tr['blog_tip']}}">
                                <span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;<span class="targetToShow blackTextShadow">{{tr['blog']}}</span>
                            </a>
                        </li>
                        #}
                        <li{% if sAction|Exists && sAction == 'activitiesAction' %} class="active transparentBlackBg"{% endif %}>
                            <a href="/activities" class="ui-tip showOnHover" data-toggle="tooltip" data-placement="bottom" title="{{tr['lifestream_tip']}}">
                                <span class="glyphicon glyphicon-link"></span>&nbsp;<span class="targetToShow blackTextShadow">{{tr['lifestream']}}</span>
                            </a>
                        </li>
            
                        <li>
                            <a href="#" data-toggle-selector="#app-search" title="Rechercher un contenu" class="ui-toggle ui-tip ui-toggle ui-focus showOnHover" data-focus-selector="#ux-global-search-input">
                                <span class="glyphicon glyphicon-search"></span>&nbsp;<span class="targetToShow blackTextShadow">{{tr['search']}}</span>
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" id="open-right" class="ui-tip showOnHover" title="{{tr['bundles_tip']}}">
                                <span class="glyphicon glyphicon-cloud-download"></span> <span class="targetToShow blackTextShadow">{{tr['bundles']}}</span>
                            </a>
                        </li>
                        {% if aSession|Exists %}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" class="blackTextShadow ui-tip" title="Menu utilisateur">
                                <img src="{{sGravatarSrc16}}" class="ui-nav-avatar" alt="Avatar" /> {{aSession['firstname']}} {{aSession['lastname']}} <strong class="caret"></strong>
                            </a>
                            <ul class="dropdown-menu transparentBlackBg rounded-bottom ui-shadow text-left padding">
                                <li>
                                    <a href="#modal" data-toggle="modal" class="btn btn-lg btn-link ui-sendxhr" data-url="/user/home/profile" data-selector="#modal-content"><span class="glyphicon glyphicon-user"></span> {{tr['my_account']}}</a>
                                </li>
                                <li>
                                    <a href="/admin" class="btn btn-lg btn-link"><span class="glyphicon glyphicon-cog"></span> {{tr['administration']}}</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="/logout" class="btn btn-lg btn-link" title="Se déconnecter">{{tr['logout']}}</a>
                                </li>
                            </ul>
                        </li>
                        {% else %}
                        <li>
                            <a href="/login" class="showOnHover">
                                <span class="glyphicon glyphicon-log-in"></span> <span class="targetToShow blackTextShadow"><strong>{{tr['login']}}</strong></span>
                            </a>
                        </li>
                        {% endif %}
                </ul>
            </div>
          </div>
        </nav>

        <div class="snap-drawers greyPatternBg">
            <div id="app-stack-left" class="snap-drawer snap-drawer-left noOverflowX">
                <div id="app-header" class="showOnHover">
                    <img src="{{sAppIcon}}" class="app-logo pull-left" alt="{{sBundle}} bundle icon"/>
                    <h3 class="blackTextShadow">{{sBundle}} <small class="targetToShow">1.0</small></h3>
                </div>
                
                <div id="app-search-content" class="">
                    <div class="padding">
                        <form id="app-global-search" data-url="/search/home/process" class="form-horizontal" role="search" method="post" action="/search/home/process/" data-sendform-reponse-selector="#app-search-results">
                            <input id="ux-global-search-input" type="text" name="search" class="rounded ui-shadow" placeholder="{{tr['search_helper']}}" />
                            <input type="hidden" name="entities" value="" />
                        </form>
                    </div>
                </div>
                <a href="#" class="btn btn-lg btn-info ui-sendform" data-form="#app-global-search"><span class="glyphicon glyphicon-search"></span> {{tr['search']}}</a>
                
                <div id="app-search-results" class="row clearfix"> </div>
                <ul class="nav nav-pills nav-stacked blackTextShadow">
                {% if aSession|Exists %}
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Profile</a></li>
                  <li><a href="#">Messages</a></li>
                 {% endif %}
                  <li><a href="#activityDebug" class="ui-toggle" data-toggle-selector=".app-menu">Debug</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="app-menu hide" id="home"></div>
                  <div class="app-menu hide" id="profile"></div>
                  <div class="app-menu hide" id="messages"></div>
                  <div class="app-menu hide" id="activityDebug">
                    {% include sDeBugHelper %}
                  </div>
                </div>
            </div>
            <div class="snap-drawer snap-drawer-right">
                {% if aSession|Exists && aAppBundles|Exists %}
                    <div class="list-group app-bundles">
                    {% for sBundleName, aBundleDetails in aAppBundles %}
                        <a href="/{{sBundleName}}" class="list-group-item app-bundle-item blackTextShadow text-right{% if sBundle === sBundleName %} active{% endif %}">
                            {{sBundleName}} <img src="/lib/bundles/{{sBundleName}}/img/icon.png" class="app-bundle-icon" alt="Bundle icon" />
                        </a>
                    {% endfor %}
                    </div>
                {% else %}
                    <div class="list-group app-bundles">
                    {% for sBundleName, aBundleDetails in aAppBundles %}
                        {% if (sBundleName === 'auth') OR (sBundleName === 'search') OR (sBundleName === 'admin') %} 
                        <a href="/{{sBundleName}}" class="list-group-item app-bundle-item blackTextShadow text-right{% if sBundle === sBundleName %} active{% endif %}">
                            {{sBundleName}} <img src="/lib/bundles/{{sBundleName}}/img/icon.png" class="app-bundle-icon" alt="Bundle icon" />
                        </a>
                        {% endif %}
                    {% endfor %}
                    </div>
                {% endif %}
            </div>
        </div>
        
        <div id="ux-content" class="snap-content ui-loadscroll noOverflowX ui-shadow">
                {% block main %}{% endblock %}
        </div>
    
        <div class="modal modal-md fade" id="modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="modal-content">
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
        {% block modal %}{% endblock %}
        
        <!--  sociableUX -->
        <script type="text/javascript" src="/lib/plugins/jquery/js/jquery-1.11.min.js"></script>
        <script type="text/javascript" src="/lib/plugins/bootstrap3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/lib/js/script.min.js"></script>

        <script type="text/javascript" src="/lib/bundles/{{sBundle}}/js/{{sBundle}}.js"></script>
        {% block js %}{% endblock %}
        
        <!--  Google Analytics @todo passer en conf et dans le build js -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        
          ga('create', 'UA-49761357-1', 'nbonnici.info');
          ga('send', 'pageview');
        
        </script>
    
    </body>
</html>