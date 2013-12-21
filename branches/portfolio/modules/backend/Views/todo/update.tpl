{% if update|Exists %}
    {% if update %}
		<div class="alert alert-success"">
			 <button type="button" class="close" data-dismiss="alert">×</button>
			<h4>{{tr['update_success']}}</h4>
		</div>    
    {% else %}
		<div class="alert alert-error"">
			 <button type="button" class="close" data-dismiss="alert">×</button>
			<h4>{{tr['update_error']}}</h4>
		</div>    
    {% endif %}
{% endif %}
{% if oTodo.idtodo|Exists %}

	<form id="update-todo{{oTodo.idtodo}}" action="/backend/todo/update/id/{{oTodo.idtodo}}" method="post">
		<input type="text" name="label" value="{{oTodo.label}}" />
		
		<div class="ui-editor" data-name="content" id="ui-editor-{{oTodo.idtodo}}">
			{{oTodo.content|safe}}
		</div>                                
		          
		<div class="text-right">
			<input type="submit" value="{{tr['update']}}" data-form="#update-todo{{oTodo.idtodo}}" class="ui-sendform btn btn-large btn-primary" />
			<a class="btn btn-large">{{tr['cancel']}}</a>
		</div>		          
		                                
	</form>
	
{% else %}	
		<div class="alert alert-error"">
			 <button type="button" class="close" data-dismiss="alert">×</button>
			<h4>{{tr['access_denied']}}</h4>
		</div>    
{% endif %}