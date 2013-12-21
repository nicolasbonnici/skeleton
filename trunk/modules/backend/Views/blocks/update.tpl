{% if update|Exists %}
    {% if update %}
        <div class="alert alert-success"><p>{{tr['update_success']}}</p></div>
    {% else %}
        <div class="alert alert-error"><p>{{tr['update_error']}}</p></div>
    {% endif %}
{% endif %}