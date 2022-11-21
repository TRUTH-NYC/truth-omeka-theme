<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
$collection = get_current_record('collection', false);
$items = get_items_in_collection($collection);
// $items = get_loop_records('items');

function dump($data, $title="", $background="#EEEEEE", $color="#000000"){

    //=== Style  
    echo "  
    <style>
        /* Styling pre tag */
        pre {
            padding:10px 20px;
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;
        }

        /* ===========================
        == To use with XDEBUG 
        =========================== */
        /* Source file */
        pre small:nth-child(1) {
            font-weight: bold;
            font-size: 14px;
            color: #CC0000;
        }
        pre small:nth-child(1)::after {
            content: '';
            position: relative;
            width: 100%;
            height: 20px;
            left: 0;
            display: block;
            clear: both;
        }

        /* Separator */
        pre i::after{
            content: '';
            position: relative;
            width: 100%;
            height: 15px;
            left: 0;
            display: block;
            clear: both;
            border-bottom: 1px solid grey;
        }  
    </style>
    ";

    //=== Content            
    echo "<pre style='background:$background; color:$color; padding:10px 20px; border:2px inset $color'>";
    echo    "<h2>$title</h2>";
            var_dump($data); 
    echo "</pre>";

}
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<h1><?php echo metadata('collection', 'rich_title', array('no_escape' => true)); ?></h1>

<p><?php echo metadata('collection', array('Dublin Core', 'Description')); ?></p>

<div id="collection-items">
        <?php 

            $all_tags = array();
            $tags = array();

            array_walk($items, function($item) use(&$all_tags, &$tags) {
                $tags = array_merge($tags, $item->Tags);
                $_tags = array_map(function($tag) { return $tag['name']; }, $item['Tags']);
                $all_tags = array_merge($all_tags, $tags);  
            });

            $unique_tags = array_unique($all_tags);

            $unique_tags = array_filter($unique_tags, function ($tag) {
                return preg_match('/^\s*III(\\.\\d)+/', $tag);
            });

            $unique_tags = array_map(function ($tag) use ($items) {
                $_tag = (object)[];
                $_tag->name = $tag;
                $_tag->items = array_filter($items, function($item) use ($tag) {

                    $tag_names = array_map(function ($t) { return $t->name; }, $item->Tags);

                    $search_result = array_search($tag, $tag_names, false);
                    return $search_result;
                });
                return $_tag;
            }, $unique_tags);
        ?>    
    
    <?php if ($totalItems > 0): ?>
        <?php foreach($unique_tags as $tag): ?>
            <div> <h3> <?php echo $tag->name; ?> </h3>
                <?php $index = 0; ?>
                <?php foreach ($tag->items as $item): ?>
                    <?php $itemTitle = metadata($item, array('Dublin Core', 'Title')); ?>
                    <?php if($index == 0): ?>
                        <figure class="item hentry">
                            <div class="item-img">
                                <?php 
                                    $rest_item_links = array_values(array_map(function($i) { return record_url($i); }, $tag->items));
                                ?>
                                <a href="<?php echo record_url($item); ?>" data-gallery-links="<?php echo htmlentities(json_encode($rest_item_links)); ?>">
                                    <?php echo record_image($item, 'thumbnail', array('alt' => $itemTitle)); ?>
                                </a>
                            </div>
                            <figcaption> <?php echo $itemTitle; ?> </figcaption>
                        </figure>
                    <?php endif; ?>
                    <?php $index += 1; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p><?php echo __("There are currently no items within this collection."); ?></p>
    <?php endif; ?>
</div><!-- end collection-items -->


<?php echo foot(); ?>
