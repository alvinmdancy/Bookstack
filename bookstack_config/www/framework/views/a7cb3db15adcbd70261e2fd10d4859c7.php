
<div refs="editor-toolbox@tab-content" data-tab-content="comments" class="toolbox-tab-content">
    <h4><?php echo e(trans('entities.comments')); ?></h4>

    <div class="comment-container-compact px-l">
        <p class="text-muted small mb-m">
            <?php echo e(trans('entities.comment_editor_explain')); ?>

        </p>
        <?php $__currentLoopData = $comments->getActive(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__themeViews->handleViewInclude('comments.comment-branch', ['branch' => $branch, 'readOnly' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($comments->empty()): ?>
            <p class="italic text-muted"><?php echo e(trans('entities.comment_none')); ?></p>
        <?php endif; ?>
        <?php if($comments->archivedThreadCount() > 0): ?>
            <details class="section-expander mt-s">
                <summary><?php echo e(trans('entities.comment_archived_threads')); ?></summary>
                <?php $__currentLoopData = $comments->getArchived(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__themeViews->handleViewInclude('comments.comment-branch', ['branch' => $branch, 'readOnly' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </details>
        <?php endif; ?>
    </div>
</div><?php /**PATH /app/www/resources/views/pages/parts/toolbox-comments.blade.php ENDPATH**/ ?>