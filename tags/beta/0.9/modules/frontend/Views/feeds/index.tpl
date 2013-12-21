{% extends appLayout %}

{% block meta_title %}Feeds{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}

{% endblock %}

{% block js %}

{% endblock %}

{% block main %}
			{% if oNewTwitterActivities|Exists && oNewTwitterActivities.oAddedFeedActivities|Exists && oTwitterFeed|Exists %}
				<div class="row-fluid">
					<div class="span12 alert alert-success alert-block">
					  <button type="button" class="close" data-dismiss="alert">×</button>
					  <h4><img src="{{oTwitterFeed.icon}}" alt="Feed icon" />{{oNewTwitterActivities.oAddedFeedActivities.count}}&nbsp;Nouvelle(s) activité(s) sur {{oTwitterFeed.domain}} enregistré(s).</h4>
					  <p>Sur {{oTwitterFeed.domain}} pour le feed {{oTwitterFeed.title}}.</p>
					  {% if oNewTwitterActivities.oAddedFeedActivities|Exists %}
					  <ul>
					  	{% for oAddedAcitivities in oNewTwitterActivities.oAddedFeedActivities  %}
					  	<li>{{oAddedAcitivities.title}}</li>
					  	{% endfor %}					  
					  <ul>
					  {% endif %}
					  
					  </ul>
					</div>				
				</div>
			{% endif %}				
			{% if oNewTwitterActivities|Exists %}	
				<div id="ui-grid" class="row-fluid" data-module="frontend" data-controller="home" data-action="list" data-columns>
				
					{% for oItem in oNewTwitterActivities %}
						<div class="item well ui-shadow">
							<h4>
								<a href="{{oItem.permalink}}">{{oItem.title|safe}}</a>
							</h4>
							{% if oItem.content|Exists %}
							<div>

							</div>
							{% endif %}
							<span class="timestamp2Date" data-timestamp="{{oItem.created}}"></span>
						</div>	
												
					{% endfor %}
						
				</div>    			
			{% endif %}				


{% endblock %}