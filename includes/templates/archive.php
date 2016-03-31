<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

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



                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
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
                                                <?php the_content();?>
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
                                'prev_text'          => __( 'Previous page', 'twentysixteen' ),
                                'next_text'          => __( 'Next page', 'twentysixteen' ),
                                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
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

<!-- Bootstrap code -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <style>
        @media (min-width: 768px) {
            .navbar-collapse {
                height: auto;
                border-top: 0;
                box-shadow: none;
                max-height: none;
                padding-left:0;
                padding-right:0;
            }
            .navbar-collapse.collapse {
                display: block !important;
                width: auto !important;
                padding-bottom: 0;
                overflow: visible !important;
            }
            .navbar-collapse.in {
                overflow-x: visible;
            }

            .navbar
            {
                max-width:300px;
                margin-right: 0;
                margin-left: 0;
            }

            .navbar-nav,
            .navbar-nav > li,
            .navbar-left,
            .navbar-right,
            .navbar-header
            {float:none !important;}

            .navbar-right .dropdown-menu {left:0;right:auto;}
            .navbar-collapse .navbar-nav.navbar-right:last-child {
                margin-right: 0;
            }
            .col-lg-3.col-md-2.aside-outer {
                min-width: 185px;
            }
        }
    </style>