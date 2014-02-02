{% if oTodos|Exists %}
	{% for oTodo in oTodos %}
	        <tr{% if oTodo.deadline > current_timestamp %} class="danger blackFontColor"{% endif %}>
	            <td>
	                {{oTodo.idtodo}}
	            </td>
                <td>
                    <a href="#" class="ui-editable" data-entity="Todo" data-pk="{{oTodo.idtodo}}" data-module="backend" data-controller="crud" data-action="update">
                       {{oTodo.label|safe}}
                    </a>
                </td>
	            <td>
					<div class="btn-group">
					  <a href="#" class="btn btn-info" title="{{tr['view']}}"><span class="glyphicon glyphicon-zoom-in"></span></a>
					  <a href="#" class="btn btn-warning" title="{{tr['edit']}}"><span class="glyphicon glyphicon-pencil"></span></a>
					  <a href="#" class="btn btn-danger ui-confirm" title="{{tr['delete']}}"><span class="glyphicon glyphicon-trash"></span></a>
					</div>
	            </td>
	            <td>
                    <a href="#" class="ui-editable ui-editable-date" data-type="date" data-entity="Todo" data-pk="{{oTodo.idtodo}}" data-module="backend" data-controller="crud" data-action="update">	            
	                   <span class="ui-timestamp" data-timestamp="{{oTodo.deadline}} "></span>
                    </a>
	            </td>
	            <td>
	                {{tr['last_edited']}}&nbsp;{{oTodo.lastupdate}} 
	            </td>
	        </tr>
	{% endfor %}
{% else %}
<tr>
    <td>
        Aucun enregistrement
    </td>
</tr> 	
{% endif %}
