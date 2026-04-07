

<a href="<?php echo e($chapter->getUrl()); ?>" class="chapter entity-list-item <?php if($chapter->visible_pages->count() > 0): ?> has-children <?php endif; ?>" data-entity-type="chapter" data-entity-id="<?php echo e($chapter->id); ?>">
    <span class="icon text-chapter"><?php echo (new \BookStack\Util\SvgIcon('chapter'))->toHtml(); ?></span>
    <div class="content">
        <h4 class="entity-list-item-name break-text"><?php echo e($chapter->name); ?></h4>
        <div class="entity-item-snippet">
            <p class="text-muted break-text"><?php echo e($chapter->getExcerpt()); ?></p>
        </div>
    </div>
</a>
<?php if($chapter->visible_pages->count() > 0): ?>
    <div class="chapter chapter-expansion">
        <span class="icon text-chapter"><?php echo (new \BookStack\Util\SvgIcon('page'))->toHtml(); ?></span>
        <div component="chapter-contents" class="content">
            <button type="button"
                    refs="chapter-contents@toggle"
                    aria-expanded="false"
                    class="text-muted chapter-contents-toggle"><?php echo (new \BookStack\Util\SvgIcon('caret-right'))->toHtml(); ?> <span><?php echo e(trans_choice('entities.x_pages', $chapter->visible_pages->count())); ?></span></button>
            <div refs="chapter-contents@list" class="inset-list chapter-contents-list">
                <div class="entity-list-item-children">
                    <?php echo $__themeViews->handleViewInclude('entities.list', ['entities' => $chapter->visible_pages], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php /**PATH /app/www/resources/views/chapters/parts/list-item.blade.php ENDPATH**/ ?>