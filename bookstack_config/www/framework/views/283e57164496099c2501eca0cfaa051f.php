<?php if($page->tags->count() > 0): ?>
    <section>
        <?php echo $__themeViews->handleViewInclude('entities.tag-list', ['entity' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </section>
<?php endif; ?><?php /**PATH /app/www/resources/views/pages/parts/show-sidebar-section-tags.blade.php ENDPATH**/ ?>