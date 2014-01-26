{% if oItems|Exists %}
	{% for oItem in oItems %}
	
		{% set sItemType = '' %}
		{% if oItem.feed_idfeed == 1 %}
			{% set sItemType = 'twitter' %}
		{% endif %}
		{% if oItem.feed_idfeed == 2 %}
			{% set sItemType = 'google' %}
		{% endif %}
		{% if oItem.feed_idfeed == 3 %}
			{% set sItemType = 'googleplus' %}
		{% endif %}	
	
		<div class="item {{sItemType}} col-md-12 well ui-shadow">
			<h4>
				<a href="{{oItem.permalink}}">{{oItem.title|safe}}</a>				
			</h4>
			
			{% if aSession|Exists %}
			<span>
				<a href="#" class="ui-editable" data-url="/backend/feedsItem/update/id/{{oItem.status}}" data-type="text" data-pk="{{oItem.idfeedItem}}" data-name="title" title="{{tr['update']}}">{{oItem.status}}</a>
			</span>&nbsp;
			{% endif %}			
			
			<span class="ui-timestamp" data-timestamp="{{oItem.created}}"></span>
		</div>
	{% endfor %}	
{% endif %}		