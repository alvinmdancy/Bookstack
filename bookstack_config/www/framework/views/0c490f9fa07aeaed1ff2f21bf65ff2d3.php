<div id="details" class="mb-xl">
    <h5><?php echo e(trans('common.details')); ?></h5>
    <div class="blended-links">
        <?php echo $__themeViews->handleViewInclude('entities.meta', ['entity' => $shelf, 'watchOptions' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php if($shelf->hasPermissions()): ?>
            <div class="active-restriction">
                <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $shelf)): ?>
                    <a href="<?php echo e($shelf->getUrl('/permissions')); ?>" class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.shelves_permissions_active')); ?></div>
                    </a>
                <?php else: ?>
                    <div class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.shelves_permissions_active')); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH /app/www/resources/views/shelves/parts/show-sidebar-section-details.blade.php ENDPATH**/ ?>