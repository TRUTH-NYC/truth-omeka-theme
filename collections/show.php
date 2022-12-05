<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
$description =  metadata('collection', array('Dublin Core', 'Description'));
$collection = get_current_record('collection', false);
$items = get_items_in_collection($collection);
// $items = get_loop_records('items');
?>

<?php echo head(array('title' => $description, 'bodyclass' => 'collections show')); ?>

<?php // echo '<h1>'. metadata('collection', 'rich_title', array('no_escape' => true)) . '</h1>'; ?>

<h1><?php echo $description; ?></h1>

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
                                    $thumbnail = file_display_url($item->getFile(0));
                                    $fullsize = file_display_url($item->getFile(0), 'fullsize');
                                    $image = '<img image-url 
                                    srcset="' . $thumbnail . ' 139w, '. $fullsize . ' 1000w" 
                                    sizes="(max-width: 530px) calc(100vw - 4%), (max-width: 918px) calc((100vw - 4% - 3em) / 2), (max-width: 1244px) calc((100vw - 4% - 6em) / 3)"
                                    src="'
                                    . $fullsize 
                                    . '" alt=" ' 
                                    . $itemTitle 
                                    . '">';
                                ?>
                                <a href="<?php echo record_url($item); ?>" data-gallery-links="<?php echo htmlentities(json_encode($rest_item_links)); ?>">
                                    <?php echo $image; ?>
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
