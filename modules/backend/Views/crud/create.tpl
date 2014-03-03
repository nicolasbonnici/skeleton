	{% if bCreateEntity|Exists %}
	    {% if bCreateEntity %}
	    <div class="alert alert-success alert-dismissable">
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	      <h4>Succès!</h4>
	      <p>Votre vous pouvez éditer dès à présent votre nouvel enregistrement.</p>
	      <p><button type="button" class="btn btn-primary" data-dismiss="modal">Fermer cette fenêtre</button></p>
	    </div>
	    {% else %}
	    <div class="alert alert-success alert-dismissable">
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4>Une erreur est survenue...</h4>
          <p>Une erreur empèche le nouvel enregistrement, veuillez vous assurer que les champs sont correctement remlis.</p>
	    </div>
	    {% endif %}		    
    {% endif %}