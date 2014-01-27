{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}{% endblock %}

{% block js %}
<script>
$(document).ready(function() {
	$('.filterFeedItems').on('click', function() {
		var iFeedId = $(this).data('ifeedid');
		if (parseInt(iFeedId) > 0) {
			$($(this).data('sreloadtarget')).attr('data-ifeedid', iFeedId);
		}
		return false;
	});
});
</script>
{% endblock %}

{% block main %}

			<div class="row">
			{% if oFeeds|Exists %}
				<div class="col-md-12 btn-group transparentBg padding margin">
				{% for oFeed in oFeeds %}
					<a href="#" data-sfeedid="{{oFeed.idfeed}}" class="ui-reload filterFeedItems btn btn-default btn-lg" data-sreloadtarget="#lifestream" data-title="Uniquement afficher l'activité du feed {{oFeed.title}}" data-istep="64">
						<img src="{{oFeed.icon}}" class="icon-feed" alt="Feed icon" /> {{oFeed.domain}}
					</a>
				{% endfor %}
				</div>
			{% endif %}
			</div>

			<div class="row">
				<div id="lifestream" class="ui-loadable ui-scroll-loadable ui-grid" data-module="frontend" data-controller="home" data-action="list">
		
				</div>    				
			</div>

{% endblock %}