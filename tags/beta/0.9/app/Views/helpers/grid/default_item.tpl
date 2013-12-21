{% if oItems|Exists %}
	<div id="ui-grid" class="row-fluid">	    
	{% for oItem in oItems %}
	
		<div class="span3 item well">
			<p>
				<a href="{{oItem.permalink}}">{{oItem.title}}</a>
			</p>
			<span>{{oItem.created}}</span>
		</div>	
		
	{% endfor %}	
	</div>
{% endif %}