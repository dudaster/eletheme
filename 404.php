<?php get_header(); ?>
<section id="content" role="main">
	<?php do_action( 'eletheme_before_main_content' ); ?>

<?php 


		$page = get_page_by_title('[404]', OBJECT, 'elementor_library'); 
		if($page) echo get_eletheme($page->ID); else echo"<h1>Not found</h1>";


?>

<?php do_action( 'eletheme_after_main_content' ); ?>
</section>
<?php get_footer(); ?>