<?php $__env->startPush('social-meta'); ?>
    <meta property="og:description" content="<?php echo e(Str::limit($shelf->description, 100, '...')); ?>">
    <?php if($shelf->coverInfo()->exists()): ?>
        <meta property="og:image" content="<?php echo e($shelf->coverInfo()->getUrl()); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__themeViews->handleViewInclude('entities.body-tag-classes', ['entity' => $shelf], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php $__env->startSection('body'); ?>

    <div class="mb-s print-hidden">
        <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
            $shelf,
        ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>

    <main class="card content-wrap">

        <div class="flex-container-row wrap v-center">
            <h1 class="flex fit-content break-text"><?php echo e($shelf->name); ?></h1>
            <div class="flex"></div>
            <div class="flex fit-content text-m-right my-m ml-m">
                <?php echo $__themeViews->handleViewInclude('common.sort', $listOptions->getSortControlData(), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </div>
        </div>

        <div class="book-content">
            <div class="text-muted break-text"><?php echo $shelf->descriptionInfo()->getHtml(); ?></div>
            <?php if(count($sortedVisibleShelfBooks) > 0): ?>
                <?php if($view === 'list'): ?>
                    <div class="entity-list">
                        <?php $__currentLoopData = $sortedVisibleShelfBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__themeViews->handleViewInclude('books.parts.list-item', ['book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="grid third">
                        <?php $__currentLoopData = $sortedVisibleShelfBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__themeViews->handleViewInclude('entities.grid-item', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mt-xl">
                    <hr>
                    <p class="text-muted italic mt-xl mb-m"><?php echo e(trans('entities.shelves_empty_contents')); ?></p>
                    <div class="icon-list inline block">
                        <?php if(userCan(\BookStack\Permissions\Permission::BookCreateAll) && userCan(\BookStack\Permissions\Permission::BookshelfUpdate, $shelf)): ?>
                            <a href="<?php echo e($shelf->getUrl('/create-book')); ?>" class="icon-list-item text-book">
                                <span class="icon"><?php echo (new \BookStack\Util\SvgIcon('add'))->toHtml(); ?></span>
                                <span><?php echo e(trans('entities.books_create')); ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if(userCan(\BookStack\Permissions\Permission::BookshelfUpdate, $shelf)): ?>
                            <a href="<?php echo e($shelf->getUrl('/edit')); ?>" class="icon-list-item text-bookshelf">
                                <span class="icon"><?php echo (new \BookStack\Util\SvgIcon('edit'))->toHtml(); ?></span>
                                <span><?php echo e(trans('entities.shelves_edit_and_assign')); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('left'); ?>
    <?php echo $__themeViews->handleViewInclude('shelves.parts.show-sidebar-section-tags', ['shelf' => $shelf], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('shelves.parts.show-sidebar-section-details', ['shelf' => $shelf], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('shelves.parts.show-sidebar-section-activity', ['activity' => $activity], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('right'); ?>
    <?php echo $__themeViews->handleViewInclude('shelves.parts.show-sidebar-section-actions', ['shelf' => $shelf, 'view' => $view], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.tri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/shelves/show.blade.php ENDPATH**/ ?>