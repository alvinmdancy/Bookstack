<?php $__env->startSection('body'); ?>
    <?php echo $__themeViews->handleViewInclude('shelves.parts.list', ['shelves' => $shelves, 'view' => $view], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('left'); ?>
    <?php echo $__themeViews->handleViewInclude('home.parts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('right'); ?>
    <div class="actions mb-xl">
        <h5><?php echo e(trans('common.actions')); ?></h5>
        <div class="icon-list text-link">
            <?php if(userCan(\BookStack\Permissions\Permission::BookshelfCreateAll)): ?>
                <a href="<?php echo e(url("/create-shelf")); ?>" class="icon-list-item">
                    <span><?php echo (new \BookStack\Util\SvgIcon('add'))->toHtml(); ?></span>
                    <span><?php echo e(trans('entities.shelves_new_action')); ?></span>
                </a>
            <?php endif; ?>
            <?php echo $__themeViews->handleViewInclude('entities.view-toggle', ['view' => $view, 'type' => 'bookshelves'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <a href="<?php echo e(url('/tags')); ?>" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('tag'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.tags_view_tags')); ?></span>
            </a>
            <?php echo $__themeViews->handleViewInclude('home.parts.expand-toggle', ['classes' => 'text-link', 'target' => '.entity-list.compact .entity-item-snippet', 'key' => 'home-details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('common.dark-mode-toggle', ['classes' => 'icon-list-item text-link'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/home/shelves.blade.php ENDPATH**/ ?>