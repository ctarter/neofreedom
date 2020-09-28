<?php
/**
 * Template Name: Home Right Sidebar Layout
 *
 * This template can be used to override the default template and sidebar setup
 *
 * @package casttools
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;get_header();
$shredder = new Shredder;
$container = get_theme_mod('casttools_container_type');
?>

<div class="wrapper" id="page-wrapper">

    <div class="<?php echo esc_attr($container); ?>" id="content">

        <div class="row">

            <div
                    class="<?php if (is_active_sidebar('right-sidebar')) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?> content-area"
                    id="primary">

                <main class="site-main" id="main" role="main">


                    <?php while (have_posts()) : the_post(); ?>
                    <?php
                        $categories = get_categories();
                        $catsplit = array();
                        $setsplit = array();
                        $catsetting = explode(',',get_theme_mod('casttools_featured_categories' ));
                        $set2='';
                        foreach($catsetting as $set){
                            $set = trim($set);
                            $set2 = get_cat_ID($set);
                            $setsplit[$set2]=$set;
                            $set_keys = array_keys($setsplit);
                        }
                        foreach ($categories as $key => $category) {
                            if(in_array($category->category_parent,$set_keys)){
                                $catsplit[$setsplit[$category->category_parent]][$category->name] = $category;
                            } else {
                                $catname = '';
                                $catname = (string)$category->name;
                                $catarr = json_decode(json_encode($category), true);
                                if (isset($catsplit[$catname])) {
                                    $catsplit[$catname] = array_merge($catsplit[$catname], $catarr);
                                }
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="list-group" id="list-tab" role="tablist">
                                    <?php
                                    $x = 1;
                                    foreach ($catsplit as $key => $cat) {
                                        $key3 = strtolower(str_replace(" ", "-", $key));
                                        if ($x == 0) {
                                            $d = ' active ';
                                            $x++;
                                        } else {
                                            $d = '';
                                        }
                                        print '<a class="list-group-item list-group-item-action ' . $d . '" id="list-' . $key3 . '-list" data-toggle="list" href="#' . $key3 . '" role="tab" aria-controls="' . $key3 . '">' . $key . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <div class="tab-content" id="nav-tabContent">
                                    <?php
                                    $i = 0;
                                    foreach ($catsplit as $key => $cat) {
                                        $key3 = strtolower(str_replace(" ", "-", $key));
                                        if ($i == 1) {
                                            $d = ' active ';
                                            $i++;
                                        } else {
                                            $d = '';
                                        }
                                        print '<div class="tab-pane fade show ' . $d . '" id="' . $key3 . '" role="tabpanel" aria-labelledby="list-' . $key3 . '-list">';
                                        print '       <div class="list-group">';
                                        foreach ($cat as $key2 => $cat2) {
                                            if (is_object($cat2)) {
                                                $category_link = get_category_link($cat2->term_id);
                                                print '  <a href="#" class="list-group-item list-group-item-action  modalopen"  data-whatever="' . esc_url($category_link) . '">' . $key2 . '</a>';
                                            }
                                        }
                                        print  '</div></div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="teachingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; // end of the loop. ?>

                </main><!-- #main -->

            </div><!-- #primary -->

            <?php $shredder->get_template_part('sidebar-templates/sidebar', 'right'); ?>

        </div><!-- .row -->

    </div><!-- #content -->

</div><!-- #page-wrapper -->

<?php get_footer(); ?>
