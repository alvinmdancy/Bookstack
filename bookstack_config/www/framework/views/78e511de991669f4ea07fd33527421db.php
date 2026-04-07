<?php $__env->startPush('social-meta'); ?>
    <meta property="og:description" content="<?php echo e(Str::limit($page->text, 100, '...')); ?>">
<?php $__env->stopPush(); ?>

<?php echo $__themeViews->handleViewInclude('entities.body-tag-classes', ['entity' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php $__env->startSection('body'); ?>

    <div class="mb-m print-hidden">
        <?php echo $__themeViews->handleViewInclude('entities.breadcrumbs', ['crumbs' => [
            $page->book,
            $page->hasChapter() ? $page->chapter : null,
            $page,
        ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </div>

    <main class="content-wrap card">
        <div component="page-display"
             option:page-display:page-id="<?php echo e($page->id); ?>"
             class="page-content clearfix">
            <?php echo $__themeViews->handleViewInclude('pages.parts.page-display', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        </div>
        <?php echo $__themeViews->handleViewInclude('pages.parts.pointer', ['page' => $page, 'commentTree' => $commentTree], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    </main>

    <?php echo $__themeViews->handleViewInclude('entities.sibling-navigation', ['next' => $next, 'previous' => $previous], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

    <?php if($commentTree->enabled()): ?>
        <div class="comments-container mb-l print-hidden">
            <?php echo $__themeViews->handleViewInclude('comments.comments', ['commentTree' => $commentTree, 'page' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <div class="clearfix"></div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('left'); ?>
    <?php echo $__themeViews->handleViewInclude('pages.parts.show-sidebar-section-tags', ['page' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('pages.parts.show-sidebar-section-attachments', ['page' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('pages.parts.show-sidebar-section-page-nav', ['pageNav' => $pageNav], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('entities.book-tree', ['book' => $book, 'sidebarTree' => $sidebarTree], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('right'); ?>
    <?php echo $__themeViews->handleViewInclude('pages.parts.show-sidebar-section-details', ['page' => $page, 'watchOptions' => $watchOptions, 'book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('pages.parts.show-sidebar-section-actions', ['page' => $page, 'watchOptions' => $watchOptions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/pages/show.blade.php ENDPATH**/ ?>