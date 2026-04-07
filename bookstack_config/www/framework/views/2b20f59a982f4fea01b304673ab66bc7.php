<?php $__env->startSection('body'); ?>

    <div class="container">

        <div class="my-s">
            <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
                $book,
                $book->getUrl('/permissions') => [
                    'text' => trans('entities.books_permissions'),
                    'icon' => 'lock',
                ]
            ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>

        <main class="card content-wrap auto-height">
            <?php echo $__themeViews->handleViewInclude('form.entity-permissions', ['model' => $book, 'title' => trans('entities.books_permissions')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </main>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/books/permissions.blade.php ENDPATH**/ ?>