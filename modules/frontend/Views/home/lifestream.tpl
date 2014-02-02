{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}{% endblock %}

{% block js %}
<script>
$(document).ready(function() {
	var core = $.fn.core();
	$('.filterFeedItems').on('click', function() {
		var iFeedId = $(this).data('ifeedid');
		if (parseInt(iFeedId) > 0) {
			var iFeedsCount = $('.filterFeedItems.btn-info').size();
			if (iFeedsCount > 0) {}
				var sFeedId = ''; 
				$('.filterFeedItems.btn-info').each(function(index) {
					sFeedId += new String($(this).data('ifeedid') + ((index !== iFeedsCount) ? ' ,' : ''));
					console.log($(this).data('ifeedid'));
				});
			}
			
			$(this).toggleClass('btn-info');
			$(this).toggleClass('btn-default');
			$($(this).data('sreloadtarget')).data('sfeedid', sFeedId);
			core.reload( $( $(this).data('sreloadtarget') ) );	
		return false;
	});
});
</script>
{% endblock %}

{% block main %}

			<div class="row">
					<div class="col-md-12 column transparentBlackBg rounded well text-right">
					    <div class="btn-group">
	        			{% if oFeeds|Exists %}
						{% for oFeed in oFeeds %}
							<a href="#" data-ifeedid="{{oFeed.idfeed}}" class="filterFeedItems btn btn-default btn-lg" data-sreloadtarget="#lifestream" data-title="Uniquement afficher l'activité du feed {{oFeed.title}}">
								<img src="{{oFeed.icon}}" class="icon-feed" alt="Feed icon" />
							</a>
						{% endfor %}
					    {% endif %}
					    </div>
					</div>
	
					<div id="lifestream" class="col-md-12 column ui-loadable ui-scroll-loadable ui-grid" data-module="frontend" data-controller="home" data-action="list">
					
					</div>
					
			</div> 

{% endblock %}