	{% if bCreateNewPost|Exists %}
	    {% if bCreateNewPost %}
	    <div class="alert alert-success alert-dismissable">
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	      <h4>Todo créer avec succès!</h4>
	      <p>Votre post est correctement enregistré, vous pouvez l'éditer dès à présent.</p>
	      <p><button type="button" class="btn btn-primary" data-dismiss="modal">Fermer cette fenêtre</button></p>
	    </div>
	    {% else %}
	    <div class="alert alert-success alert-dismissable">
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4>Une erreur est survenue...</h4>
          <p>Une erreur empèche l'enregistrement de votre post, veuillez vous assurer que les champs sont correctement remlis.</p>
	    </div>
	    {% endif %}
	{% else %}
        <form role="form" id="newPostForm" action="/backend/post/create/" method="post">
            <div class="form-group">
                <label>Titre</label> 
                {#<a href="#" class="ui-editable" data-entity="Post" data-name="title" data-module="backend" data-controller="crud" data-action="update">
                </a>#}
                <input type="text" name="title" class="form-control input-lg" placeholder="Entrez le titre de votre post" value="{% if title|Exists %}{{title}}{% endif %}" />
            </div>
            <div class="form-group">
                <label>Contenu</label>
                <div class="ui-editor" data-name="content">
                {% if content|Exists %}{{content}}{% endif %}
                </div>
                <p class="help-block">
                    Vous pouvez mettre en form votre post à l'aide de la barre d'outils de mise en forme
                </p>
            </div>
        </form> 		    
    {% endif %}