<?php get_header(); ?>

	<main id="main" class="hfeed" role="main" itemscope itemtype="http://schema.org/ItemPage">

	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title" itemprop="name headline"><?php the_title(); ?></h1>
			</header>

			<div class="entry-content" itemprop="articleBody">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		</article>

		<?php get_template_part( 'ad', 'single' ); ?>
		<?php comments_template(); ?>
		
	<?php endwhile; ?>

	</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>