<div class="actions mb-xl">
    <h5><?php echo e(trans('common.actions')); ?></h5>
    <div class="icon-list text-link">

        <?php if(userCan(\BookStack\Permissions\Permission::PageCreate, $book)): ?>
            <a href="<?php echo e($book->getUrl('/create-page')); ?>" data-shortcut="new" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('add'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.pages_new')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::ChapterCreate, $book)): ?>
            <a href="<?php echo e($book->getUrl('/create-chapter')); ?>" data-shortcut="new" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('add'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.chapters_new')); ?></span>
            </a>
        <?php endif; ?>

        <hr class="primary-background">

        <?php if(userCan(\BookStack\Permissions\Permission::BookUpdate, $book)): ?>
            <a href="<?php echo e($book->getUrl('/edit')); ?>" data-shortcut="edit" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('edit'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.edit')); ?></span>
            </a>
            <a href="<?php echo e($book->getUrl('/sort')); ?>" data-shortcut="sort" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('sort'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.sort')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::BookCreateAll)): ?>
            <a href="<?php echo e($book->getUrl('/copy')); ?>" data-shortcut="copy" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('copy'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.copy')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $book)): ?>
            <a href="<?php echo e($book->getUrl('/permissions')); ?>" data-shortcut="permissions" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?></span>
                <span><?php echo e(trans('entities.permissions')); ?></span>
            </a>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::BookDelete, $book)): ?>
            <a href="<?php echo e($book->getUrl('/delete')); ?>" data-shortcut="delete" class="icon-list-item">
                <span><?php echo (new \BookStack\Util\SvgIcon('delete'))->toHtml(); ?></span>
                <span><?php echo e(trans('common.delete')); ?></span>
            </a>
        <?php endif; ?>

        <hr class="primary-background">

        <?php if($watchOptions->canWatch() && !$watchOptions->isWatching()): ?>
            <?php echo $__themeViews->handleViewInclude('entities.watch-action', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
        <?php if(!user()->isGuest()): ?>
            <?php echo $__themeViews->handleViewInclude('entities.favourite-action', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
        <?php if(userCan(\BookStack\Permissions\Permission::ContentExport)): ?>
            <?php echo $__themeViews->handleViewInclude('entities.export-menu', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endif; ?>
    </div>
</div><?php /**PATH /app/www/resources/views/books/parts/show-sidebar-section-actions.blade.php ENDPATH**/ ?>