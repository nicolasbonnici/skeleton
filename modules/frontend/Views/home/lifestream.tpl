{% extends appLayout %}

{% block meta_title %}Test{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block css %}{% endblock %}

{% block js %}
<script>
$(document).ready(function() {
	var core = $.fn.core();
	
	$('.filterFeed').on('click', function() {
        $(this).toggleClass('btn-success').toggleClass('btn-default');
        
        return false;
	});
	
	$('.filterFeedItems').on('click', function() {

		var iFeedsCount = $('.filterFeed.btn-success').size();
		if (iFeedsCount > 0) {
			var sFeedId = ''; 
			var iIndex = 1;
			$('.filterFeed.btn-success').each(function() {
				sFeedId += new String($(this).data('ifeedid') + ((iIndex === iFeedsCount) ? '' : ','));
				iIndex++;
			});
		}
		$($(this).data('sreloadtarget')).data('sfeedid', sFeedId);
		return false;
	});
});
</script>
{% endblock %}

{% block main %}

			<div class="row">
					<div class="col-md-12 column">
					    <div class="col-md-9 transparentBlackBg rounded well text-left ui-shadow">
		        			{% if oFeeds|Exists %}
							{% for oFeed in oFeeds %}
							<div class="btn-group">
	                          <a href="#" data-ifeedid="{{oFeed.idfeed}}" class="filterFeed btn btn-default btn-lg" title="Afficher l'activité du feed {{oFeed.title}}">
	                                <img src="{{oFeed.icon}}" class="icon-feed" alt="Feed icon" />
	                          </a>
							  <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" title="Afficher les options du feed {{oFeed.title}}">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li>
		                            <a href="#" class="" title="Parser les nouveaux éléments de ce feed">
		                                 <span class="glyphicon glyphicon-refresh"></span> Parse
		                            </a>						    
							    </li>
							    <li><a href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
							    <li class="divider"></li>
							    <li><a href="#"><span class="glyphicon glyphicon-remove"></span> Delete</a></li>
							  </ul>
							</div>						
							{% endfor %}
						    {% endif %}
						</div>
						<div class="col-md-2 col-md-offset-1 transparentBlackBg rounded well text-center ui-shadow">
	                        <a href="#" class="btn btn-lg btn-default filterFeedItems" data-sreloadtarget="#lifestream" title="Filtrer les résultats">
	                          Recharger <span class="glyphicon glyphicon-refresh"></span>
	                        </a>
						</div>
					</div>
	
					<div id="lifestream" class="col-md-12 column ui-loadable ui-scroll-loadable ui-grid" data-module="frontend" data-controller="home" data-action="list">
					</div>
					
			</div> 

{% endblock %}