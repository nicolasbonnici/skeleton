{% if update|Exists %}
    {% if update %}
		{{tr['update_success']}}
    {% else %}
        {{tr['update_error']}}
    {% endif %}
{% endif %}