{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}

{% endblock %}

{% block main %}

                <div class="row transparentBlackBg margin ui-shadow rounded">

                       <div class="col-md-6">
	                      <h1>
	                         {{ tr['hello'] }}! <small></small>
	                      </h1>
	                      <p>
							Je suis un développeur situé prés de Paris, spécialisé dans les technologies du web. Je peux réaliser votre site internet, votre boutique en ligne, votre application mobile ou encore votre solution informatique sur mesures. 
						  </p>
						  <p>
							Mes créations respectent les standards W3C et sont accessibles depuis n'importe quel environnement même mobile (directement sur votre terminal iPhone, Android, Blackberry...)		                      
	                      </p>
	
	                      <p>
                              <a class="btn btn-lg btn-default btn-large" href="#"><i class="glyphicon glyphicon-zoom-in"></i> En savoir plus</a>
                              <a class="btn btn-lg btn-primary ui-login-popover" href="#"><i class="glyphicon glyphicon-user"></i> {{tr['login']}}</a>
                              <a class="btn btn-lg btn-info" href="#"><i class="glyphicon glyphicon-envelope"></i> {{tr['contact']}}</a>
	                      </p>
                       </div>

                       <div class="col-md-6">
                       
                       </div>

                </div>


{% endblock %}