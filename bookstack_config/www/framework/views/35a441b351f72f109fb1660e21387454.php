<div id="markdown-editor" component="markdown-editor"
     option:markdown-editor:page-id="<?php echo e($model->id ?? 0); ?>"
     option:markdown-editor:text-direction="<?php echo e($locale->htmlDirection()); ?>"
     option:markdown-editor:image-upload-error-text="<?php echo e(trans('errors.image_upload_error')); ?>"
     option:markdown-editor:server-upload-limit-text="<?php echo e(trans('errors.server_upload_limit')); ?>"
     class="flex-fill flex code-fill">

    <div class="markdown-editor-wrap active flex-container-column">
        <div class="editor-toolbar flex-container-row items-stretch justify-space-between">
            <div class="editor-toolbar-label text-mono bold px-m py-xs flex-container-row items-center flex">
                <span><?php echo e(trans('entities.pages_md_editor')); ?></span>
            </div>
            <div component="dropdown" class="buttons flex-container-row items-stretch">
                <?php if(config('services.drawio')): ?>
                    <button class="text-button" type="button" data-action="insertDrawing" title="<?php echo e(trans('entities.pages_md_insert_drawing')); ?>"><?php echo (new \BookStack\Util\SvgIcon('drawing'))->toHtml(); ?></button>
                <?php endif; ?>
                <button class="text-button" type="button" data-action="insertImage" title="<?php echo e(trans('entities.pages_md_insert_image')); ?>"><?php echo (new \BookStack\Util\SvgIcon('image'))->toHtml(); ?></button>
                <button class="text-button" type="button" data-action="insertLink" title="<?php echo e(trans('entities.pages_md_insert_link')); ?>"><?php echo (new \BookStack\Util\SvgIcon('link'))->toHtml(); ?></button>
                <button class="text-button" type="button" data-action="fullscreen" title="<?php echo e(trans('common.fullscreen')); ?>"><?php echo (new \BookStack\Util\SvgIcon('fullscreen'))->toHtml(); ?></button>
                <button refs="dropdown@toggle" class="text-button" type="button" title="<?php echo e(trans('common.more')); ?>"><?php echo (new \BookStack\Util\SvgIcon('more'))->toHtml(); ?></button>
                <div refs="dropdown@menu markdown-editor@setting-container" class="dropdown-menu" role="menu">
                    <div class="px-m">
                        <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', ['name' => 'md-showPreview', 'label' => trans('entities.pages_md_show_preview'), 'value' => true, 'checked' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                    </div>
                    <hr class="m-none">
                    <div class="px-m">
                        <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', ['name' => 'md-scrollSync', 'label' => trans('entities.pages_md_sync_scroll'), 'value' => true, 'checked' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                    </div>
                    <hr class="m-none">
                    <div class="px-m">
                        <?php echo $__themeViews->handleViewInclude('form.custom-checkbox', ['name' => 'md-plainEditor', 'label' => trans('entities.pages_md_plain_editor'), 'value' => true, 'checked' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-fill" dir="ltr">
            <textarea id="markdown-editor-input"
                      refs="markdown-editor@input"
                      <?php if($errors->has('markdown')): ?> class="text-neg" <?php endif; ?>
                      name="markdown"
                      rows="5"><?php if(isset($model) || old('markdown')): ?><?php echo e(old('markdown') ?? ($model->markdown === '' ? $model->html : $model->markdown)); ?><?php endif; ?></textarea>
        </div>

    </div>

    <div refs="markdown-editor@display-wrap" class="markdown-editor-wrap flex-container-row items-stretch" style="display: none">
        <div refs="markdown-editor@divider" class="markdown-panel-divider flex-fill"></div>
        <div class="flex-container-column flex flex-fill">
            <div class="editor-toolbar">
                <div class="editor-toolbar-label text-mono bold px-m py-xs"><?php echo e(trans('entities.pages_md_preview')); ?></div>
            </div>
            <iframe src="about:blank"
                    refs="markdown-editor@display"
                    class="markdown-display flex flex-fill"
                    sandbox="allow-same-origin"></iframe>
        </div>
    </div>
</div>



<?php if($errors->has('markdown')): ?>
    <div class="text-neg text-small"><?php echo e($errors->first('markdown')); ?></div>
<?php endif; ?><?php /**PATH /app/www/resources/views/pages/parts/markdown-editor.blade.php ENDPATH**/ ?>