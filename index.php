<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'map browse')); ?>
<?php 
    queue_css_file('geolocation-items-map');
?>

<div class="homepage-content grid homepage-grid-2-cols">
<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <div class="homepage-paragraph">
        <?php echo $homepageText; ?>
    </div>
<?php endif; ?>
    <div class="homepage-video">
        <?php if (!empty(get_theme_option('homepage_video'))): ?>
            <link rel="stylesheet" href="https://unpkg.com/lite-youtube-embed@0.2.0/src/lite-yt-embed.css" />
            <script src="https://unpkg.com/lite-youtube-embed@0.2.0/src/lite-yt-embed.js"></script>
            <?php 
                $url = get_theme_option('homepage_video');
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[\w\-?&!#=,;]+/[\w\-?&!#=/,;]+/|(?:v|e(?:mbed)?)/|[\w\-?&!#=,;]*[?&]v=)|youtu\.be/)([\w-]{11})(?:[^\w-]|\Z)%i', $url, $match)) {
                    $video_id = $match[1];
                }
            ?>
            <figure>
            <lite-youtube videoid="<?php echo $video_id; ?>" playlabel="Homepage Video"></lite-youtube>
            <?php if (!empty(get_theme_option('homepage_video_caption'))): ?>
                <figcaption> <?php echo get_theme_option('homepage_video_caption'); ?></figcaption>
            <?php endif; ?>
            </figure>
        <?php else: ?>
            <?php echo get_homepage_video(); ?>
        <?php endif; ?>
    </div>
</div>

<?php echo get_view()->geolocationMapBrowse('map_browse', array('list' => 'map-links')); ?>
<div id="map-links" style="display:none"></div>

    <?php fire_plugin_hook('public_home', array('view' => $this)); ?>

</div>
<?php echo foot(); ?>
