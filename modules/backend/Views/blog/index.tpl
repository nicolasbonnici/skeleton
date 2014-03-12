{% extends 'layout.tpl' %}

{% block favicon %}/lib/img/aps/blog/icon.png{% endblock favicon %}
{% block meta_title %}Blogging app{% endblock meta_title %}
{% block meta_description %}A simple blogging application{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="/lib/plugins/summernote/js/summernote.js"></script>
<script type="text/javascript" src="/lib/plugins/moment/js/moment.min.js"></script>
<script type="text/javascript" src="/lib/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.grow.js"></script>
    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.tooltip_0.4.4.js"></script>
    <script type="text/javascript" src="/lib/plugins/charts/flot/jquery.flot.orderBars.js"></script>

<script type="text/javascript">

//generate random number for charts
randNum = function(){
    //return Math.floor(Math.random()*101);
    return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
}

$(document).ready(function() {
    // Show the delete btn for several checked checkbox
    $('body').on('.ui-select.posts', 'click', function() {
       alert($('.ui-select.posts:checked').size());
       if ($('.ui-select.posts:checked').size() > 1) {
           $('.ui-delete-posts').removeClass('hide');
       } else {
           $('.ui-delete-posts').addClass('hide');
       }
    });
    
    // Charts
    $(document).ajaxStop(function() {
        var chartColours = ['#88bbc8', '#ed7a53', '#9FC569', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282'];
        //some data
        var d1 = [[1, 3+randNum()], [2, 6+randNum()], [3, 9+randNum()], [4, 12+randNum()],[5, 15+randNum()],[6, 18+randNum()],[7, 21+randNum()],[8, 15+randNum()],[9, 18+randNum()],[10, 21+randNum()],[11, 24+randNum()],[12, 27+randNum()],[13, 30+randNum()],[14, 33+randNum()],[15, 24+randNum()],[16, 27+randNum()],[17, 30+randNum()],[18, 33+randNum()],[19, 36+randNum()],[20, 39+randNum()],[21, 42+randNum()],[22, 45+randNum()],[23, 36+randNum()],[24, 39+randNum()],[25, 42+randNum()],[26, 45+randNum()],[27,38+randNum()],[28, 51+randNum()],[29, 55+randNum()], [30, 60+randNum()]];
        var d2 = [[1, randNum()-5], [2, randNum()-4], [3, randNum()-4], [4, randNum()],[5, 4+randNum()],[6, 4+randNum()],[7, 5+randNum()],[8, 5+randNum()],[9, 6+randNum()],[10, 6+randNum()],[11, 6+randNum()],[12, 2+randNum()],[13, 3+randNum()],[14, 4+randNum()],[15, 4+randNum()],[16, 4+randNum()],[17, 5+randNum()],[18, 5+randNum()],[19, 2+randNum()],[20, 2+randNum()],[21, 3+randNum()],[22, 3+randNum()],[23, 3+randNum()],[24, 2+randNum()],[25, 4+randNum()],[26, 4+randNum()],[27,5+randNum()],[28, 2+randNum()],[29, 2+randNum()], [30, 3+randNum()]];
        //define placeholder class
        var placeholder = $("#visitors-chart");
        //graph options
        var options = {
                grid: {
                    show: true,
                    aboveData: true,
                    color: "#3f3f3f" ,
                    labelMargin: 5,
                    axisMargin: 0, 
                    borderWidth: 0,
                    borderColor:null,
                    minBorderMargin: 5 ,
                    clickable: true, 
                    hoverable: true,
                    autoHighlight: true,
                    mouseActiveRadius: 20
                },
                series: {
                    grow: {
                        active: false,
                        stepMode: "linear",
                        steps: 50,
                        stepDelay: true
                    },
                    lines: {
                        show: true,
                        fill: true,
                        lineWidth: 4,
                        steps: false
                        },
                    points: {
                        show:true,
                        radius: 5,
                        symbol: "circle",
                        fill: true,
                        borderColor: "#fff"
                    }
                },
                legend: { 
                    position: "ne", 
                    margin: [0,-25], 
                    noColumns: 0,
                    labelBoxBorderColor: null,
                    labelFormatter: function(label, series) {
                        // just add some space to labes
                        return label+'&nbsp;&nbsp;';
                     }
                },
                yaxis: { min: 0 },
                xaxis: {ticks:11, tickDecimals: 0},
                colors: chartColours,
                shadowSize:1,
                tooltip: true, //activate tooltip
                tooltipOpts: {
                    content: "%s : %y.0",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            };   

            $.plot(placeholder, [ 

                {
                    label: "Visits", 
                    data: d1,
                    lines: {fillColor: "#f2f7f9"},
                    points: {fillColor: "#88bbc8"}
                }, 
                {   
                    label: "Unique Visits", 
                    data: d2,
                    lines: {fillColor: "#fff8f2"},
                    points: {fillColor: "#ed7a53"}
                } 

            ], options);
    });

});
</script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/lib/plugins/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote-bs3.css" rel="stylesheet">
<style>
span.reminder-number {
    font-size: 22px;
    font-weight: bold;
}
span.reminder-txt {}

#visitors-chart {
    height: 350px;
}
</style>
{% endblock %}

{% block modal %}
<div class="modal fade" id="modal-post" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-post-content">
            <p>&nbsp;</p>
		</div>
	</div>				
</div>
{% endblock %}

{% block main %}

	<div class="row clearfix transparentBlackBg rounded well ui-transition ui-shadow">
		<div class="col-md-12 column">
    		<h1 class="showOnHover">
    			<img src="/lib/img/apps/blog/icon.png" alt="App icon" />Blogging app <small class="targetToShow">1.0</small>
    		</h1>
            <ul class="nav nav-pills">
              <li class="active"><a href="#" class="ui-sendxhr" data-url="/backend/blog/dashboard/" data-selector="#dashboard" role="button">Dashboard</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  Posts <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#modal-post" type="button" class="ui-sendxhr" data-url="/backend/blog/createblog/" data-selector="#modal-post-content" role="button" data-toggle="modal">
                            <span class="glyphicon glyphicon-file"></span> Nouveau
                        </a>
                    </li>
                    <li>
                        <a href="#modal-post" type="button" class="ui-sendxhr" data-url="/backend/blog/posts/" data-selector="#dashboard" role="button">
                            <span class="glyphicon glyphicon-file"></span> Gérer
                        </a>
                    </li>
                </ul>
              </li>              
              <li><a href="#">Comments</a></li>
              <li><a href="#">Settings</a></li>
            </ul>
		</div>
        
		<div class="col-md-12 column" id="dashboard">
        </div>
	</div>

{% endblock %}

