{% if oItems|Exists %}
	{% for oItem in oItems %}
	
		<div class="item col-md-12 well ui-shadow">
			{% if oSession|Exists %}
			<span>
				<a href="#" class="ui-editable" data-url="/backend/feedsItem/update/id/{{oItem.status}}" data-type="text" data-pk="{{oItem.idfeedItem}}" data-name="title" title="{{tr['update']}}">{{oItem.status}}</a>
			</span>
			{% endif %}
			<h4>
			{% if oItem.feeds_idfeed == 1 %}
				<img alt="feed icon" src="http://twitter.com/favicon.ico">
			{% endif %}
			{% if oItem.feeds_idfeed == 2 %}
				<img alt="feed icon" src="http://google.com/favicon.ico">
			{% endif %}
			{% if oItem.feeds_idfeed == 3 %}
				<img alt="feed icon" src="http://plus.google.com/favicon.ico">
			{% endif %}
				&nbsp;<a href="{{oItem.permalink}}">{{oItem.title|safe}}</a>
				
			</h4>
			{% if oItem.content|Exists %}
			<div>

			</div>
			{% endif %}
			<span class="timestamp2Date" data-timestamp="{{oItem.created}}"></span>
		</div>	
		
	{% endfor %}	
{% endif %}		