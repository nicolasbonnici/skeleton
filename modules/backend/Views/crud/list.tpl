{% if oPosts|Exists %}
	{% for oPost in oPosts %}
	        <tr{% if oPost.deadline > current_timestamp %} class="danger blackFontColor"{% endif %}>
	            <td>
	                <input type="checkbox" class="ui-select" name="idpost" value="{{oPost.idpost}}" />
	            </td>
                <td>
                    {{oPost.title|safe}}
                </td>
	            <td>
	               <span class="ui-timestamp" data-timestamp="{{oPost.deadline}}"></span>
	            </td>
	            <td>
					<div class="btn-group">
					  <a href="#modal-todo" class="ui-sendxhr btn btn-info" data-url="/backend/todo/read/idpost/{{oPost.idpost}}" data-selector="#modal-todo-content" data-toggle="modal" data-idpost="{{oPost.idpost}}" title="{{tr['view']}}"><span class="glyphicon glyphicon-zoom-in"></span></a>
					  <a href="#modal-todo" class="ui-sendxhr btn btn-warning" data-url="/backend/todo/update/idpost/{{oPost.idpost}}" data-selector="#modal-todo-content" data-toggle="modal" data-idpost="{{oPost.idpost}}" title="{{tr['edit']}}"><span class="glyphicon glyphicon-pencil"></span></a>
					  <a href="#modal-todo" class="ui-sendxhr btn btn-danger" data-url="/backend/todo/delete/idpost/{{oPost.idpost}}" data-selector="#modal-todo-content" data-toggle="modal" data-idpost="{{oPost.idpost}}" title="{{tr['delete']}}"><span class="glyphicon glyphicon-trash"></span></a>
					</div>
	            </td>
	            <td>
	                {{tr['last_edited']}}&nbsp;<span class="ui-timestamp" data-timestamp="{{oPost.lastupdate}}"></span> 
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
