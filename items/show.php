<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'item show')); ?>

<?php $itemTitle = metadata('item', 'display_title'); ?>

<div class="item hentry">
    <?php if (metadata('item', 'has thumbnail')): ?>
    <div class="item-img">
        <?php echo link_to_item(item_image(null, array('alt' => $itemTitle))); ?>
    </div>
    <?php endif; ?>

    <h1><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

    <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array('snippet' => 250))): ?>
    <div class="item-description">
        <?php echo $description; ?>
    </div>
    <?php endif; ?>
</div>

<p><?php echo metadata('item', array('Dublin Core', 'Description')); ?></p>

<?php echo foot(); ?>
