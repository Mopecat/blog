<article id="post-<?php the_ID(); ?>" class="block post">
	<span class="round-date teal">
				<span class="month"><?php the_time(n月) ?></span>
				<span class="day"><?php the_time(d) ?></span>
	</span>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'amativeness' ); ?>
		</div>
		<?php endif; ?>
		<header class="entry-header">
			<?php if ( is_single() ) : ?>
			<p class="title"><?php the_title(); ?></p>
			<?php else : ?>
			<p class="title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</p>
			<p class="article-meta">本文由 <?php the_author(); ?> 发表于 <?php the_time('Y年n月j日') ?> </p>
			<?php endif; // is_single() ?>
			<?php if ( comments_open() ) : ?>
			<div class="ui ribbon label teal"><?php the_category(',') ?></div>
			<?php endif; // comments_open() ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="article-content teal">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'amativeness' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'amativeness' ), 'after' => '</div>' ) ); ?>
		</div><!-- .article-content -->
		<?php endif; ?>

	</article><!-- #post -->
