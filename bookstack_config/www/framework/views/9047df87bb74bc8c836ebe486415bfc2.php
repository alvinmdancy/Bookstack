<?php
/** @var \BookStack\Permissions\PermissionFormData $data */
?>
<form component="entity-permissions"
      option:entity-permissions:entity-type="<?php echo e($model->getType()); ?>"
      action="<?php echo e($model->getUrl('/permissions')); ?>"
      method="POST">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="_method" value="PUT">

    <div class="grid half left-focus v-end gap-m wrap">
        <div>
            <h1 class="list-heading"><?php echo e($title); ?></h1>
            <p class="text-muted mb-s">
                <?php echo e(trans('entities.permissions_desc')); ?>


                <?php if($model instanceof \BookStack\Entities\Models\Book): ?>
                    <br> <?php echo e(trans('entities.permissions_book_cascade')); ?>

                <?php elseif($model instanceof \BookStack\Entities\Models\Chapter): ?>
                    <br> <?php echo e(trans('entities.permissions_chapter_cascade')); ?>

                <?php endif; ?>
            </p>

            <?php if($model instanceof \BookStack\Entities\Models\Bookshelf): ?>
                <p class="text-warn"><?php echo e(trans('entities.shelves_permissions_cascade_warning')); ?></p>
            <?php endif; ?>
        </div>
        <div class="flex-container-row justify-flex-end">
            <div class="form-group mb-m">
                <label for="owner"><?php echo e(trans('entities.permissions_owner')); ?></label>
                <?php echo $__themeViews->handleViewInclude('form.user-select', ['user' => $model->ownedBy, 'name' => 'owned_by'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
        </div>
    </div>

    <hr>

    <div refs="entity-permissions@role-container" class="item-list mt-m mb-m">
        <?php $__currentLoopData = $data->permissionsWithRoles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__themeViews->handleViewInclude('form.entity-permissions-row', [
                'permission' => $permission,
                'role' => $permission->role,
                'entityType' => $model->getType(),
                'inheriting' => false,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="flex-container-row justify-flex-end mb-xl">
        <div class="flex-container-row items-center gap-m">
            <label for="role_select" class="m-none p-none"><span
                        class="bold"><?php echo e(trans('entities.permissions_role_override')); ?></span></label>
            <select name="role_select" id="role_select" refs="entity-permissions@role-select">
                <option value=""><?php echo e(trans('common.select')); ?></option>
                <?php $__currentLoopData = $data->rolesNotAssigned(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->display_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <div class="item-list mt-m mb-xl">
        <?php echo $__themeViews->handleViewInclude('form.entity-permissions-row', [
                'role' => $data->everyoneElseRole(),
                'permission' => $data->everyoneElseEntityPermission(),
                'entityType' => $model->getType(),
                'inheriting' => !$model->permissions()->where('role_id', '=', 0)->exists(),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>

    <hr class="mb-m">

    <div class="flex-container-row justify-space-between gap-m wrap">
        <div class="flex min-width-m">
            <?php if($model instanceof \BookStack\Entities\Models\Bookshelf): ?>
                <p class="small text-muted mb-none">
                    * <?php echo e(trans('entities.shelves_permissions_create')); ?>

                </p>
            <?php endif; ?>
        </div>
        <div class="text-right">
            <a href="<?php echo e($model->getUrl()); ?>" class="button outline"><?php echo e(trans('common.cancel')); ?></a>
            <button type="submit" class="button"><?php echo e(trans('entities.permissions_save')); ?></button>
        </div>
    </div>
</form><?php /**PATH /app/www/resources/views/form/entity-permissions.blade.php ENDPATH**/ ?>