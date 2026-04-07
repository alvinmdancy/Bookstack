<?php $__env->startSection('container-attrs'); ?>
    component="entity-search"
    option:entity-search:entity-id="<?php echo e($book->id); ?>"
    option:entity-search:entity-type="book"
<?php $__env->stopSection(); ?>

<?php $__env->startPush('social-meta'); ?>
    <meta property="og:description" content="<?php echo e(Str::limit($book->description, 100, '...')); ?>">
    <?php if($book->coverInfo()->exists()): ?>
        <meta property="og:image" content="<?php echo e($book->coverInfo()->getUrl()); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__themeViews->handleViewInclude('entities.body-tag-classes', ['entity' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php $__env->startSection('body'); ?>

    <div class="mb-s print-hidden">
        <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
            $book,
        ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>

    <main class="content-wrap card">
        <h1 class="break-text"><?php echo e($book->name); ?></h1>
        <div refs="entity-search@contentView" class="book-content">
            <div class="text-muted break-text"><?php echo $book->descriptionInfo()->getHtml(); ?></div>
            <?php if(count($bookChildren) > 0): ?>
                <div class="entity-list book-contents">
                    <?php $__currentLoopData = $bookChildren; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childElement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($childElement->isA('chapter')): ?>
                            <?php echo $__themeViews->handleViewInclude('chapters.parts.list-item', ['chapter' => $childElement], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                        <?php else: ?>
                            <?php echo $__themeViews->handleViewInclude('pages.parts.list-item', ['page' => $childElement], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="mt-xl">
                    <hr>
                    <p class="text-muted italic mb-m mt-xl"><?php echo e(trans('entities.books_empty_contents')); ?></p>

                    <div class="icon-list block inline">
                        <?php if(userCan(\BookStack\Permissions\Permission::PageCreate, $book)): ?>
                            <a href="<?php echo e($book->getUrl('/create-page')); ?>" class="icon-list-item text-page">
                                <span class="icon"><?php echo (new \BookStack\Util\SvgIcon('page'))->toHtml(); ?></span>
                                <span><?php echo e(trans('entities.books_empty_create_page')); ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if(userCan(\BookStack\Permissions\Permission::ChapterCreate, $book)): ?>
                            <a href="<?php echo e($book->getUrl('/create-chapter')); ?>" class="icon-list-item text-chapter">
                                <span class="icon"><?php echo (new \BookStack\Util\SvgIcon('chapter'))->toHtml(); ?></span>
                                <span><?php echo e(trans('entities.books_empty_add_chapter')); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>
        </div>

        <?php echo $__themeViews->handleViewInclude('entities.search-results', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('right'); ?>
    <?php echo $__themeViews->handleViewInclude('books.parts.show-sidebar-section-details', ['book' => $book, 'watchOptions' => $watchOptions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('books.parts.show-sidebar-section-actions', ['book' => $book, 'watchOptions' => $watchOptions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('left'); ?>
    <?php echo $__themeViews->handleViewInclude('entities.search-form', ['label' => trans('entities.books_search_this')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('books.parts.show-sidebar-section-tags', ['book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('books.parts.show-sidebar-section-shelves', ['bookParentShelves' => $bookParentShelves], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('books.parts.show-sidebar-section-activity', ['activity' => $activity], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.tri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/books/show.blade.php ENDPATH**/ ?>