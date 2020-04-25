<?php
/*
Plugin Name: Best website Image Alt Text
Plugin URI: https://github.com/bestwebsite/image-alt-text
Description: Check for missing alternative text on uploaded images.
Author: BestWebsite
Version: 1.0
Author URI: https://github.com/bestwebsite/image-alt-text
*/

/******************** PLUGIN FUNCTIONS BEGIN ********************/
add_action('admin_menu', 'bestweb_image_alt_text_menu');

function bestweb_image_alt_text_menu() {
	add_options_page( 'bestweb Image Alt Text', 'Image Alt Text', 'manage_options', 'bestweb_image_alt_text', 'bestweb_image_alt_text_options' );
}

function bestweb_image_alt_text_options() { ?>
	<?php
		$query_images_args = array(
		    'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
		);
		$query_images = new WP_Query($query_images_args);
		$images = array();
		foreach ( $query_images->posts as $image) {
			$altText = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
			
			if(strlen($altText) === 0) {
				$imgObj = array(
					'id' => $image->ID,
					'url' => wp_get_attachment_thumb_url($image->ID)
				);
				
				$images[]= $imgObj;
			}
		}
	?>
	<div id="=bestweb_image_alt_text_options" class="wrap">
		<h2>bestweb Image Alt Text</h2>
		<span>The following images do not have alt text associated with them:</span><br /><br />
		<table class="widefat">
			<thead>
				<tr>
					<th>Image ID</th>
					<th>Thumbnail</th>
					<th>Manage</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($images as $key => $value) : ?>
					<tr <?php echo ($key % 2 === 0 ? 'class="alternate"' : ''); ?>>
						<td><?php echo $value['id']; ?></td>
						<td><img src="<?php echo $value['url']; ?>" alt="thumbnail of <?php echo $value['id']; ?>" /></td>
						<td><a href="<?php echo admin_url(); ?>upload.php?item=<?php echo $value['id']; ?>">Manage</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php } ?>