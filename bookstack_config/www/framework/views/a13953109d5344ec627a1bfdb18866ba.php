<?php
    /**
     * @var \BookStack\Entities\Models\Book $book
     */
?>
<a href="<?php echo e($book->getUrl()); ?>" class="book entity-list-item" data-entity-type="book" data-entity-id="<?php echo e($book->id); ?>">
    <div class="entity-list-item-image bg-book" style="background-image: url('<?php echo e($book->coverInfo()->getUrl()); ?>')">
        <?php echo (new \BookStack\Util\SvgIcon('book'))->toHtml(); ?>
    </div>
    <div class="content">
        <h4 class="entity-list-item-name break-text"><?php echo e($book->name); ?></h4>
        <div class="entity-item-snippet">
            <p class="text-muted break-text mb-s text-limit-lines-1"><?php echo e($book->descriptionInfo()->getPlain()); ?></p>
        </div>
    </div>
</a><?php /**PATH /app/www/resources/views/books/parts/list-item.blade.php ENDPATH**/ ?>