<?php
$collectionTitle = metadata('collection', 'display_title');
$totalItems = metadata('collection', 'total_items');
?>

<?php echo head(array('title' => $collectionTitle, 'bodyclass' => 'collections show')); ?>

<div class="years-slider-container">
    <div class="arrow prev" onclick="yearsSlider.prevSlide()"></div>
    <div class="years-slider" >
        <?php foreach (get_year_collections() as $collection): ?>
            <h2><?php echo link_to($collection, null, metadata($collection, array('Dublin Core', 'Title'))); ?></h2>
        <?php endforeach; ?>
    </div>
    <div class="arrow next" onclick="yearsSlider.nextSlide()"></div>
</div>

<h1><?php echo metadata('collection', 'rich_title', array('no_escape' => true)); ?></h1>

<p><?php echo metadata('collection', array('Dublin Core', 'Description')); ?></p>


<div id="collection-items">
    <?php if ($totalItems > 0): ?>
        <?php foreach (loop('items') as $item): ?>
        <?php $itemTitle = metadata('item', 'display_title'); ?>
        <div class="item hentry">
            <?php if (metadata('item', 'has thumbnail')): ?>
            <div class="item-img">
                <?php echo link_to_item(item_image(null, array('alt' => $itemTitle))); ?>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
        <!-- <?php echo link_to_items_browse(__(plural('View item', 'View all %s items', $totalItems), $totalItems), array('collection' => metadata('collection', 'id')), array('class' => 'view-items-link')); ?> -->
    <?php else: ?>
        <p><?php echo __("There are currently no items within this collection."); ?></p>
    <?php endif; ?>
</div><!-- end collection-items -->


<?php echo foot(); ?>
