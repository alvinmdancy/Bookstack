<?php $__env->startSection('body'); ?>

    <div class="container small">

        <div class="my-s">
            <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
                $book,
                $book->getUrl('/delete') => [
                    'text' => trans('entities.books_delete'),
                    'icon' => 'delete',
                ]
            ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>

        <div class="card content-wrap auto-height">
            <h1 class="list-heading"><?php echo e(trans('entities.books_delete')); ?></h1>
            <p><?php echo e(trans('entities.books_delete_explain', ['bookName' => $book->name])); ?></p>
            <p class="text-neg"><strong><?php echo e(trans('entities.books_delete_confirmation')); ?></strong></p>

            <form action="<?php echo e($book->getUrl()); ?>" method="POST" class="text-right">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="_method" value="DELETE">
                <a href="<?php echo e($book->getUrl()); ?>" class="button outline"><?php echo e(trans('common.cancel')); ?></a>
                <button type="submit" class="button"><?php echo e(trans('common.confirm')); ?></button>
            </form>
        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/books/delete.blade.php ENDPATH**/ ?>