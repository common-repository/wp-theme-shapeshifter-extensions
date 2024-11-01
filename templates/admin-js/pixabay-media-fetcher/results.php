<?php ?>


<!-- Media Fetcher Results -->
	<script id="wp-theme-shapeshifter-extensions-pixabay-media-fetcher-results" type="text/template">
		<% 

			var pageTotal = parseInt( data.totalHits / options.per_page )+ ( data.totalHits % options.per_page > 0 ? 1 : 0 );

			jQuery.each( data.hits, function( index, image ) { 

		%>

			<li tabindex="0" role="checkbox" aria-label="e835b3062fe91c72d75b410dfb4d429eeb75e2dc1ab910_1920" aria-checked="false" data-id="<%- image.id %>" class="attachment pixabay-media" style="width: 116px; height: 116px;">
				<div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape" style="
					width: 100px;
					height: 100px;
				">
					<div class="thumbnail" style="
						width: 100px;
						height: 100px;
						background-image: url(<%- image.previewURL %>);
						background-size: cover;
						background-position: center center;
						background-repeat: no-repeat;
					" 
						<% for( var key in image ) { %>data-image<%- key.toLowerCase() %>="<%- image[key] %>" <% } %>
					>
					</div>

					<button type="button" class="button-link check" tabindex="-1">
						<span class="media-modal-icon"></span>
						<span class="screen-reader-text"><?php esc_html_e( 'Remove the Check', ShapeShifter_Extensions::TEXTDOMAIN ); ?></span>
					</button>

				</div>
			</li>
		<%
			});

			if( options.page < pageTotal ) {
		%>
				<li tabindex="0" role="checkbox" aria-label="e835b3062fe91c72d75b410dfb4d429eeb75e2dc1ab910_1920" aria-checked="false" class="attachment pixabay-media" style="width: 116px;">
					<button class="button load-next" style="width: 100px; height: 100px;"><?php esc_html_e( 'Next', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
				</li>
		<%
			} 
		%>
	</script>

<!-- Media Pagination -->
	<script id="wp-theme-shapeshifter-extensions-pixabay-popup-pagination" type="text/template">
		<% for( var page = 1; page <= pageTotal; page++ ) { %>
			<option value="<%- page %>" <% if( page == currentPage ){ %>selected<% } %>><%- page %></option>
		<% } %>
	</script>

<!-- Media Popup Image Preview -->
	<script id="wp-theme-shapeshifter-extensions-pixabay-popup-popup-image-preview" type="text/template">
		<div class="pixabay-image-preview-popup" style="
			display: flex;
		">
			<div class="pixabay-image-preview-popup-image-wrapper" style="
				width: 80%;
				text-align: center;
			">
				<img src="<%- previewImageSrc %>" style="
					width: 100%;
					height: 100%;
				">
			</div>
			<div class="pixabay-image-preview-popup-controls" style="
				width: 20%;
				padding: 10px;
			">
				<button class="button close-media-preview-popup"><?php esc_html_e( 'Close', ShapeShifter_Extensions::TEXTDOMAIN ); ?></button>
			</div>
		</div>
	</script>

