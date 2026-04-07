<div class="setting-list">

    <div class="grid half">
        <div>
            <label class="setting-list-label"><?php echo e(trans('settings.role_details')); ?></label>
        </div>
        <div>
            <div class="form-group">
                <label for="display_name"><?php echo e(trans('settings.role_name')); ?></label>
                <?php echo $__themeViews->handleViewInclude('form.text', ['name' => 'display_name', 'model' => $role], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
            <div class="form-group">
                <label for="description"><?php echo e(trans('settings.role_desc')); ?></label>
                <?php echo $__themeViews->handleViewInclude('form.text', ['name' => 'description', 'model' => $role], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
            <div class="form-group">
                <?php echo $__themeViews->handleViewInclude('form.checkbox', ['name' => 'mfa_enforced', 'label' => trans('settings.role_mfa_enforced'), 'model' => $role ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>

            <?php if(in_array(config('auth.method'), ['ldap', 'saml2', 'oidc'])): ?>
                <div class="form-group">
                    <label for="name"><?php echo e(trans('settings.role_external_auth_id')); ?></label>
                    <?php echo $__themeViews->handleViewInclude('form.text', ['name' => 'external_auth_id', 'model' => $role], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div component="permissions-table">
        <label class="setting-list-label"><?php echo e(trans('settings.role_system')); ?></label>
        <a href="#" refs="permissions-table@toggle-all" class="text-small text-link"><?php echo e(trans('common.toggle_all')); ?></a>

        <div class="toggle-switch-list grid half my-m">
            <div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'restrictions-manage-all', 'label' => trans('settings.role_manage_entity_permissions')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?> <sup>1</sup></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'restrictions-manage-own', 'label' => trans('settings.role_manage_own_entity_permissions')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?> <sup>1</sup></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'templates-manage', 'label' => trans('settings.role_manage_page_templates')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'access-api', 'label' => trans('settings.role_access_api')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'content-export', 'label' => trans('settings.role_export_content')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'content-import', 'label' => trans('settings.role_import_content')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'editor-change', 'label' => trans('settings.role_editor_change')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'receive-notifications', 'label' => trans('settings.role_notifications')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
            </div>
            <div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'settings-manage', 'label' => trans('settings.role_manage_settings')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'users-manage', 'label' => trans('settings.role_manage_users')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <div><?php echo $__themeViews->handleViewInclude('settings.roles.parts.checkbox', ['permission' => 'user-roles-manage', 'label' => trans('settings.role_manage_roles')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?></div>
                <p class="text-warn text-small mt-s mb-none"><?php echo e(trans('settings.roles_system_warning')); ?></p>
            </div>
        </div>

        <p class="mb-none text-small text-muted">
            <sup>1</sup> <?php echo e(trans('settings.role_permission_note_users_and_roles')); ?>

        </p>
    </div>

    <div>
        <label class="setting-list-label"><?php echo e(trans('settings.role_asset')); ?></label>
        <p><?php echo e(trans('settings.role_asset_desc')); ?></p>

        <?php if(isset($role) && $role->system_name === 'admin'): ?>
            <p class="text-warn"><?php echo e(trans('settings.role_asset_admins')); ?></p>
        <?php endif; ?>

        <div component="permissions-table"
             option:permissions-table:cell-selector=".item-list-row > div"
             option:permissions-table:row-selector=".item-list-row"
             class="item-list toggle-switch-list">
            <div class="item-list-row flex-container-row items-center hide-under-m bold">
                <div class="flex py-s px-m min-width-s">
                    <a href="#" refs="permissions-table@toggle-all" class="text-small text-link"><?php echo e(trans('common.toggle_all')); ?></a>
                </div>
                <div refs="permissions-table@toggle-column" class="flex py-s px-m min-width-xxs"><?php echo e(trans('common.create')); ?></div>
                <div refs="permissions-table@toggle-column" class="flex py-s px-m min-width-xxs"><?php echo e(trans('common.view')); ?></div>
                <div refs="permissions-table@toggle-column" class="flex py-s px-m min-width-xxs"><?php echo e(trans('common.edit')); ?></div>
                <div refs="permissions-table@toggle-column" class="flex py-s px-m min-width-xxs"><?php echo e(trans('common.delete')); ?></div>
            </div>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.asset-permissions-row', ['title' => trans('entities.shelves'), 'permissionPrefix' => 'bookshelf'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.asset-permissions-row', ['title' => trans('entities.books'), 'permissionPrefix' => 'book'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.asset-permissions-row', ['title' => trans('entities.chapters'), 'permissionPrefix' => 'chapter'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.asset-permissions-row', ['title' => trans('entities.pages'), 'permissionPrefix' => 'page'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.related-asset-permissions-row', ['title' => trans('entities.images'), 'permissionPrefix' => 'image'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.related-asset-permissions-row', ['title' => trans('entities.attachments'), 'permissionPrefix' => 'attachment'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('settings.roles.parts.related-asset-permissions-row', ['title' => trans('entities.comments'), 'permissionPrefix' => 'comment'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>

        <div>
            <p class="text-muted text-small p-m">
                <sup>1</sup> <?php echo e(trans('settings.role_asset_image_view_note')); ?>

                <br>
                <sup>2</sup> <?php echo e(trans('settings.role_asset_users_note')); ?>

            </p>
        </div>
    </div>
</div><?php /**PATH /app/www/resources/views/settings/roles/parts/form.blade.php ENDPATH**/ ?>