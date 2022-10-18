<!DOCTYPE html>
<html lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( $description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <?php echo auto_discovery_link_tags(); ?>

    <!-- Plugin Stuff -->

    <?php fire_plugin_hook('public_head', array('view'=>$this)); ?>


    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts','style'));
    queue_css_url('//fonts.googleapis.com/css?family=PT+Serif:400,700,400italic,700italic');
    echo head_css();

    echo theme_header_background();
    ?>

    <?php
    ($backgroundColor = get_theme_option('background_color')) || ($backgroundColor = "#FFFFFF");
    ($textColor = get_theme_option('text_color')) || ($textColor = "#444444");
    ($linkColor = get_theme_option('link_color')) || ($linkColor = "#888888");
    ($buttonColor = get_theme_option('button_color')) || ($buttonColor = "#000000");
    ($buttonTextColor = get_theme_option('button_text_color')) || ($buttonTextColor = "#FFFFFF");
    ($titleColor = get_theme_option('header_title_color')) || ($titleColor = "#000000");
    ?>
    <style>
        body {
            background-color: <?php echo $backgroundColor; ?>;
            color: <?php echo $textColor; ?>;
        }
        #site-title a:link, #site-title a:visited,
        #site-title a:active, #site-title a:hover {
            color: <?php echo $titleColor; ?>;
            <?php if (get_theme_option('header_background')): ?>
            text-shadow: 0px 0px 20px #000;
            <?php endif; ?>
        }
        a:link {
            color: <?php echo $linkColor; ?>;
        }
        /* a:visited {
            color: <?php echo $linkColor; ?>;
        }
        a:hover, a:active, a:focus {
            color: <?php echo $linkColor; ?>;
        }
        */

        .button, button,
        input[type="reset"],
        input[type="submit"],
        input[type="button"],
        .pagination_next a,
        .pagination_previous a {
          background-color: <?php echo $buttonColor; ?>;
          color: <?php echo $buttonTextColor; ?> !important;
        }

        #search-form input[type="text"] {
            border-color: <?php echo $buttonColor; ?>
        }

        @media (max-width:768px) {
            #primary-nav li {
                background-color: <?php echo $buttonColor; ?>;
            }

            #primary-nav li ul li {
                background-color: <?php echo $buttonColor; ?>;
            }

            #primary-nav li li li {
                background-color: <?php echo $buttonColor; ?>;
            }
        }

    </style>
    <!-- JavaScripts -->
    <?php
    queue_js_file('vendor/modernizr');
    queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)'));
    queue_js_file('vendor/respond');
    queue_js_file('vendor/jquery-accessibleMegaMenu');
    queue_js_file('globals');
    queue_js_file('default');
    echo head_js();
    ?>
</head>
<?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <a href="#content" id="skipnav"><?php echo __('Skip to main content'); ?></a>
    <?php fire_plugin_hook('public_body', array('view'=>$this)); ?>

        <header class="container" role="banner">
            <?php fire_plugin_hook('public_header', array('view'=>$this)); ?>
            <div logo-container>
                <div id="site-above-title"><?php echo get_theme_option('ab_logo'); ?></div>
                <div id="site-title"><?php echo link_to_home_page(get_theme_option('logo')); ?></div>
                <div id="site-sub-title"><?php echo get_theme_option('sub_logo'); ?></div>
            </div>

            <?php $exhibits = get_records('Exhibit'); ?>
            <div class="nav desktop">
                <?php foreach ($exhibits as $exhibit): ?>
                    <?php 
                     $isCurrent = false;
                     $curex = get_current_record('exhibit', false); 
                     if($curex) {
                         $isCurrent = $curex->id == $exhibit->id;
                     }
                    ?>
                    <h2 class="exhibit-nav desktop<?php echo $isCurrent ? ' current': ''?>"><?php echo link_to_exhibit(null, array(), null, $exhibit); ?></h2>
                <?php endforeach; ?>
            </div>
            <div class="mobile-nav">
                <input type="checkbox" id="mobile-nav-switch" style="display: none" hidden tabindex="-1" />
                <label for="mobile-nav-switch" class="mobile-nav-opener"> 
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 297 297" style="enable-background:new 0 0 297 297;" xml:space="preserve"><path d="M280.214,39.211H16.786C7.531,39.211,0,46.742,0,55.997v24.335c0,9.256,7.531,16.787,16.786,16.787h263.428     c9.255,0,16.786-7.531,16.786-16.787V55.997C297,46.742,289.469,39.211,280.214,39.211z"/><path d="M280.214,119.546H16.786C7.531,119.546,0,127.077,0,136.332v24.336c0,9.255,7.531,16.786,16.786,16.786h263.428     c9.255,0,16.786-7.531,16.786-16.786v-24.336C297,127.077,289.469,119.546,280.214,119.546z"/><path d="M280.214,199.881H16.786C7.531,199.881,0,207.411,0,216.668v24.335c0,9.255,7.531,16.786,16.786,16.786h263.428     c9.255,0,16.786-7.531,16.786-16.786v-24.335C297,207.411,289.469,199.881,280.214,199.881z"/></svg> 
                    <div class="mobile-nav-backdrop"></div>
                </label>
                <div class="mobile-nav-panel">
                    <?php foreach ($exhibits as $exhibit): ?>
                        <?php 
                        $isCurrent = false;
                        $curex = get_current_record('exhibit', false); 
                        if($curex) {
                            $isCurrent = $curex->id == $exhibit->id;
                        }
                        ?>
                        <h2 class="exhibit-nav mobile<?php echo $isCurrent ? ' current': ''?>"><?php echo link_to_exhibit(null, array(), null, $exhibit); ?></h2>
                    <?php endforeach; ?>                
                </div>
            </div>
        </header>
        
        <div class="container">
                        
            <div id="content" role="main" tabindex="-1">
                <?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
