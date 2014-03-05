{% extends appLayout %}

{% block meta_title %}nbonnici.info:~${% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/knob/js/jquery.knob.js"></script>
<<script type="text/javascript">
<!--
$(document).ready(function() {
    // init circular progressbars
    $('.ui-circular-progress').knob();	
});
//-->
</script>
{% endblock %}

{% block main %}
                <div class="row transparentBlackBg well ui-shadow rounded">
                    <div class="col-md-6 column">
                        <h1>
                           {{ tr['welcome'] }}! <small></small>
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
                    
                    <div class="col-md-6 column ui-noSelect">
                        <div class="carousel slide" id="portfolio-carousel">
                            <ol class="carousel-indicators">
                                <li data-slide-to="0" data-target="#portfolio-carousel" class="active">
                                </li>
                                <li data-slide-to="1" data-target="#portfolio-carousel">
                                </li>
                                <li data-slide-to="2" data-target="#portfolio-carousel">
                                </li>
                                <li data-slide-to="3" data-target="#portfolio-carousel">
                                </li>
                                <li data-slide-to="4" data-target="#portfolio-carousel">
                                </li>
                                <li data-slide-to="5" data-target="#portfolio-carousel">
                                </li>
                                <li data-slide-to="6" data-target="#portfolio-carousel">
                                </li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="/lib/img/portfolio/abl.jpg" class="ui-noSelect" alt="Portfolio image">                        
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            First Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/lib/img/portfolio/lm5.jpg" class="ui-noSelect" alt="Portfolio image">
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            Second Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/lib/img/portfolio/aab.jpg" class="ui-noSelect" alt="Portfolio image">
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            Second Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/lib/img/portfolio/nextcom.jpg" class="ui-noSelect" alt="Portfolio image">
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            Second Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/lib/img/portfolio/visudom.jpg" class="ui-noSelect" alt="Portfolio image">
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            Second Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/lib/img/portfolio/lekrimo-nad.png" class="ui-noSelect" alt="Portfolio image">
                                    <div class="carousel-caption transparentBlackBg ui-shadow">
                                        <h4>
                                            Third Thumbnail label
                                        </h4>
                                        <p>
                                            Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.
                                        </p>
                                    </div>
                                </div>
                            </div> <a class="left carousel-control" href="#portfolio-carousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a> <a class="right carousel-control" href="#portfolio-carousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>                    
                </div>
                
                <div class="row">
                        <div class="col-md-12 whiteBg ui-shadow rounded">
                                <div class="row">

                                	<div class="col-md-9">
						                <h1 class="showOnHover">
						                    Portfolio <small class="targetToShow">un peu de bla bla</small>
						                </h1>
                                	    <p></p>
									</div>
									
                                	<div class="col-md-3">
                                		<h1>Languages</h1>
                                		<h3>PHP5</h3>
										<input type="text" class="ui-circular-progress" value="90" data-width="120" data-fgColor="#669" data-readOnly="true">
                                		<h3>Java</h3>
                                        <input type="text" class="ui-circular-progress" value="50" data-width="120" data-fgColor="#669" data-readOnly="true">
                                		<h3>Python</h3>
                                        <input type="text" class="ui-circular-progress" value="60" data-width="120" data-fgColor="#669" data-readOnly="true">
										<h3>JavaScript</h3>
										<input type="text" class="ui-circular-progress" value="70" data-width="120" data-fgColor="#1eff00" data-readOnly="true">
										<h1>SGBD</h1>
										<h3>MySQL</h3>
										<input type="text" class="ui-circular-progress" value="80" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h3>Oracle SQL</h3>
										<input type="text" class="ui-circular-progress" value="60" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h3>NoSQL (MongoDb)</h3>
										<input type="text" class="ui-circular-progress" value="70" data-width="120" data-fgColor="#e97b00" data-readOnly="true">
										<h1>Misc</h1>
										<h3>CSS2/3</h3>
										<input type="text" class="ui-circular-progress" value="85" data-width="120" data-fgColor="#48acde" data-readOnly="true">
										<h3>HTML5</h3>
										<input type="text" class="ui-circular-progress" value="100" data-width="120" data-fgColor="#48acde" data-readOnly="true">
                                	</div>
                                </div>                    
                        </div>
                </div>

{% endblock %}