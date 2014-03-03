    {% if bUpdateEntity|Exists %}
        {% if bUpdateEntity %}
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4>Succès!</h4>
          <p>Votre vous pouvez éditer dès à présent votre nouvel enregistrement.</p>
        </div>
        {% else %}
        <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <h4>Une erreur est survenue...</h4>
        </div>
        {% endif %}         
    {% endif %}
