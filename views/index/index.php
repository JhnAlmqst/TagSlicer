<h1>{{ page_title }}</h1>
<form class="form-horizontal search-form" method="post" action="/search/">
	<div class="form-group">
		<label for="link" class="col-sm-2 control-label">Адрес сайта</label>
		<div class="col-sm-9">
			<input type="text" name="link" class="form-control required" id="link" autocomplete="off" placeholder="Адрес сайта">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Тип поиска</label>
		<div class="col-sm-9 radio-group">
		{% for type in types %}
			<div class="radio">
				<label for="type{{ type.id }}">
					<input type="radio" name="type" id="type{{ type.id }}" autocomplete="off" value="{{ type.id }}" {{ loop.first ?  "checked" : "" }}>
					{{ type.title }}
				</label>
			</div>
		{% endfor %}
		</div>
	</div>
	<div class="form-group hidden">
		<label for="text" class="col-sm-2 control-label">Что искать</label>
		<div class="col-sm-9">
			<input type="text" name="text" class="form-control required" id="text" autocomplete="off" placeholder="Что искать">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-9 col-sm-offset-2">
			<button type="button" class="btn btn-primary submit">Отправить</button>
			<span class="error label label-danger"></span>
		</div>
	</div>	
</form>