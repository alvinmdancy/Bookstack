<div id="page-details" class="entity-details mb-xl">
    <h5><?php echo e(trans('common.details')); ?></h5>
    <div class="blended-links">
        <?php echo $__themeViews->handleViewInclude('entities.meta', ['entity' => $page, 'watchOptions' => $watchOptions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

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

        <?php if($page->chapter && $page->chapter->hasPermissions()): ?>
            <div class="active-restriction">
                <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $page->chapter)): ?>
                    <a href="<?php echo e($page->chapter->getUrl('/permissions')); ?>" class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.chapters_permissions_active')); ?></div>
                    </a>
                <?php else: ?>
                    <div class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.chapters_permissions_active')); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($page->hasPermissions()): ?>
            <div class="active-restriction">
                <?php if(userCan(\BookStack\Permissions\Permission::RestrictionsManage, $page)): ?>
                    <a href="<?php echo e($page->getUrl('/permissions')); ?>" class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.pages_permissions_active')); ?></div>
                    </a>
                <?php else: ?>
                    <div class="entity-meta-item">
                        <?php echo (new \BookStack\Util\SvgIcon('lock'))->toHtml(); ?>
                        <div><?php echo e(trans('entities.pages_permissions_active')); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($page->template): ?>
            <div class="entity-meta-item">
                <?php echo (new \BookStack\Util\SvgIcon('template'))->toHtml(); ?>
                <div><?php echo e(trans('entities.pages_is_template')); ?></div>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH /app/www/resources/views/pages/parts/show-sidebar-section-details.blade.php ENDPATH**/ ?>