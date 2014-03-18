{% block main %}
{% if aSession|Exists %}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="modal-title" id="myModalLabel">
            <span class="glyphicon glyphicon-user"></span> {{aSession['firstname']}} {{aSession['lastname']}}
        </h1>
        
    </div>
    <div class="modal-body">
        {% if passwordUpdate|Exists %}
            <div class="alert alert-{% if passwordUpdate %}success{% else %}error{% endif %}">
                {% if passwordUpdate %}
                    <p>{{tr['password_update_success']}}</p>
                {% else %}
                    <p>{{tr['password_update_error']}}</p>                              
                {% endif %}                         
            </div>
        {% endif %}
         <form class="form-horizontal" role="form" action="" method="POST">
             <div class="form-group">
                  <label for="" class="col-sm-4 control-label">{{tr['avatar']}}</label>
                 <div class="col-sm-8">
                     <img src="{{sGravatarSrc128}}" alt="Avatar" />                                      
                 </div>
             </div>                              
             <div class="form-group">
                 <label for="" class="col-sm-4 control-label">{{tr['username']}}</label>
                 <div class="col-sm-8">
                     <a href="#" class="ui-editable" data-type="text" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="firstname">{{aSession['firstname']}}</a>&nbsp;
                     <a href="#" class="ui-editable" data-type="text" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="lastname">{{aSession['lastname']}}</a>&nbsp;
                 </div>
             </div>
             <div class="form-group">
                  <label for="" class="col-sm-4 control-label">{{tr['email']}}</label>
                 <div class="col-sm-8">
                     <a href="#" class="ui-editable" data-type="email" data-entity="User" data-url="/backend/crud/update/id/{{aSession['iduser']}}" data-pk="{{aSession['iduser']}}" data-name="mail" >{{aSession['mail']}}</a>&nbsp;
                 </div>
             </div>
             <div class="form-group">
                  <label for="" class="col-sm-4 control-label">{{tr['your_password']}}</label>
                 <div class="col-sm-8">
                     <input type="password" class="form-control" placeholder="{{tr['input_password']}}" name="password" class="">
                     <input type="password" class="form-control" placeholder="{{tr['input_new_password']}}" name="passwordNew1" class="">
                     <input type="password" class="form-control" placeholder="{{tr['confirm_new_password']}}" name="passwordNew2" class="">
                 </div>
             </div>
             <div class="form-group">
                 <div class="col-md-12 text-right">
                 </div>
             </div>                                  
         </form>     
    </div>
    <div class="modal-footer">
            <button type="submit" class="btn btn-lg btn-primary">{{tr['update_password']}}</button>
         <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">Fermer</button>
    </div>
{% else %}
    <div class="alert alert-warning">
      <strong>Error!</strong> No valid session found, open a session in order to access the user profile.
    </div>  
{% endif %}
{% endblock %}