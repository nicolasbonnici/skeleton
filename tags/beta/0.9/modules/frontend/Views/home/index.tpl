{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}

{% endblock %}

{% block main %}
                <div class="row">
                        <div class="col-md-12">
                                <div class="hero-unit">
                                        <h1>
                                                Hello, world!
                                        </h1>
                                        <p>{{ tr['hello'] }} template engine! (rendered by haanga)</p>
                                        <p>&nbsp;</p>
                                        <p>Todo</p>
                                        <p>Terminer le router pour mapper correctement les params des url rewrited</p>
                                        <p>Construire un <a href="/backend/todo/">todo</a> (-.-)</p>
                                        <p>Taggé une première version de dev</p>

                                        <p>
                                                <a class="btn btn-primary btn-large" href="#">Learn more »</a>
                                        </p>
                                </div>                    
                        </div>
                </div>
				<div class="row">
					<div id="ui-grid" class="ui-loadable ui-loadscroll ui-grid" data-module="frontend" data-controller="home" data-action="list">
			
					</div>    				
				</div>



{% endblock %}