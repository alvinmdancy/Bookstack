

<div component="permissions-table" class="item-list-row flex-container-row justify-space-between wrap">
    <div class="gap-x-m flex-container-row items-center px-l py-m flex">
        <div class="text-large" title="<?php echo e($role->id === 0 ? trans('entities.permissions_role_everyone_else') : trans('common.role')); ?>">
            <?php echo (new \BookStack\Util\SvgIcon($role->id === 0 ? 'groups' : 'role'))->toHtml(); ?>
        </div>
        <span>
            <strong><?php echo e($role->display_name); ?></strong> <br>
            <small class="text-muted"><?php echo e($role->description); ?></small>
        </span>
        <?php if($role->id !== 0): ?>
            <button type="button"
                class="ml-auto flex-none text-small text-link text-button hover-underline item-list-row-toggle-all hide-under-s"
                refs="permissions-table@toggle-all"
                ><strong><?php echo e(trans('common.toggle_all')); ?></strong></button>
        <?php endif; ?>
    </div>
    <?php if($role->id === 0): ?>
        <div class="px-l flex-container-row items-center" refs="entity-permissions@everyone-inherit">
            <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', [
                'name' => 'entity-permissions-inherit',
                'label' => trans('entities.permissions_inherit_defaults'),
                'value' => 'true',
                'checked' => $inheriting
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
    <?php endif; ?>
    <div class="flex-container-row justify-space-between gap-x-xl wrap items-center">
        <input type="hidden" name="permissions[<?php echo e($role->id); ?>][active]"
               <?php if($inheriting): ?> disabled="disabled" <?php endif; ?>
               value="true">
        <div class="px-l">
            <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', [
                'name' =>  'permissions[' . $role->id . '][view]',
                'label' => trans('common.view'),
                'value' => 'true',
                'checked' => $permission->view,
                'disabled' => $inheriting
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
        <?php if($entityType !== 'page'): ?>
            <div class="px-l">
                <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', [
                    'name' =>  'permissions[' . $role->id . '][create]',
                    'label' => trans('common.create') . ($entityType === 'bookshelf' ? ' *'  : ''),
                    'value' => 'true',
                    'checked' => $permission->create,
                    'disabled' => $inheriting
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
        <?php endif; ?>
        <div class="px-l">
            <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', [
                'name' =>  'permissions[' . $role->id . '][update]',
                'label' => trans('common.update'),
                'value' => 'true',
                'checked' => $permission->update,
                'disabled' => $inheriting
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
        <div class="px-l">
            <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', [
                'name' =>  'permissions[' . $role->id . '][delete]',
                'label' => trans('common.delete'),
                'value' => 'true',
                'checked' => $permission->delete,
                'disabled' => $inheriting
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
    </div>
    <?php if($role->id !== 0): ?>
        <div class="flex-container-row items-center px-m py-s">
            <button type="button"
                    class="text-neg p-m icon-button"
                    data-role-id="<?php echo e($role->id); ?>"
                    data-role-name="<?php echo e($role->display_name); ?>"
                    title="<?php echo e(trans('common.remove')); ?>">
                <?php echo (new \BookStack\Util\SvgIcon('close'))->toHtml(); ?> <span class="hide-over-m ml-xs"><?php echo e(trans('common.remove')); ?></span>
            </button>
        </div>
    <?php endif; ?>
</div><?php /**PATH /app/www/resources/views/form/entity-permissions-row.blade.php ENDPATH**/ ?>