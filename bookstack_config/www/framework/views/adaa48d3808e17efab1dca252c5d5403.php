<div id="actions" class="actions mb-xl">
    <h5><?php echo e(trans('common.actions')); ?></h5>

    <div class="icon-list text-link">

        
        <?php if(userCan(\BookStack\Permissions\Permission::PageUpdate, $page)): ?>
            <a href="<?php echo e($page->getUrl('/edit')); ?>" data-shortcut="edit" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('edit'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.edit')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::PageCreateAll) || userCan(\BookStack\Permissions\Permission::PageCreateOwn) || userCanOnAny(\BookStack\Permissions\Permission::Create, \BookStack\Entities\Models\Book::class) || userCanOnAny(\BookStack\Permissions\Permission::Create, \BookStack\Entities\Models\Chapter::class)): ?>
            <a href="<?php echo e($page->getUrl('/copy')); ?>" data-shortcut="copy" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('copy'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.copy')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::PageUpdate, $page)): ?>
            <?php if(userCan(\BookStack\Permissions\Permission::PageDelete, $page)): ?>
                <a href="<?php echo e($page->getUrl('/move')); ?>" data-shortcut="move" class="icon-list-item">
                    <span><?php echo (new \BookStack\Util\SvgIcon('folder'))->toHtml(); ?></span>
                    <span><?php echo e(trans('common.move')); ?></span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="<?php echo e($page->getUrl('/revisions')); ?>" data-shortcut="revisions" class="icon-list-item">
            <span><?php echo (new \BookStack\Util\SvgIcon('history'))->toHtml(); ?></span>
            <span><?php echo e(trans('entities.revisions')); ?></span>
        </a>
        <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $page)): ?>
            <a href="<?php echo e($page->getUrl('/permissions')); ?>" data-shortcut="permissions" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.permissions')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::PageDelete, $page)): ?>
            <a href="<?php echo e($page->getUrl('/delete')); ?>" data-shortcut="delete" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('delete'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.delete')); ?></span>
            </a>
        <?php endif; ?>

        <hr class="primary-background"/>

        <?php if($watchOptions->canWatch() && !$watchOptions->isWatching()): ?>
            <?php echo $__themeViews->handleViewInclude('entities.watch-action', ['entity' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
        <?php if(!user()->isGuest()): ?>
            <?php echo $__themeViews->handleViewInclude('entities.favourite-action', ['entity' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::ContentExport)): ?>
            <?php echo $__themeViews->handleViewInclude('entities.export-menu', ['entity' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
    </div>

</div><?php /**PATH /app/www/resources/views/pages/parts/show-sidebar-section-actions.blade.php ENDPATH**/ ?>