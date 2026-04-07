<nav id="book-tree"
     class="book-tree mb-xl"
     aria-label="<?php echo e(trans('entities.books_navigation')); ?>">

    <h5><?php echo e(trans('entities.books_navigation')); ?></h5>

    <ul class="sidebar-page-list mt-xs menu entity-list">
        <?php if(userCan(\BookStack\Permissions\Permission::BookView, $book)): ?>
            <li class="list-item-book book">
                <?php echo $__themeViews->handleViewInclude('entities.list-item-basic', ['entity' => $book, 'classes' => ($current->matches($book)? 'selected' : '')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </li>
        <?php endif; ?>

        <?php $__currentLoopData = $sidebarTree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookChild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-item-<?php echo e($bookChild->getType()); ?> <?php echo e($bookChild->getType()); ?> <?php echo e($bookChild->isA('page') && $bookChild->draft ? 'draft' : ''); ?>">
                <?php echo $__themeViews->handleViewInclude('entities.list-item-basic', ['entity' => $bookChild, 'classes' => $current->matches($bookChild)? 'selected' : ''], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

                <?php if($bookChild->isA('chapter') && count($bookChild->visible_pages) > 0): ?>
                    <div class="entity-list-item no-hover">
                        <span role="presentation" class="icon text-chapter"></span>
                        <div class="content">
                            <?php echo $__themeViews->handleViewInclude('chapters.parts.child-menu', [
                                'chapter' => $bookChild,
                                'current' => $current,
                                'isOpen'  => $bookChild->matchesOrContains($current)
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                        </div>
                    </div>

                <?php endif; ?>

            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</nav><?php /**PATH /app/www/resources/views/entities/book-tree.blade.php ENDPATH**/ ?>