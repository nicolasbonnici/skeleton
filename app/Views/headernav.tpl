                <div class="row clearfix">
                    <div class="container-fluid">
                        <nav class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li{% if sAction|Exists && sAction == 'indexAction' %} class="active"{% endif %}>
                                        <a href="/" class="ui-tip" title="{{tr['homepage_tip']}}" data-toggle="tooltip" data-placement="bottom" title="Accueil"><span class="glyphicon glyphicon-home"></span> {{tr['homepage']}}</a>
                                    </li>
                                    <li{% if sAction|Exists && sAction == 'portfolioAction' %} class="active"{% endif %}>
                                        <a href="/portfolio" class="ui-tip" data-toggle="tooltip" data-placement="bottom" title="{{tr['portfolio_tip']}}"><span class="glyphicon glyphicon-folder-open"></span> {{tr['portfolio']}}</a>
                                    </li>
                                    <li{% if sAction|Exists && sAction == 'contactAction' %} class="active"{% endif %}>
                                        <a href="/contact" class="ui-tip" data-toggle="tooltip" data-placement="bottom" title="{{tr['contact_tip']}}"><span class="glyphicon glyphicon-envelope"></span> {{tr['contact']}}</a>
                                    </li>
                                    <li>
                                        <a href="#" class="ui-toggle-popover ui-tip" data-popover="#searchPopover" title="{{tr['search_tip']}}">
                                            <span class="glyphicon glyphicon-search"></span> {{tr['search']}}
                                        </a>                                    

                                        <div id="searchPopover" class="hide">
                                            <div class="row clearfix">
                                                <div class="col-md-12 column well">
                                                    <form class="" role="search">
                                                        <div class="form-group">
                                                           <input type="text" class="form-control input-lg" placeholder="{{tr['search_helper']}}" />
                                                        </div> 
                                                        <div class="form-group text-right">
                                                           <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
                                                        </div>                                                 
                                                    </form>
                                                </div>
                                                <div class="col-md-12 column">
                                                    <p>Résultats</p>
                                                </div>
                                            </div>
                                        </div>
                                                                                
                                    </li>                                    
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    {% if aSession|Exists %}
                                    <li>
                                        <a href="#" data-popover="#user-menu" class="ui-toggle-popover ui-tip" title="Menu utilisateur">
                                            <img src="{{sGravatarSrc16}}" class="ui-nav-avatar" alt="Avatar" /> {{aSession['firstname']}} {{aSession['lastname']}} <strong class="caret"></strong>
                                        </a>
                                        <div id="user-menu" class="hide">
                                            <div id="user-menu-content" class="row clearfix">
                                                <div class="col-md-12 column">
                                                    <ul class="nav nav-pills nav-stacked">
                                                        <li>
                                                            <a href="#modal-user" data-toggle="modal" class="btn btn-lg btn-link ui-sendxhr" data-url="/user/home/profile" data-selector="#modal-user-content"><span class="glyphicon glyphicon-user"></span> {{tr['my_account']}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="/setup" class="btn btn-lg btn-link"><span class="glyphicon glyphicon-cog"></span> {{tr['administration']}}</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="btn btn-lg btn-link ui-pane-toggle ui-tip" data-pane="east" title="Mes applications">
                                                                <span class="glyphicon glyphicon-cloud-download"></span> Apps
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="btn btn-lg btn-link ui-pane-toggle" data-pane="west" title="{{tr['toggle_menu_tip']}}">                                  
                                                                <span class="glyphicon glyphicon-log-in"></span> {{tr['toggle_menu']}}
                                                            </a>
                                                        </li>
                                                        {% if sEnv === 'dev' %}
                                                        <li>
                                                            <a class="btn btn-lg btn-link ui-pane-toggle" data-pane="south" title="{{tr['toggle_menu_tip']}}">                                  
                                                                <span class="glyphicon glyphicon-stats"></span> Debug
                                                            </a>
                                                        </li>
                                                        {% endif %}
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="/logout" class="btn btn-lg btn-link" title="Se déconnecter">{{tr['logout']}}</a>
                                                            <div id="login-popover" class="hide">
                                                                <div class="row clearfix">
                                                                    <div class="col-md-12 column">
                                                                        <a href="/logout" class="btn btn-danger" data-loading-text="Loading...">{{tr['logout']}}</a>    
                                                                    </div>
                                                                </div>
                                                            </div>                                              
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li> 
                                    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                    {% else %}
                                    <li class="">
                                        <a href="/login" class=""><strong>{{tr['login']}}</strong></a>
                                        
                                        <div id="login-popover" class="hide">
                                            <div class="row clearfix">
                                                <div class="col-md-12 column">
                                                    <form class="form-horizontal well" role="form" method="POST" action="/auth">
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
                                    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                    {% endif %}
                                </ul>
                            </div>                            
                        </nav>
                    </div>
                </div>   