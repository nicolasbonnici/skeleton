
  <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h4 class="modal-title" id="myModalLabel">
          Nouveau Todo!
      </h4>
  </div>
  
  <div id="modal-create-content" class="modal-body">
	<form role="form" id="newTodoForm" action="/backend/crud/create/" data-entity="Todo" data-view="todo/create.tpl" method="post">
	    <div class="form-group">
	        <label>Titre du Todo</label>
	        <a href="#" class="ui-editable" data-entity="Todo" data-name="label" data-module="backend" data-controller="crud" data-action="update">
	        </a>
            <input type="text" name="label" class="form-control input-lg" placeholder="Entrez le titre de votre post" value="{% if title|Exists %}{{title}}{% endif %}" />
	    </div>   
	    <div class="form-group">
	        <label>Deadline</label>
            <div class="input-append date" id="dpYears" data-date="" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                <span class="icon-calendar glyphicon glyphicon-calendar"></span>
                <input name="date" class="ui-datepicker span2" size="16" type="text" value="">
                <span class="icon-time glyphicon glyphicon-time"></span>
                <input name="time" class="ui-datepicker span2" size="16" type="text" value="">
            </div>
	    </div>
	    <div class="form-group">
	        <label>Contenu</label>
	        <div class="ui-editor" data-name="content">
	        {% if content|Exists %}{{content}}{% endif %}
	        </div>
	        <p class="help-block">
	            Vous pouvez mettre en form votre todo à l'aide de la barre d'outils de mise en forme
	        </p>
	    </div>
	</form> 		    
  
  </div> 

  <div class="modal-footer">
       <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">{{tr['cancel']}}</button>
       &nbsp;<button type="button" class="btn btn-lg btn-primary ui-sendform refreshOnCallback sendNotificationOnCallback" data-view="todo/create.tpl" data-form="#newTodoForm" title="Enregistrer ce todo">{{tr['save']}}</button>
  </div>    	
