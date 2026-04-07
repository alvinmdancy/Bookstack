<div class="item-list-row flex-container-row items-center wrap">
    <div class="flex py-s px-m min-width-s">
        <strong><?php echo e($title); ?></strong> <br>
        <a href="#" refs="permissions-table@toggle-row" class="text-small text-link"><?php echo e(trans('common.toggle_all')); ?></a>
    </div>
    <div class="flex py-s px-m min-width-xxs">
        <small class="hide-over-m bold"><?php echo e(trans('common.create')); ?><br></small>
        <?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => $permissionPrefix . '-create-all', 'label' => ''], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php if($permissionPrefix === 'comment'): ?><sup class="text-muted">2</sup><?php endif; ?>
    </div>
    <div class="flex py-s px-m min-width-xxs">
        <small class="hide-over-m bold"><?php echo e(trans('common.view')); ?><br></small>
        <small class="faded"><?php echo e(trans('settings.role_controlled_by_asset')); ?><?php if($permissionPrefix === 'image'): ?><sup class="text-muted">1</sup><?php endif; ?></small>
    </div>
    <div class="flex py-s px-m min-width-xxs">
        <small class="hide-over-m bold"><?php echo e(trans('common.edit')); ?><br></small>
        <?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => $permissionPrefix . '-update-own', 'label' => trans('settings.role_own')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php if($permissionPrefix === 'comment'): ?><sup class="text-muted">2</sup><?php endif; ?>
        <br>
        <?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => $permissionPrefix . '-update-all', 'label' => trans('settings.role_all')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php if($permissionPrefix === 'comment'): ?><sup class="text-muted">2</sup><?php endif; ?>
    </div>
    <div class="flex py-s px-m min-width-xxs">
        <small class="hide-over-m bold"><?php echo e(trans('common.delete')); ?><br></small>
        <?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => $permissionPrefix . '-delete-own', 'label' => trans('settings.role_own')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <br>
        <?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => $permissionPrefix . '-delete-all', 'label' => trans('settings.role_all')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
</div><?php /**PATH /app/www/resources/views/settings/roles/parts/related-asset-permissions-row.blade.php ENDPATH**/ ?>