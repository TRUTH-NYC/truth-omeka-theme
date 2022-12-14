<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
$description =  metadata('collection', array('Dublin Core', 'Description'));
$collection = get_current_record('collection', false);
$items = get_items_in_collection($collection);
// $items = get_loop_records('items');

function array_find($xs, $f) {
    foreach ($xs as $x) {
      if (call_user_func($f, $x) === true)
        return $x;
    }
    return null;
  }
?>

<?php echo head(array('title' => $description, 'bodyclass' => 'collections show')); ?>

<?php // echo '<h1>'. metadata('collection', 'rich_title', array('no_escape' => true)) . '</h1>'; ?>

<h1><?php echo $description; ?></h1>

        <?php 
            $all_tags = array();
            $tags = array();
            $all_exhibits = array();

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

            array_walk($unique_tags, function($tag) use(&$all_exhibits) {
                $exhibitFound = item_getFirstFeaturedExhibit($tag->name);
                
                $existingExhibit = current(array_filter($all_exhibits, function($ex) use ($exhibitFound) { return $ex->slug == $exhibitFound->slug; }));
                if($existingExhibit) {
                    $subExhibitOfTag = $existingExhibit;
                } else {
                    $subExhibitOfTag = $exhibitFound;
                }

                if(!isset($subExhibitOfTag->uniqueTags)) {
                    $subExhibitOfTag->uniqueTags = array();
                }
                $subExhibitOfTag->uniqueTags = array_merge($subExhibitOfTag->uniqueTags, array($tag));
                $all_exhibits = array_merge($all_exhibits, array($subExhibitOfTag));
            });

            $all_exhibit_slugs = array_map(function ($e) { return $e->slug; }, $all_exhibits);
            $unique_exhibit_slugs = array_unique($all_exhibit_slugs);
        ?>    
    <?php 
    ?>
    <?php if ($totalItems > 0): ?>
        <?php foreach($unique_exhibit_slugs as $slug): ?>
            <?php 
                $exhibitOfUniqueTag = current(array_filter($all_exhibits, function($ex) use ($slug) { return $ex->slug == $slug; }));
                if(!$exhibitOfUniqueTag) {
                    continue;
                }
            ?>

            <a href="/exhibits/show/<?php echo $exhibitOfUniqueTag->slug; ?>"> <h2 style="margin-top: 2em"> <?php echo $exhibitOfUniqueTag->title; ?> </h2> </a>
            <?php if(get_theme_option('years_show_sub_exhibit_description')): ?>
            <p>
                <?php echo $exhibitOfUniqueTag->description; ?>
            </p>
            <?php endif; ?>
            <div id="collection-items">
            <?php foreach($exhibitOfUniqueTag->uniqueTags as $tag): ?>
                <div>
                    <h3> <?php echo $tag->name; ?> </h3>
                    <?php $index = 0; ?>
                    <?php foreach ($tag->items as $item): ?>
                        <?php $itemTitle = metadata($item, array('Dublin Core', 'Title')); ?>
                        <?php if($index == 0): ?>
                            <figure class="item hentry">
                                <p>
                                <?php echo metadata($item, array('Dublin Core', 'Description')); ?>
                                </p>
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
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p><?php echo __("There are currently no items within this collection."); ?></p>
    <?php endif; ?>
<!-- end collection-items -->


<?php echo foot(); ?>
