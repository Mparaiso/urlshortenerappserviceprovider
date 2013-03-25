<?php
/* @var $templates array un tableau de templates */
$templates=array();

$templates["flash"] = <<<EOD
{% for type,messages in app.session.getFlashBag().all() %}
	{% for message in messages %}
		<div class="alert alert-{{ type }}">
		<button type="button" 
			class="close" 
			data-dismiss="alert">&times;</button>
		{{ message }}
		</div>
	{% endfor %}
{% endfor %}
EOD;

$templates["layout"] = <<<EOD
<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>{% block title 'UrlShortener' %}</title>
		{% block stylesheets %}
			<link rel="stylesheet" 
				href="{{app.request.basepath}}/vendor/bootstrap/css/bootstrap.min.css" />
		{% endblock %}
	</head>
	<body>
		<div class="container">
		{% block head %}<h1>Url Shortener</h1>{% endblock %}
		{% block flash %}
		{% include 'flash' %}
		{% endblock %}
		{% block content %}{%endblock%}
		</div>
	</body>
</html>
EOD;

$templates["index"]= <<<EOD
{% extends "layout" %}
{% block content %}
	{% if link is not null %}
			{% set url = url("index") ~ link.identifier %}
		<h4>
			{{link.url.getOriginal()}} has been shortened to {{url }}
		</h4>
			<div class="">
			Go to <a href="{{url}}" target="_blank">{{url}}</a>
			</div>
	{%endif%}
	<div>
		Shorten this : 
		<form method="POST">
			{{ form_widget(form) }}
		<input type="submit" value="Shorten" />
	</form>
	</div>
{% endblock %}
EOD;

$templates["info"]= <<<EOD
{% extends "layout" %}
{% block content %}
<div>Original : {{link.url.original}}</div>
<div>Shortened : {{url("index")~link.identifier}}</div>
<div>Date created : {{link.createdAt | date()}}</div>
<div>Number of visits : {{ link.visits| length }}</div>
{% endblock %}
EOD;

return $templates;