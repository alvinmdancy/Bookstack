<?php $__env->startSection('body'); ?>

    <div class="container small">

        <div class="my-s">
            <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
                $page->book,
                $page->chapter,
                $page,
                $page->getUrl('/delete') => [
                    'text' => trans('entities.pages_delete'),
                    'icon' => 'delete',
                ]
            ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading"><?php echo e($page->draft ? trans('entities.pages_delete_draft') : trans('entities.pages_delete')); ?></h1>

            <?php if($usedAsTemplate): ?>
                <p class="text-warn"><?php echo e(trans('entities.pages_delete_warning_template')); ?></p>
            <?php endif; ?>

            <div class="grid half v-center">
                <div>
                    <p class="text-neg">
                        <strong>
                            <?php echo e($page->draft ? trans('entities.pages_delete_draft_confirm'): trans('entities.pages_delete_confirm')); ?>

                        </strong>
                    </p>
                </div>
                <div>
                    <form action="<?php echo e($page->getUrl()); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="_method" value="DELETE">
                        <div class="form-group text-right">
                            <a href="<?php echo e($page->getUrl()); ?>" class="button outline"><?php echo e(trans('common.cancel')); ?></a>
                            <button type="submit" class="button"><?php echo e(trans('common.confirm')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/pages/delete.blade.php ENDPATH**/ ?>