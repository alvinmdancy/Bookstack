<div component="chapter-contents" class="chapter-child-menu">
    <button type="button"
            refs="chapter-contents@toggle"
            aria-expanded="<?php echo e($isOpen ? 'true' : 'false'); ?>"
            class="text-muted chapter-contents-toggle <?php if($isOpen): ?> open <?php endif; ?>">
        <?php echo (new \BookStack\Util\SvgIcon('caret-right'))->toHtml(); ?> <?php echo (new \BookStack\Util\SvgIcon('page'))->toHtml(); ?> <span><?php echo e(trans_choice('entities.x_pages', $bookChild->visible_pages->count())); ?></span>
    </button>
    <ul refs="chapter-contents@list"
        class="chapter-contents-list sub-menu inset-list <?php if($isOpen): ?> open <?php endif; ?>" <?php if($isOpen): ?>
        style="display: block;" <?php endif; ?>
        role="menu">
        <?php $__currentLoopData = $bookChild->visible_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childPage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-item-page <?php echo e($childPage->isA('page') && $childPage->draft ? 'draft' : ''); ?>" role="presentation">
                <?php echo $__themeViews->handleViewInclude('entities.list-item-basic', ['entity' => $childPage, 'classes' => $current->matches($childPage)? 'selected' : '' ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div><?php /**PATH /app/www/resources/views/chapters/parts/child-menu.blade.php ENDPATH**/ ?>