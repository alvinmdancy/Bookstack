<?php if(count($activity) > 0): ?>
    <div id="recent-activity" class="mb-xl">
        <h5><?php echo e(trans('entities.recent_activity')); ?></h5>
        <?php echo $__themeViews->handleViewInclude('common.activity-list', ['activity' => $activity], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/shelves/parts/show-sidebar-section-activity.blade.php ENDPATH**/ ?>