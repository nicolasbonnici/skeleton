{% if iStatus|Exists %}
    {% if iStatus == 1 %}
		{{tr['update_success']}}
    {% endif %}
    {% if iStatus == 2 %}
		{{tr['update_error']}}
    {% endif %}
    {% if iStatus == 3 %}
		{{tr['update_error_access_denied']}}
    {% endif %}
    {% if iStatus == 4 %}
		{{tr['update_error_session_expired']}}
    {% endif %}
{% endif %}