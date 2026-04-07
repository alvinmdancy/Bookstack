<?php echo e(csrf_field()); ?>

<div class="form-group title-input">
    <label for="name"><?php echo e(trans('common.name')); ?></label>
    <?php echo $__themeViews->handleViewInclude('form.text', ['name' => 'name', 'autofocus' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</div>

<div class="form-group description-input">
    <label for="description_html"><?php echo e(trans('common.description')); ?></label>
    <?php echo $__themeViews->handleViewInclude('form.description-html-input', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</div>

<div class="form-group collapsible" component="collapsible" id="logo-control">
    <button refs="collapsible@trigger" type="button" class="collapse-title text-link" aria-expanded="false">
        <label><?php echo e(trans('common.cover_image')); ?></label>
    </button>
    <div refs="collapsible@content" class="collapse-content">
        <p class="small"><?php echo e(trans('common.cover_image_description')); ?></p>

        <?php echo $__themeViews->handleViewInclude('form.image-picker', [
            'defaultImage' => url('/book_default_cover.png'),
            'currentImage' => (($model ?? null)?->coverInfo()->getUrl(440, 250, null) ?? url('/book_default_cover.png')),
            'name' => 'image',
            'imageClass' => 'cover'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
</div>

<div class="form-group collapsible" component="collapsible" id="tags-control">
    <button refs="collapsible@trigger" type="button" class="collapse-title text-link" aria-expanded="false">
        <label for="tag-manager"><?php echo e(trans('entities.book_tags')); ?></label>
    </button>
    <div refs="collapsible@content" class="collapse-content">
        <?php echo $__themeViews->handleViewInclude('entities.tag-manager', ['entity' => $book ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
</div>

<div class="form-group collapsible" component="collapsible" id="template-control">
    <button refs="collapsible@trigger" type="button" class="collapse-title text-link" aria-expanded="false">
        <label for="template-manager"><?php echo e(trans('entities.default_template')); ?></label>
    </button>
    <div refs="collapsible@content" class="collapse-content">
        <?php echo $__themeViews->handleViewInclude('entities.template-selector', ['entity' => $book ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>
</div>

<div class="form-group text-right">
    <a href="<?php echo e($returnLocation); ?>" class="button outline"><?php echo e(trans('common.cancel')); ?></a>
    <button type="submit" class="button"><?php echo e(trans('entities.books_save')); ?></button>
</div>

<?php echo $__themeViews->handleViewInclude('entities.selector-popup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php echo $__themeViews->handleViewInclude('form.editor-translations', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?><?php /**PATH /app/www/resources/views/books/parts/form.blade.php ENDPATH**/ ?>