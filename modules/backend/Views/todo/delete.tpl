{% if bTodoDelete|Exists %}
	{% if bTodoDelete %}
        <div class="alert alert-success">
          <strong>Suppression réussie!</strong> Votre todo à correctement été supprimé
        </div>  
	{% else %}
	    <div class="alert alert-error">
	      <strong>Une erreur est survenue!</strong> Impossible de supprimer le todo
	    </div>  
	{% endif %}
{% endif %}
