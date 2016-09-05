<?php
//http://gabrieleromanato.com/2012/02/wordpress-visualizzare-i-post-correlati-senza-plugin/
function show_related_posts() {
	global $post;

		$tags = wp_get_post_tags($post->ID);
		
		if($tags) {
		
    	$first_tag = $tags[0]->term_id;
    	$args = array(
    		'tag__in' => array($first_tag),
    		'post__not_in' => array($post->ID),
    		'showposts'=> 4,
    		'ignore_sticky_posts'=>1
   		);
    $post_correlati = new WP_Query($args);
  		if( $post_correlati->have_posts() ) {
          echo '<h3>' . __('Related posts', 'italystrap') .  '</h3>' . "\n";
  		    echo '<div class="row" itemscope itemtype="http://schema.org/Article">' . "\n";
    		while ($post_correlati->have_posts()) : $post_correlati->the_post(); ?>
				<span class="col-md-3 col-xs-6">
					<?php if ( has_post_thumbnail() ) {
							echo "<figure><span class='thumbnail'>";
                              the_post_thumbnail(
                                'thumbnail',
                                array(
                                  'class' => 'center-block img-responsive',
                                  'alt'   => trim( strip_tags( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) ) ),
                                  ) );
							echo "</span></figure>";} ?>
							<meta  itemprop="image" content="<?php echo italystrap_thumb_url();?>"/>
					<p class="text-center"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" itemprop="url"><span itemprop="name"><strong><?php the_title(); ?></strong></span></a></p>
				</span>
      	<?php
    		endwhile;
    		echo '</div><hr>' . "\n";
    		 wp_reset_query();
  		}
  	  }
}


/**
 * Funzione per visualizzare i post correlati
 *
 * @link http://gabrieleromanato.com/2012/02/wordpress-visualizzare-i-post-correlati-senza-plugin/
 *
 * @package ItalyStrap\Controzzi
 */

 /**
  * Mostro i post correlati
  */
function controzzi_show_related_posts() {
  global $post;

  $tags = wp_get_post_tags( $post->ID );

  if ( $tags ) {

    $count = count( $tags );

    for ( $i = 0; $i < $count; $i++ ) {
      $first_tag[] = $tags[ $i ]->term_id;
    }


    $args = array(
      'tag__in'       => $first_tag,
      'post__not_in'      => array( $post->ID ),
      'showposts'       => 3,
      'ignore_sticky_posts' => 1,
      );

    $post_correlati = new WP_Query( $args );

    if ( $post_correlati->have_posts() ) {
      echo '<h4 class="entry-related-heading">' . __( 'Ti potrebbe interessare anche...', 'italystrap' ) .  '</h4>' . "\n";
      echo '<section class="row entry-related" itemscope itemtype="http://schema.org/Article">' . "\n";
      while ( $post_correlati->have_posts() ) :

        $post_correlati->the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-4' ); ?>>
        <?php
        if ( has_post_thumbnail() ) {

          $post_thumbnail_id = get_post_thumbnail_id();
          $image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );

          echo '<meta  itemprop="image" content="' . esc_attr( $image_attributes[0] ) . '"/>';
          echo "<figure><span class=''>";
          the_post_thumbnail(
            'related-article',
            array(
              'class' => 'center-block img-responsive',
              'alt'   => trim( strip_tags( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) ) ),
              ) );
              echo '</span></figure>';
         } else { ?>
          <figure>
            <img width="270" height="220" src="<?php echo ITALYSTRAP_CHILD_PATH; ?>/img/logo270x220.png" class="center-block wp-post-image img-responsive" alt="">
          </figure>
      <?php } ?>
        <h3 class="entry-related-title">
          <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" itemprop="url">
            <span itemprop="name">
              <strong>
                <?php the_title(); ?>
              </strong>
            </span>
          </a>
        </h3>
        <footer class="entry-footer">
          <span itemprop="author"><?php the_author_posts_link(); ?></span>
          <?php if ( comments_open() ) : ?>
             | <span><?php comments_number( __( 'No Responses', 'italystrap' ), __( 'One Response', 'italystrap' ), __( '% Responses', 'italystrap' ) ); ?></span>
          <?php endif ?>
        </footer>
        <span class="clearfix"></span>
        <hr class="hr-grey-dark">
        <p class="entry-related-content" itemprop="text"><?php echo esc_attr( wp_trim_words( get_the_content(), 20, '' ) ); ?></p>
        <a class="btn btn-front-page btn-lg-front-page pull-right" href="<?php the_permalink() ?>">Leggi Tutto</a>
        <span class="clearfix"></span>
      </article>
            <?php
            endwhile;
            echo '</section>' . "\n";

    }
  }
}

