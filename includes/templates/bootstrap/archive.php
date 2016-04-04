<?php
/**
 * A template for displaying lists of FAQ questions using the Bootstrap framework
 *
 * This is an example, intended for documentation and as a starting place for your own templates, and should not be used in production without modifications.
 *
 * Specifically refactor this code & your child theme to use wp_enqueue_script() & theme_enqueue_styles() to include
 * the bootstrap js & css files, then move the inline css to your own css file.  See https://codex.wordpress.org/Child_Themes for more information on child themes.
 *
 * @link https://github.com/rhspeer/PositionZeroFAQ
 *
 * @package Position Zero FAQ
 * @subpackage Bootstrap example template
 * @since Position Zero FAQ 1.0
 */

get_header(); ?>
<!-- Refactor CSS inclusion to use theme_enqueue_styles() -->
<!-- Bootstrap code -->
<!-- Latest 3/2016 compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo plugins_url(); ?>/PositionZeroFAQ/includes/templates/bootstrap/css/style.css">

    <div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <div class="row">
                <header class="page-header">
                    <?php
                    the_archive_title( '<h2 class="page-title">', '</h2>' );
                    the_archive_description( '<div class="taxonomy-description">', '</div>' );
                    ?>
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
                        <?php if ( have_posts() ) : ?>
                            <div class="panel-group" id="accordion" role="tablist" area-multiselectable="true">
                                <?php
                                $i = 0; // iterator
                                // Start the Loop.
                                while ( have_posts() ) : the_post();
                                ?>
                                    <div class="panel panel-default">

                                        <div class="panel-heading"
                                             role="tab"
                                             id="heading<?php echo $i?>">

                                            <h4 class="panel-title">
                                                <a data-toggle="collapse"
                                                   data-parent="#accordion"
                                                   href="#collapse<?php echo $i;?>"
                                                   aria-expanded="false"
                                                   aria-controls="collapse<?php echo $i;?>">
                                                    <?php the_title();?>
                                                </a>
                                            </h4>

                                        </div>

                                        <div id="collapse<?php echo $i;?>"
                                             class="panel-collapse collapse <?php if ($wp_query->current_post==0) { echo "in"; } // a class of opens the accordion?>"
                                             role="tabpanel"
                                             aria-labelledby="heading<?php echo $i ?>">

                                            <div class="panel-body">
                                                <?php the_excerpt();?>
                                                <a class="btn btn-primary" href="<?php the_permalink();?>" role="button">Read More</a>
                                            </div>
                                        </div>
                                    </div> <!-- /.panel -->

                                <?php
                                $i++; // increment iterator
                                // End the loop.
                                endwhile;
                                ?>
                            </div> <!-- .panel-group #accordion -->

                            <?php
                            // Previous/next page navigation.
                            the_posts_pagination( array(
                                'prev_text'          => __( 'Previous page', '' ),
                                'next_text'          => __( 'Next page', '' ),
                                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', '' ) . ' </span>',
                            ) );

                        // If no content, include the "No posts found" template.
                        else :
                            get_template_part( 'template-parts/content', 'none' );
                        endif;
                        ?>
                    </div> <!-- dis -->
                </div> <!-- col-lg-9 col-md-9 has-full-width -->
            </div> <!-- .row -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>

<!-- Bootstrap code, refactor to use wp_enqueue_scripts() in child theme -->
<!-- Latest (3/2016) compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
