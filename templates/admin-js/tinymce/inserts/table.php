<?php ?>

<!-- Table -->
<script id="wp-theme-shapeshifter-extensions-tinymce-button-template-table" type="text/template">
<div class="shapeshifter-table-wrapper shapeshifter-overflow-scroll-wrapper" data-table-feature="<%- tableFeature %>"data-table-caption="<%- tableCaption %>" data-has-header="<%- ( hasHeader ? 'true' : 'false' ) %>" data-has-footer="<%- ( hasFooter ? 'true' : 'false' ) %>" data-rows-number="<%- rowsNum %>" data-columns-number="<%- colsNum %>">
	<table class="shapeshifter-table <%- tableFeature %>-table">

		<% if( tableCaption ) { %>
			<caption><%- tableCaption %></caption>
		<% } %>

		<% if( hasHeader ) { %>
			<thead>
				<tr>
					<th></th>
					<% for( var i = 2; i <= colsNum; i++ ) { %>
						<th></th>
					<% } %>
				</tr>
			</thead>
		<% } %>

		<tbody>
			<% for( var i = 1; i <= rowsNum; i++ ) { %>
				<tr>
					<th></th>
					<% for( var i2 = 2; i2 <= colsNum; i2++ ) { %>
						<td></td>
					<% } %>
				</tr>
			<% } %>
		</tbody>

		<% if( hasFooter ) { %>
			<tfoot>
				<tr>
					<th></th>
					<% for( var i = 2; i <= colsNum; i++ ) { %>
						<td></td>
					<% } %>
				</tr>
			</tfoot>
		<% } %>

	</table>
</div>
</script>


<?php ?>