{% extends 'layout.tpl' %}

{% block meta_title %}Todo!{% endblock meta_title %}
{% block meta_description %}{% endblock meta_description %}

{% block js %}
<script type="text/javascript" src="/lib/plugins/summernote/js/summernote.js"></script>
{% endblock %}

{% block css %}
<link href="/lib/plugins/summernote/css/summernote.css" rel="stylesheet">
<link href="/lib/plugins/summernote/css/summernote-bs3.css" rel="stylesheet">
{% endblock %}

{% block main %}

	<div class="row clearfix transparentBlackBg rounded well ui-transition ui-shadow">
		<div class="col-md-12 column">
			<div class="page-header">
				<h1 class="showOnHover">
					Todo! <small class="targetToShow">1.0</small>
				</h1>
			</div>
		</div>

		<div class="col-md-12 column">
			<div class="btn-group btn-group-lg text-right">
				 <button class="btn btn-default" type="button"id="modal-275600" href="#modal-container-275600" role="button" class="btn" data-toggle="modal"><em class="glyphicon glyphicon-file"></em> New todo!</button> 
				 <button class="btn btn-default" type="button"><em class="glyphicon glyphicon-align-center"></em> Center</button> 
			</div>			
			<div class="modal fade" id="modal-container-275600" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">
								New todo
							</h4>
						</div>
						<div class="modal-body">
							<form role="form">
								<div class="form-group">
									 <label for="exampleInputEmail1">Email address</label><input type="email" class="form-control" id="exampleInputEmail1" />
								</div>
								<div class="form-group">
									 <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" />
								</div>
								<div class="form-group">
									 <label for="exampleInputFile">File input</label><input type="file" id="exampleInputFile" />
									<p class="help-block">
										Example block-level help text here.
									</p>
								</div>
								<div class="checkbox">
									 <label><input type="checkbox" /> Check me out</label>
								</div> <button type="submit" class="btn btn-default">Submit</button>
							</form>							
						</div>
						<div class="modal-footer">
							 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary">Save changes</button>
						</div>
					</div>
					
				</div>
				
			</div>
			

			<table class="table table-hover">
				<thead>
					<tr>
						<th>
							#
						</th>
						<th>
							Title
						</th>
						<th>
							Last edited
						</th>
						<th>
							Status
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							1
						</td>
						<td>
							TB - Monthly
						</td>
						<td>
							01/04/2012
						</td>
						<td>
							Default
						</td>
					</tr>
					<tr class="active">
						<td>
							1
						</td>
						<td>
							TB - Monthly
						</td>
						<td>
							01/04/2012
						</td>
						<td>
							Approved
						</td>
					</tr>
					<tr class="success">
						<td>
							2
						</td>
						<td>
							TB - Monthly
						</td>
						<td>
							02/04/2012
						</td>
						<td>
							Declined
						</td>
					</tr>
					<tr class="warning">
						<td>
							3
						</td>
						<td>
							TB - Monthly
						</td>
						<td>
							03/04/2012
						</td>
						<td>
							Pending
						</td>
					</tr>
					<tr class="danger">
						<td>
							4
						</td>
						<td>
							TB - Monthly
						</td>
						<td>
							04/04/2012
						</td>
						<td>
							Call in to confirm
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

{% endblock %}

