<?php
/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 8/22/17
 * Time: 1:17 PM
 */

class Child_Theme_Shortcodes {

	public function __construct()
	{

	}


	public function register() {

		add_shortcode('testimonial', array( $this, 'render_testimonial'));



	}

	public function render_testimonial( $atts, $content ) {


		/** @var  $post_type */
		/** @var  $name */
		/** @var  $badge_text */
		/** @var  $image_size */
		/** @var  $horizontal */
		/** @var  $image_right */
		/** @var  $word_count */
		/** @var  $card_group */
		/** @var  $button_text */
		/** @var  $img_ctx_class */




		$atts_actual = shortcode_atts(
			array(
				'post_type'           => 'post',
				'category_name'       => 'Testimonial',
				'name'                => '',
				'badge_text'          => '',
				'button_text'         => 'Read more',
				'image_size'          => 'medium',
				'horizontal'          => 'false',
				'image_right'         => 'false',
				'word_count'          => '-1',
				'card_group'          => '',
				'img_ctx_class'         => ''
			),
			$atts );


		extract( $atts_actual );

		$word_count = intval( $word_count);

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'post_count'     => 5,
			'posts_per_page' => 5
		);

		// If name is not set, default to the most recent product.
		if ( !empty( $name )) {
			$args['name'] = $name ;
		}

		$post_query = new WP_Query( $args );

		$template = '
			<div class="testimonial-container ct-hidden">
			 	<div class="row">
			 		<div class="col-xs-9 testimonial-text">
			 			<h3>__POST_TITLE__</h3>
			 			<p>__POST_EXCERPT__</p>
					</div>
					<div class="col-xs-3 testimonial-image">
			 			__POST_IMAGE_HTML__
					</div>
				</div>
			</div>
		';

		if ( $post_query->have_posts() ) {

			$output = '<div class="container-fluid testimonial-slider">' ;

			while ( $post_query->have_posts() ) : $post_query->the_post();

				$id = get_the_ID();

				$post_title = get_the_title();;

				$post_excerpt = get_the_excerpt();
				$post_url = get_the_permalink();
				$attachment_id = get_post_thumbnail_id($id);

				$slide_class = 'pad-hover-caption-image product-image-bottom';
				$post_image_src = wp_get_attachment_image_src($attachment_id, $image_size);
				$post_image_html = wp_get_attachment_image( $attachment_id, $image_size , false, array( 'class' => 'attachment-' . $image_size . ' size-' . $image_size . ' img-fluid ' . $slide_class));
				$img_width = $post_image_src[1];
				$img_height = $post_image_src[2];
				$data_str = ' data-width="' . $img_width . '" data-height="' . $img_height . '" />';

				$post_image_html = str_replace('/>', $data_str, $post_image_html );
				$post_image_html = '<a href="' . $post_url . '">' . $post_image_html .  '</a>';

				$slide_html  = str_replace('__POST_TITLE__', $post_title, $template);
				$slide_html  = str_replace('__POST_EXCERPT__', $post_excerpt, $slide_html);
				$slide_html  = str_replace('__POST_IMAGE_HTML__', $post_image_html, $slide_html);

				$output .= $slide_html;


			endwhile;

		}
		$output .= '</div>';
		wp_reset_postdata();

		return $output;
	}

}