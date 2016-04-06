<?php
/**
 * Created by JetBrains PhpStorm.
 * User: robertspeer
 * Date: 4/5/16
 * Time: 5:14 PM
 * To change this template use File | Settings | File Templates.
 */
get_header(); ?>
<?php // Start the loop.
		while ( have_posts() ) : the_post(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="row">
            <header class="page-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header><!-- .page-header -->
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-2 aside-outer">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Question Types</a>
                </div>
                <div class="aside navbar navbar-default" role="navigation">
                    <?php wp_nav_menu( array( 'theme_location' => 'faq-menu', 'container_class' => 'collapse navbar-collapse navbar-ex1-collapse', 'menu_class'=>'nav navbar-nav' ) ); ?>
                </div><!-- /.aside -->
            </div><!--  /.col-lg-3 col-md-2 -->

            <div class="col-lg-9 col-md-9 has-full-width">
                <div class="dis">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', '' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', '' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );

                    if ( '' !== get_the_author_meta( 'description' ) ) {
                        get_template_part( 'template-parts/biography' );
                    }

                    // End of the loop.
                    endwhile;
                    ?>
                </div> <!-- dis -->
            </div> <!-- col-lg-9 col-md-9 has-full-width -->
        </div> <!-- .row -->
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>