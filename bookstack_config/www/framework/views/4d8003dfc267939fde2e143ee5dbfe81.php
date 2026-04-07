
<div component="dropzone"
     option:dropzone:url="<?php echo e($url); ?>"
     option:dropzone:success-message="<?php echo e($successMessage); ?>"
     option:dropzone:error-message="<?php echo e(trans('errors.attachment_upload_error')); ?>"
     option:dropzone:upload-limit="<?php echo e(config('app.upload_limit')); ?>"
     option:dropzone:upload-limit-message="<?php echo e(trans('errors.server_upload_limit')); ?>"
     option:dropzone:zone-text="<?php echo e(trans('entities.attachments_dropzone')); ?>"
     option:dropzone:file-accept="*"
     class="relative">
    <div refs="dropzone@status-area"></div>
    <button type="button"
            refs="dropzone@select-button dropzone@drop-target"
            class="dropzone-landing-area text-center">
        <?php echo e($placeholder); ?>

    </button>
</div><?php /**PATH /app/www/resources/views/form/simple-dropzone.blade.php ENDPATH**/ ?>