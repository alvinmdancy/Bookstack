<?php if($shelf->tags->count() > 0): ?>
    <div id="tags" class="mb-xl">
        <?php echo $__themeViews->handleViewInclude('entities.tag-list', ['entity' => $shelf], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/shelves/parts/show-sidebar-section-tags.blade.php ENDPATH**/ ?>