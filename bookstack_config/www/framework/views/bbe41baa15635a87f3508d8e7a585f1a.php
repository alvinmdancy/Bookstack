<?php if(count($bookParentShelves) > 0): ?>
    <div class="actions mb-xl">
        <h5><?php echo e(trans('entities.shelves')); ?></h5>
        <?php echo $__themeViews->handleViewInclude('entities.list', ['entities' => $bookParentShelves, 'style' => 'compact'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/books/parts/show-sidebar-section-shelves.blade.php ENDPATH**/ ?>