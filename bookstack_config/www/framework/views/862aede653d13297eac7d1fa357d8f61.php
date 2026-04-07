<div class="mb-xl">
    <h5><?php echo e(trans('common.details')); ?></h5>
    <div class="blended-links">
        <?php echo $__themeViews->handleViewInclude('entities.meta', ['entity' => $book, 'watchOptions' => $watchOptions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php if($book->hasPermissions()): ?>
            <div class="active-restriction">
                <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $book)): ?>
                    <a href="<?php echo e($book->getUrl('/permissions')); ?>" class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.books_permissions_active')); ?></div>
                    </a>
                <?php else: ?>
                    <div class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.books_permissions_active')); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH /app/www/resources/views/books/parts/show-sidebar-section-details.blade.php ENDPATH**/ ?>