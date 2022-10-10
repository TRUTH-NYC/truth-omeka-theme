<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'map browse')); ?>
<?php 
    queue_css_file('geolocation-items-map');
?>
<div class="years-slider-container">
    <div class="arrow prev" onclick="yearsSlider.prevSlide()"></div>
    <div class="years-slider" >
        <?php foreach (get_year_collections() as $collection): ?>
            <h2><?php echo link_to($collection, null, metadata($collection, array('Dublin Core', 'Title'))); ?></h2>
        <?php endforeach; ?>
    </div>
    <div class="arrow next" onclick="yearsSlider.nextSlide()"></div>
</div>
<div class="homepage-content grid homepage-grid-2-cols">
<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <div class="homepage-paragraph">
        <?php echo $homepageText; ?>
    </div>
<?php endif; ?>
    <div class="homepage-video">
        <?php echo get_homepage_video(); ?>
    </div>
</div>

<?php echo get_view()->geolocationMapBrowse('map_browse', array('list' => 'map-links', 'params' => $params)); ?>
<div id="map-links" style="display:none"></div>

<!-- 
<div id="primary">
    <?php if (get_theme_option('Display Featured Item') == 1): ?>
    <!-- Featured Item 
    <div id="featured-item">
        <h2><?php echo __('Featured Item'); ?></h2>
        <?php echo random_featured_items(1); ?>
    </div><!--end featured-item 
    <?php endif; ?>
    <?php if (get_theme_option('Display Featured Collection')): ?>
    <!-- Featured Collection 
    <div id="featured-collection">
        <h2><?php echo __('Featured Collection'); ?></h2>
        <?php echo random_featured_collection(); ?>
    </div><!-- end featured collection 
    <?php endif; ?>	
    <?php if ((get_theme_option('Display Featured Exhibit')) && function_exists('exhibit_builder_display_random_featured_exhibit')): ?>
    <!-- Featured Exhibit 
    <?php echo exhibit_builder_display_random_featured_exhibit(); ?>
    <?php endif; ?>

</div>
<!-- end primary -->

<!-- 
<div id="secondary">
    <?php
    $recentItems = get_theme_option('Homepage Recent Items');
    if ($recentItems === null || $recentItems === ''):
        $recentItems = 3;
    else:
        $recentItems = (int) $recentItems;
    endif;
    if ($recentItems):
    ?>
    <div id="recent-items">
        <h2><?php echo __('Recently Added Items'); ?></h2>
        <?php echo recent_items($recentItems); ?>
        <p class="view-items-link"><a href="<?php echo html_escape(url('items')); ?>"><?php echo __('View All Items'); ?></a></p>
    </div>
    <?php endif; ?>

    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>

</div> -->
<?php echo foot(); ?>
