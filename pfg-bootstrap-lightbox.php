<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Blue Imp Light Box Load File
 */
wp_enqueue_style('awl-bootstrap-lightbox-css', PFG_PLUGIN_URL .'lightbox/bootstrap/css/ekko-lightbox.css');
wp_enqueue_script('awl-bootstrap-lightbox-js', PFG_PLUGIN_URL .'lightbox/bootstrap/js/ekko-lightbox.js', array('jquery'), '' , true);

$allimages = array(  'p' => $pf_gallery_id, 'post_type' => 'awl_filter_gallery', 'orderby' => 'ASC');
$loop = new WP_Query( $allimages );
while ( $loop->have_posts() ) : $loop->the_post();
	$post_id = get_the_ID();
	$all_category = get_option('awl_portfolio_filter_gallery_categories');

	// collect all selected filters assigned on images
	$all_selected_filters = array();
	foreach ($filters as $filters_key => $filters_value) {
		if(is_array($filters_value)) {
			$all_selected_filters = array_merge($all_selected_filters, $filters_value);
		}
	}
	?>
	
	<div class="row text-center">
		<ul class="simplefilter simplefilter_<?php echo $pf_gallery_id; ?>">
			<li class="active" data-filter="all">All</li>
			<?php
			if(is_array($all_selected_filters) && count($all_selected_filters)) {
				$all_selected_filters = array_unique($all_selected_filters ); // remove same key
				foreach ($all_selected_filters as $filter_key) {
					?>
					<li data-filter="<?php echo $filter_key ?>"><?php echo $all_category[$filter_key]; ?></li>
					<?php
				}
			}?>
		</ul>
	</div>

	<div id="filter_gallery_<?php echo $pf_gallery_id; ?>" class="row filtr-container filters-div">
		<?php
		
		if(isset($pf_gallery_settings['image-ids']) && count($pf_gallery_settings['image-ids']) > 0) {
			$count = 0;
			if($thumbnail_order == "DESC") {
				$pf_gallery_settings['image-ids'] = array_reverse($pf_gallery_settings['image-ids']);
			}
			if($thumbnail_order == "RANDOM") {
				shuffle($pf_gallery_settings['image-ids']);
			}			
			$no = 1;
			foreach($pf_gallery_settings['image-ids'] as $attachment_id) {
				//$attachment_id;
				$image_link_url =  $pf_gallery_settings['image-link'][$count];
				$thumb = wp_get_attachment_image_src($attachment_id, 'thumb', true);
				$thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail', true);
				$medium = wp_get_attachment_image_src($attachment_id, 'medium', true);
				$large = wp_get_attachment_image_src($attachment_id, 'large', true);
				$full = wp_get_attachment_image_src($attachment_id, 'full', true);
				$postthumbnail = wp_get_attachment_image_src($attachment_id, 'post-thumbnail', true);
				$attachment_details = get_post( $attachment_id );
				$href = get_permalink( $attachment_details->ID );
				$src = $attachment_details->guid;
				$title = $attachment_details->post_title;
				$description = $attachment_details->post_content;
				
				//set thumbnail size
				if($gal_thumb_size == "thumbnail") { $thumbnail_url = $thumbnail[0]; }
				if($gal_thumb_size == "medium") { $thumbnail_url = $medium[0]; }
				if($gal_thumb_size == "large") { $thumbnail_url = $large[0]; }
				if($gal_thumb_size == "full") { $thumbnail_url = $full[0]; }
				
				// seach attachment id in to $filters and get all filter ids
				//$pfg_filters = $pf_gallery_settings['filters'];
				foreach ($filters as $pfg_filters_key => $pfg_filters_values) {
				}
				if (array_key_exists($attachment_id, $filters)) {
					$filter_key_array = $filters[$attachment_id];
					$prefix = $filter_keys = '';
					
					if(count($filter_key_array) > 1) {
						foreach ($filter_key_array as $filter_key => $filter_value) {
							$filter_keys .= $prefix . $filter_value;
							$prefix = ', ';
						}
					} else {
						$filter_keys = $filter_key_array[0];						
					}
				}
					?>
				
				
					<?php if($image_link_url) { ?>
					
					<a href="<?php echo $image_link_url; ?>" title="<?php echo $title; ?>" target="<?php echo $url_target; ?>">
						<div data-category="<?php echo $filter_keys; ?>" data-sort="<?php echo $title; ?>" class="filtr-item filtr_item_<?php echo $pf_gallery_id; ?> single_one <?php echo $col_large_desktops; ?> <?php echo $col_desktops; ?> <?php echo $col_tablets; ?> <?php echo $col_phones; ?>">
							<img class="thumbnail thumbnail_<?php echo $pf_gallery_id; ?> pfg-img pfg_img_<?php echo $pf_gallery_id; ?> img-responsive <?php echo $image_hover_effect; ?>" src="<?php echo $thumbnail_url; ?>" alt="<?php echo $title; ?>">
							<?php if($image_numbering) {?>
								<div class="item-position item_position_<?php echo $pf_gallery_id; ?>"><?php echo $no; ?></div>
							<?php } ?>
							<?php if($title_thumb == "show") {?>
							<span class="item-desc item_desc_<?php echo $pf_gallery_id; ?>"><?php echo $title; ?></span>
							<?php } ?>
						</div>
					</a>
					
					<?php } else { ?>
					
					<a href="<?php echo $full[0]; ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo $title; ?>">
						<div data-category="<?php echo $filter_keys; ?>" data-sort="<?php echo $title; ?>" class="filtr-item filtr_item_<?php echo $pf_gallery_id; ?> single_one <?php echo $col_large_desktops; ?> <?php echo $col_desktops; ?> <?php echo $col_tablets; ?> <?php echo $col_phones; ?>">
							<img class="thumbnail thumbnail_<?php echo $pf_gallery_id; ?> pfg-img pfg_img_<?php echo $pf_gallery_id; ?> img-responsive <?php echo $image_hover_effect; ?>" src="<?php echo $thumbnail_url; ?>" alt="<?php echo $title; ?>">
							<?php if($image_numbering) {?>
								<div class="item-position item_position_<?php echo $pf_gallery_id; ?>"><?php echo $no; ?></div>
							<?php } ?>
							<?php if($title_thumb == "show") {?>
							<span class="item-desc item_desc_<?php echo $pf_gallery_id; ?>"><?php echo $title; ?></span>
							<?php } ?>
						</div>
					</a>
					
					<?php } ?>
				
				<?php
					
				$no++;
				$count++;
				
			}// end of attachment foreach
		} else {
			_e('Sorry! No image gallery found ', PFG_TXTDM);
			echo ":[PFG id=$post_id]";
		} // end of if esle of images avaialble check into imager
		?>
		
	</div>

<?php
endwhile;
wp_reset_query();
?>
<script>
jQuery( window ).load(function() {
	
	
	//Filterizd Default options
	options = {
		animationDuration: 0.5,
		callbacks: {
			onFilteringStart: function() { },
			onFilteringEnd: function() { },
			onShufflingStart: function() { },
			onShufflingEnd: function() { },
			onSortingStart: function() { },
			onSortingEnd: function() { }
		},
		delay: 50,
		delayMode: 'progressive',
		easing: 'ease-out',
		filter: 'all',
		//layout: 'sameWidth',
		selector: '#filter_gallery_<?php echo $pf_gallery_id; ?>',
		setupControls: true
	}
	var filterizd = jQuery('#filter_gallery_<?php echo $pf_gallery_id; ?>').filterizr('setOptions', options);
	
	//bootstrap-lightbox-js
	jQuery(document).ready(function (jQuery) {
	// delegate calls to data-toggle="lightbox"
	jQuery(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
		event.preventDefault();
		return jQuery(this).ekkoLightbox({
			onShown: function() {
				/* if (window.console) {
					return console.log('Checking our the events huh?');
				} */
			},
			onNavigate: function(direction, itemIndex) {
				if (window.console) {
					return console.log('Navigating '+direction+'. Current item: '+itemIndex);
				}
			}
		});
	});

	//Programatically call
	jQuery('#open-image').click(function (e) {
		e.preventDefault();
		jQuery(this).ekkoLightbox();
	});
	jQuery('#open-youtube').click(function (e) {
		e.preventDefault();
		jQuery(this).ekkoLightbox();
	});

	// navigateTo
	jQuery(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
		event.preventDefault();

		var lb;
		return jQuery(this).ekkoLightbox({
			onShown: function() {
				lb = this;
				jQuery(lb.modal_content).on('click', '.modal-footer a', function(e) {
					e.preventDefault();
					lb.navigateTo(2);
				});
			}
		});
	});
});
	
});   
</script>