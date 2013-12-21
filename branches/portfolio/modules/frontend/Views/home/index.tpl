{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}

{% endblock %}

{% block main %}

                <div class="row transparentBlackBg margin ui-shadow rounded">
                        <div class="col-md-12">
                                        <h1>
                                                Hello, world!
                                        </h1>
                                        <p>{{ tr['hello'] }} template engine! (rendered by haanga)</p>

                                        <p>
                                                <a class="btn btn-primary btn-large" href="#">Learn more »</a>
                                        </p>
                        </div>
                </div>
				<br />
				<div class="row">
					<div id="ui-grid" class="ui-loadable ui-scroll-loadable ui-grid" data-module="frontend" data-controller="home" data-action="list">
			
					</div>    				
				</div>



{% endblock %}