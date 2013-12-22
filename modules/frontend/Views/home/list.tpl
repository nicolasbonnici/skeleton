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
			{% set sItemType = 'google+' %}
		{% endif %}	
	
		<div class="item {{sItemType}} col-md-12 well ui-shadow">
			{% if oSession|Exists %}
			<span>
				<a href="#" class="ui-editable" data-url="/backend/feedsItem/update/id/{{oItem.status}}" data-type="text" data-pk="{{oItem.idfeedItem}}" data-name="title" title="{{tr['update']}}">{{oItem.status}}</a>
			</span>
			{% endif %}
			<h4>
			{% if sItemType == 'twitter' %}
				<img alt="feed icon" src="http://twitter.com/favicon.ico">
			{% endif %}
			{% if sItemType == 'google' %}
				<img alt="feed icon" src="http://google.com/favicon.ico">
			{% endif %}
			{% if sItemType == 'google+' %}
				<img alt="feed icon" src="http://plus.google.com/favicon.ico">
			{% endif %}
				&nbsp;<a href="{{oItem.permalink}}">{{oItem.title|safe}}</a>
				
			</h4>
			<span class="ui-timestamp" data-timestamp="{{oItem.created}}"></span>
		</div>	
		
	{% endfor %}	
{% endif %}		