<?php ?>

<!-- Image Slider -->
<script id="wp-theme-shapeshifter-extensions-tinymce-button-template-slider" type="text/template">
<% if( images.length > 0 ) { %>
<div 
	class="slider-pro shapeshifter-slider-pro shapeshifter-slider-pro-simple-images shapeshifter-responsive-wrapper shapeshifter-draggable"
	data-slider-type="images" 
	data-slider-feature="simple" 
	data-slide-width="<%- slideWidth %>" 
	data-slide-height="<%- slideHeight %>" 
	data-sidecontrol-arrows="<%- ( sideControlArrows ? 'true' : '' ) %>"
	data-bottom-buttons="<%- ( bottomButtons ? 'true' : '' ) %>"
	data-thumbnail-type="<%- thumbnailType %>" 
	data-thumbnail-width="<%- thumbnailWidth %>" 
	data-thumbnail-height="<%- thumbnailHeight %>"
>
	<div class="sp-slides">
		<% for( var index in images ) { %>
			<div class="sp-slide">
				<img class="sp-image shapeshifter-slider-pro-image" src="blank.png" data-src="<%- images[ index ].imageSrc %>">
				<% if( thumbnailType !== 'none' ) { %>
					<div class="sp-thumbnail">
						<img class="sp-thumbnail-image" src="blank.png" data-src="<%- images[ index ].imageSrc %>" width="<%- thumbnailWidth %>" height="<%- thumbnailHeight %>">
					</div>
				<% } %>
			</div>
		<% } %>
	</div>
</div>
<% } %>
</script>

<?php ?>