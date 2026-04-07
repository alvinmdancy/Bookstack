<div component="ajax-form"
     option:ajax-form:url="/attachments/<?php echo e($attachment->id); ?>"
     option:ajax-form:method="put"
     option:ajax-form:response-container="#edit-form-container"
     option:ajax-form:success-message="<?php echo e(trans('entities.attachments_updated_success')); ?>">
    <h5><?php echo e(trans('entities.attachments_edit_file')); ?></h5>

    <div class="form-group">
        <label for="attachment_edit_name"><?php echo e(trans('entities.attachments_edit_file_name')); ?></label>
        <input type="text" id="attachment_edit_name"
               name="attachment_edit_name"
               value="<?php echo e($attachment_edit_name ?? $attachment->name ?? ''); ?>"
               placeholder="<?php echo e(trans('entities.attachments_edit_file_name')); ?>">
        <?php if($errors->has('attachment_edit_name')): ?>
            <div class="text-neg text-small"><?php echo e($errors->first('attachment_edit_name')); ?></div>
        <?php endif; ?>
    </div>

    <div component="tabs" class="tab-container">
        <div class="nav-tabs" role="tablist">
            <button id="attachment-edit-file-tab"
                    type="button"
                    aria-controls="attachment-edit-file-panel"
                    aria-selected="<?php echo e($attachment->external ? 'false' : 'true'); ?>"
                    role="tab"><?php echo e(trans('entities.attachments_upload')); ?></button>
            <button id="attachment-edit-link-tab"
                    type="button"
                    aria-controls="attachment-edit-link-panel"
                    aria-selected="<?php echo e($attachment->external ? 'true' : 'false'); ?>"
                    role="tab"><?php echo e(trans('entities.attachments_set_link')); ?></button>
        </div>
        <div id="attachment-edit-file-panel"
             <?php if($attachment->external): ?> hidden <?php endif; ?>
             tabindex="0"
             role="tabpanel"
             aria-labelledby="attachment-edit-file-tab"
             class="mb-m">
            <?php echo $__themeViews->handleViewInclude('form.simple-dropzone', [
                'placeholder' => trans('entities.attachments_edit_drop_upload'),
                'url' =>  url('/attachments/upload/' . $attachment->id),
                'successMessage' => trans('entities.attachments_file_updated'),
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
        <div id="attachment-edit-link-panel"
             <?php if(!$attachment->external): ?> hidden <?php endif; ?>
             tabindex="0"
             role="tabpanel"
             aria-labelledby="attachment-edit-link-tab">
            <div class="form-group">
                <label for="attachment_edit_url"><?php echo e(trans('entities.attachments_link_url')); ?></label>
                <input type="text" id="attachment_edit_url"
                       name="attachment_edit_url"
                       value="<?php echo e($attachment_edit_url ?? ($attachment->external ? $attachment->path : '')); ?>"
                       placeholder="<?php echo e(trans('entities.attachment_link')); ?>">
                <?php if($errors->has('attachment_edit_url')): ?>
                    <div class="text-neg text-small"><?php echo e($errors->first('attachment_edit_url')); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <button component="event-emit-select"
            option:event-emit-select:name="edit-back"
            type="button"
            class="button outline"><?php echo e(trans('common.back')); ?></button>
    <button refs="ajax-form@submit" type="button" class="button"><?php echo e(trans('common.save')); ?></button>
</div><?php /**PATH /app/www/resources/views/attachments/manager-edit-form.blade.php ENDPATH**/ ?>