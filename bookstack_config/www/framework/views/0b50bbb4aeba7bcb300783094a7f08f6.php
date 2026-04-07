<?php if($book->tags->count() > 0): ?>
    <div class="mb-xl">
        <?php echo $__themeViews->handleViewInclude('entities.tag-list', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/books/parts/show-sidebar-section-tags.blade.php ENDPATH**/ ?>