<?php if(count($draftPages) > 0): ?>
    <div id="recent-drafts" class="mb-xl">
        <h5><?php echo e(trans('entities.my_recent_drafts')); ?></h5>
        <?php echo $__themeViews->handleViewInclude('entities.list', ['entities' => $draftPages, 'style' => 'compact'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
<?php endif; ?>

<?php if(count($favourites) > 0): ?>
    <div id="top-favourites" class="mb-xl">
        <h5><?php echo e(trans('entities.my_most_viewed_favourites')); ?></h5>
        <?php echo $__themeViews->handleViewInclude('entities.list', [
            'entities' => $favourites,
            'style' => 'compact',
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <a href="<?php echo e(url('/favourites')); ?>" class="text-muted block py-xs"><?php echo e(trans('common.view_all')); ?></a>
    </div>
<?php endif; ?>

<div class="mb-xl">
    <h5><?php echo e(trans('entities.' . (auth()->check() ? 'my_recently_viewed' : 'books_recent'))); ?></h5>
    <?php echo $__themeViews->handleViewInclude('entities.list', [
        'entities' => $recents,
        'style' => 'compact',
        'emptyText' => auth()->check() ? trans('entities.no_pages_viewed') : trans('entities.books_empty')
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</div>

<div class="mb-xl">
    <h5><?php echo e(trans('entities.recently_updated_pages')); ?></h5>
    <div id="recently-updated-pages">
        <?php echo $__themeViews->handleViewInclude('entities.list', [
        'entities' => $recentlyUpdatedPages,
        'style' => 'compact',
        'emptyText' => trans('entities.no_pages_recently_updated')
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
    <a href="<?php echo e(url('/pages/recently-updated')); ?>" class="text-muted block py-xs"><?php echo e(trans('common.view_all')); ?></a>
</div>

<div id="recent-activity" class="mb-xl">
    <h5><?php echo e(trans('entities.recent_activity')); ?></h5>
    <?php echo $__themeViews->handleViewInclude('common.activity-list', ['activity' => $activity], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</div><?php /**PATH /app/www/resources/views/home/parts/sidebar.blade.php ENDPATH**/ ?>