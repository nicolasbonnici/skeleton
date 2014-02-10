        {% if bCreateNewTodo|Exists %}
            {% if bCreateNewTodo %}
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>Todo créer avec succès!</strong> Votre todo est correctement enregistré, vous pouvez l'éditer dès à présent.
            </div>
            {% else %}
            <div class="alert alert-success alert-dismissable">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <strong>Une erreur est survenue...</strong> Une erreur empèche l'enregistrement de votre todo, veuillez vous assurer que les champs sont correctement remlis.
            </div>
            {% endif %}
        {% else %}
        <form role="form" id="newTodoForm" action="/backend/todo/create/" method="post">
            <div class="form-group">
	            <label>Titre du Todo</label>
	            {#<a href="#" class="ui-editable" data-entity="Todo" data-name="label" data-module="backend" data-controller="crud" data-action="update">
	            </a>#}
	            <input type="text" name="label" placeholder="Entrez le titre de votre note" value="{% if label|Exists %}{{label}}{% endif %}" /> 
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
        {% endif %}