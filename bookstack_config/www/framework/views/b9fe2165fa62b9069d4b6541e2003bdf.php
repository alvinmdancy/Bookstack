<div id="actions" class="actions mb-xl">
    <h5><?php echo e(trans('common.actions')); ?></h5>
    <div class="icon-list text-link">

        <?php if(userCan(\BookStack\Permissions\Permission::BookCreateAll) && userCan(\BookStack\Permissions\Permission::BookshelfUpdate, $shelf)): ?>
            <a href="<?php echo e($shelf->getUrl('/create-book')); ?>" data-shortcut="new" class="icon-list-item">
                <span class="icon"><?php echo (new \BookStack\Util\SvgIcon('add'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.books_new_action')); ?></span>
            </a>
        <?php endif; ?>

        <?php echo $__themeViews->handleViewInclude('entities.view-toggle', ['view' => $view, 'type' => 'bookshelf'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

        <hr class="primary-background">

        <?php if(userCan(\BookStack\Permissions\Permission::BookshelfUpdate, $shelf)): ?>
            <a href="<?php echo e($shelf->getUrl('/edit')); ?>" data-shortcut="edit" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('edit'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.edit')); ?></span>
            </a>
        <?php endif; ?>

        <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $shelf)): ?>
            <a href="<?php echo e($shelf->getUrl('/permissions')); ?>" data-shortcut="permissions" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.permissions')); ?></span>
            </a>
        <?php endif; ?>

        <?php if(userCan(\BookStack\Permissions\Permission::BookshelfDelete, $shelf)): ?>
            <a href="<?php echo e($shelf->getUrl('/delete')); ?>" data-shortcut="delete" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('delete'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.delete')); ?></span>
            </a>
        <?php endif; ?>

        <?php if(!user()->isGuest()): ?>
            <hr class="primary-background">
            <?php echo $__themeViews->handleViewInclude('entities.favourite-action', ['entity' => $shelf], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>

    </div>
</div><?php /**PATH /app/www/resources/views/shelves/parts/show-sidebar-section-actions.blade.php ENDPATH**/ ?>