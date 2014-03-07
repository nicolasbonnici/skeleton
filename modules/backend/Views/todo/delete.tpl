{% if bDeleteEntity|Exists %}
	{% if bDeleteEntity %}
        <div class="alert alert-success">
          <h4>Suppression réussie!</h4>
          <p>Votre todo à correctement été supprimé</p>
        </div>
	{% else %}
	    <div class="alert alert-error">
	      <h4>Une erreur est survenue!</h4>
	      <p>Impossible de supprimer le todo.</p>
	    </div>  
	{% endif %}
{% else %}
    <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">
            &nbsp;
        </h4>
    </div>
    <div class="modal-body">
	    <form id="deleteTodo" class="text-center" method="post" action="/backend/crud/delete/" data-entity="Todo" data-pk="{% if pk|Exists %}{{pk}}{% endif %}">
	       <div class="form-group">
	            <label>Confirmez vous la suppréssion de ce todo?</label>
	            <input type="hidden" name="bconfirm" value="1" />
	       </div>
	    </form>
    </div>
    <div class="modal-footer">
         <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">{{tr['cancel']}}</button>
         <button type="submit" class="ui-sendform refreshOnCallback closeModalOnCallback sendNotificationOnCallback btn btn-lg btn-danger" data-form="#deleteTodo">Confirmer</button>    
    </div>	
{% endif %}
