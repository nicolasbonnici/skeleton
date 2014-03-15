<div class="col-md-12 alert alert-success alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4><em class="glyphicon glyphicon-time"></em>/{{sController}}/{{sAction}}&nbsp;Rendered in {{rendered_time}} seconds</h4>

  <ul>
  {% for sClass in aLoadedClass %}
  	<li><strong>{{sClass}}</strong></li>
  {% endfor %}
  </ul>
</div>
