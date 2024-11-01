<?php ?>

<!-- Balloon -->
<script id="wp-theme-shapeshifter-extensions-tinymce-button-template-balloon" type="text/template">
	<div class="shapeshifter-balloon-outer-wrapper shapeshifter-balloon-<%- balloonType %>" 
		data-balloon-type="<%- balloonType %>"
		data-balloon-name="<%- balloonImageCaption %>"
		data-balloon-image="<%- balloonImage %>"
		data-balloon-dialog="<%- balloonDialog %>"
		data-balloon-image-align="<%- balloonImageAlign %>"
	>
		<div class="shapeshifter-balloon-wrapper balloon-type-<%- balloonType %> align-<%- balloonImageAlign %>">
			<div class="shapeshifter-balloon-image-wrapper">
				<figure class="shapeshifter-balloon-image-figure">
					<img class="shapeshifter-balloon-image shadow-<%- balloonImageAlign %>-bottom" alt="<%- balloonImageCaption %>" src="<%- balloonImage %>" width="100" height="100">
					<% if( ! _.isEmpty( balloonImageCaption ) ) { %><figcaption class="shapeshifter-balloon-image-caption"><%- balloonImageCaption %></figcaption><% } %>
				</figure>
			</div>
			<div class="shapeshifter-balloon-dialog"><div>
				<% print( balloonDialog ) %>
			</div></div>
		</div>
	</div>
</script>
<?php ?>