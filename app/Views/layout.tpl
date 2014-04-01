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
        <link href="/lib/css/core.classes.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.css" rel="stylesheet">
        <link href="/lib/plugins/pnotify/css/jquery.pnotify.default.icons.css" rel="stylesheet">
        <link href="/lib/css/core.ui.css" rel="stylesheet">
        <link href="/lib/css/style.css" rel="stylesheet">
               
        {% block css %}{% endblock %}            
    </head>
    <body class="layout">
    
        <div class="ui-layout-north ui-shadow">
            <div class="ui-layout-content container noOverflow">
            {% include 'headernav.tpl' %}
            </div>
        </div>
    
        <div class="ui-layout-west ui-shadow ui-scrollable">
            <div class="ui-layout-content transparentBlackBg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                    
                            <div class="col-md-12 text-right">
                                <a href="#" class="btn btn-lg btn-primary ui-pane-toggle" data-pane="west" title="">
                                    <span class="glyphicon glyphicon-arrow-left"></span>
                                </a>
                            </div>
                    
                        </div>
                        <div class="ui-layout-west-xhr"></div>
                    
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
    
        <div class="ui-layout-east ui-shadow ui-scrollable">
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
            <div class="ui-layout-content container-fluid">
                <div id="activityDebug" class="row">{% include sDeBugHelper %}</div>
            </div>
        </div>
    
        <div class="modal fade" id="modal-user" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="modal-user-content">
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
        {% block modal %}{% endblock %}
        
        <script type="text/javascript" src="/lib/js/jquery-1.11.min.js"></script>
        <script type="text/javascript" src="/lib/plugins/layout/js/jquery.layout.min.js"></script>
        <script type="text/javascript" src="/lib/plugins/bootstrap3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/lib/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script type="text/javascript" src="/lib/plugins/pnotify/js/jquery.pnotify.js"></script>
        <script type="text/javascript" src="/lib/js/core/ux.core.js"></script>
        <script type="text/javascript" src="/lib/js/core/core.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
           $('body').on('submit', 'form#general-search', function() {
              $('.ui-sendform[data-form=#general-search]').trigger('click');
              return false;
           });
        });
        </script>
        {% block js %}{% endblock %}
    
    </body>
</html>