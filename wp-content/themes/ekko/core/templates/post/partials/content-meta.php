<?php
/**
 * The template used for displaying meta information for single Blog posts
 */
 ?>

 <div class="entry-meta">
   <?php  if ( is_sticky() ) echo '<span class="fas fa-thumbtack"></span> Sticky <span class="blog-separator">|</span>  '; ?>
   <span class="published"><span class="far fa-clock"></span><?php the_time( get_option('date_format') ); ?></span>
   <span class="author"><span class="far fa-keyboard"></span><?php the_author_posts_link(); ?></span>
   <span class="blog-label"><span class="far fa-folder-open"></span><?php the_category(', '); ?></span>
 </div>
