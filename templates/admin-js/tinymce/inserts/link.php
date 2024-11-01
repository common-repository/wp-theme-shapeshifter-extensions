<?php ?>

<!-- Link -->
<!-- Post -->
<script id="wp-theme-shapeshifter-extensions-tinymce-button-template-link" type="text/template">
<div class="shapeshifter-link-wrapper shapeshifter-flex-wrapper">
	<% if( isThumbnailOn ) { %>
		<div class="shapeshifter-link-thumbnail-wrapper">
			<a class="shapeshifter-link-thumbnail-a"
				href="<%- linkPermalink %>"
				<% print( linkTarget != "" ? 'target="_blank"' : '' ) %>
				<% print( linkRel != "" ? 'rel="nofollow"' : '' ) %>
			>
				<img class="shapeshifter-link-thumbnail" src="<%- thumbnailURL %>" alt="<%- thumbnailTitle %>" width="100" height="100">
			</a>
		</div>
	<% } %>
	<div class="shapeshifter-link-title-wrapper">
		<p class="shapeshifter-link-title">
			<a class="shapeshifter-link-title-a"
				href="<%- linkPermalink %>"
				<% print( linkTarget != "" ? 'target="_blank"' : '' ) %>
				<% print( linkRel != "" ? 'rel="nofollow"' : '' ) %>
			>
				<%- thumbnailTitle %>
			</a>
		</p>
	</div>
</div>
</script>


<?php ?>