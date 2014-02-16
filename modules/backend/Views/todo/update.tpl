{% if oTodo|Exists %}
    <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">
	        <a href="#" class="ui-editable" data-entity="Todo" data-name="label" data-pk="{{oTodo.idtodo}}" data-url="/backend/crud/update/">
	           {{oTodo.label|safe}}
	        </a>          
        </h4>
    </div>
    <div class="modal-body">
        <form role="form" id="newTodoForm" action="" method="post">
            <div class="form-group">
                <div class="ui-editor">
                {{oTodo.content}}
                </div>
                <p class="help-block">
                    Vous pouvez mettre en form votre todo à l'aide de la barre d'outils de mise en forme
                </p>
            </div>
        </form>                         
    </div>
    <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">{{tr['cancel']}}</button>
         &nbsp;<button type="button" class="btn btn-primary">{{tr['save']}}</button>
    </div>	    

{% else %}
    <div class="alert alert-warning">
      <strong>Warning!</strong> Your role doesn't allow you to edit this todo
    </div>  
{% endif %}
