<div class="blackTextShadow">
    <ol class="breadcrumb transparentBlackBg">
      <li><a href="/{{sBundle}}">{{sBundle}}</a></li>
      <li><a href="/{{sBundle}}/{{sControllerName}}">{{sControllerName}}</a></li>
      <li>{{sActionName}}{% if bIsXhr %} <span class="label label-info">Ajax</span>{% endif %}</li>
    </ol>
    <p><span class="glyphicon glyphicon-time"></span> Rendered under {{rendered_time}} seconds.</p>

{% if sEnv !== 'prod' %}
    <p>
        <button type="button" class="btn btn-info ui-toggle blackTextShadow" data-toggle-selector=".calledClass-{{sBundle}}-{{sController}}-{{sAction}}-{{current_timestamp}}">
          <span class="glyphicon glyphicon-cog"></span> Called class <span class="label label-default">{{aLoadedClass|Length}}</span>
        </button>
    </p>
    <ul class="calledClass-{{sBundle}}-{{sController}}-{{sAction}}-{{current_timestamp}} hide list-unstyled blackTextShadow">
        <li>
            <h4><span class="glyphicon glyphicon-info-sign"></span> {{aLoadedClass|Length}} components called</h4>
        </li>
    {% for sClass, iLoadTime in aLoadedClass %}
    <li>
        <p>
            {{sClass}} loaded in {{iLoadTime}} seconds <span class="glyphicon glyphicon-time"></span>
        </p>
    </li>
    {% endfor %}
    </ul>
{% endif %}

</div>