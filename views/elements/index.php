<h1>{{ page_title }}</h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>URL</th>
			<th>Тип</th>
			<th>Найдено</th>
		</tr>
	</thead>
	<tbody>
		{% for item in elements %}
			{% if types[item.type] %}
			<tr>
				<td><a href="/elements/show/{{ item.id }}/">{{ loop.index }}</a></td>
				<td><a href="/elements/show/{{ item.id }}/">{{ item.url }}</a> 
					<small>(<a href="{{ item.url }}" target="_blank">link</a>)</small></td>
				<td>{{ types[item.type] }}</td>
				<td>{{ item.count }}</td>
			</tr>
			{% endif %}
		{% endfor %}
	</tbody>
</table>
{% if count %}
<nav>
  <ul class="pagination">
	{% for i in count %}
		<li{{ current == i ? ' class="active"' : '' }}><a href="/elements/page/{{ i }}/">{{ i }}</a></li>
	{% endfor %}
  </ul>
</nav>
{% endif %}