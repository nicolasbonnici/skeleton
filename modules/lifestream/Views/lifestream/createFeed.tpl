{% if create|Exists %}
    {% if create %}
        <div class="alert alert-success"><p>{{tr['create_success']}}</p></div>
    {% else %}
        <div class="alert alert-error"><p>{{tr['create_error']}}</p></div>
    {% endif %}
{% endif %}


<div class="row-fluid">	
	<div class="span12 well">
		<h3>
			<img src="" alt="Feed icon" />&nbsp;
			<a href="#" class="ui-editable" data-url="/backend/feed/create/" data-type="text" data-name="title" title="{{tr['fill']}}">{{tr['empty']}}</a>
			&nbsp;(<a href="#" class="ui-editable" data-url="/backend/feed/create/" data-type="text" data-name="domain" title="{{tr['update']}}">{{tr['empty']}}</a>)
		</h3>
		<a href="#" class="ui-editable" data-url="/backend/feed/create/" data-type="text" data-name="url" title="{{tr['update']}}">{{tr['empty']}}</a></p>
	</div>	
</div>		
		
