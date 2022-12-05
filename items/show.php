<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'item show')); ?>

<?php $itemTitle = metadata('item', 'display_title'); ?>

<div class="item hentry">
    <?php if (metadata('item', 'has thumbnail')): ?>
    <figure class="item-img">
        <?php echo link_to_item(item_image('fullsize', array('alt' => $itemTitle))); ?>
        <?php 
            echo '<figcaption>';
                echo metadata('item', array('Dublin Core', 'Source'));
            echo '</figcaption>';    
        ?>
    </figure>
    <?php endif; ?>

    <?php 
        // we need to use inline style here because modal uses a isolated css context. 
    ?>
    <div class="item-content">
        <h1 style="display: none"><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h1>

        <?php if ($description = metadata('item', array('Dublin Core', 'Description'), array())): ?>
        <div class="item-description">
            <?php echo $description; ?>
        </div>
        <?php endif; ?>

        <?php if ($relation = metadata('item', array('Dublin Core', 'Relation'), array())): ?>
        <div class="item-relation">
            <?php echo $relation; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php echo foot(); ?>
