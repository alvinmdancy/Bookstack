<?php if($page->attachments->count() > 0): ?>
    <div id="page-attachments" class="mb-l">
        <h5><?php echo e(trans('entities.pages_attachments')); ?></h5>
        <div class="body">
            <?php echo $__themeViews->handleViewInclude('attachments.list', ['attachments' => $page->attachments], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/pages/parts/show-sidebar-section-attachments.blade.php ENDPATH**/ ?>