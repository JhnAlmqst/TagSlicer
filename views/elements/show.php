<h1>{{ page_title }}</h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th><span class="badge">{{ count }}</span></th>
			<th>{{ url }} <small>(<a href="{{ url }}" target="_blank">link</a>)</small></th>
		</tr>
	</thead>
	<tbody>
		{% for item in values %}
		<tr>
			<td>{{ loop.index }}</td>
			<td>{{ item|e }}<br />{{ item }}</td>
		</tr>
		{% endfor %}
	</tbody>
</table>