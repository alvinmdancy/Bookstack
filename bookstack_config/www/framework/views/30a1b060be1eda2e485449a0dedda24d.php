<section components="page-comments tabs"
         option:page-comments:page-id="<?php echo e($page->id); ?>"
         option:page-comments:created-text="<?php echo e(trans('entities.comment_created_success')); ?>"
         option:page-comments:count-text="<?php echo e(trans('entities.comment_thread_count')); ?>"
         option:page-comments:archived-count-text="<?php echo e(trans('entities.comment_archived_count')); ?>"
         option:page-comments:wysiwyg-text-direction="<?php echo e($locale->htmlDirection()); ?>"
         class="comments-list tab-container"
         aria-label="<?php echo e(trans('entities.comments')); ?>">

    <div refs="page-comments@comment-count-bar" class="flex-container-row items-center">
        <div role="tablist" class="flex">
            <button type="button"
                    role="tab"
                    id="comment-tab-active"
                    aria-controls="comment-tab-panel-active"
                    refs="page-comments@active-tab"
                    aria-selected="true"><?php echo e(trans_choice('entities.comment_thread_count', $commentTree->activeThreadCount())); ?></button>
            <button type="button"
                    role="tab"
                    id="comment-tab-archived"
                    aria-controls="comment-tab-panel-archived"
                    refs="page-comments@archived-tab"
                    aria-selected="false"><?php echo e(trans_choice('entities.comment_archived_count', count($commentTree->getArchived()))); ?></button>
        </div>
        <?php if($commentTree->empty() && userCan(\BookStack\Permissions\Permission::CommentCreateAll)): ?>
            <div refs="page-comments@add-button-container" class="ml-m flex-container-row" >
                <button type="button"
                        refs="page-comments@add-comment-button"
                        class="button outline mb-m ml-auto"><?php echo e(trans('entities.comment_add')); ?></button>
            </div>
        <?php endif; ?>
    </div>

    <div id="comment-tab-panel-active"
         refs="page-comments@active-container"
         tabindex="0"
         role="tabpanel"
         aria-labelledby="comment-tab-active"
         class="comment-container no-outline">
        <div refs="page-comments@comment-container">
            <?php $__currentLoopData = $commentTree->getActive(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__themeViews->handleViewInclude('comments.comment-branch', ['branch' => $branch, 'readOnly' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <p class="text-center text-muted italic empty-state"><?php echo e(trans('entities.comment_none')); ?></p>

        <?php if(userCan(\BookStack\Permissions\Permission::CommentCreateAll)): ?>
            <?php echo $__themeViews->handleViewInclude('comments.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php if(!$commentTree->empty()): ?>
                <div refs="page-comments@addButtonContainer" class="ml-m flex-container-row">
                    <button type="button"
                            refs="page-comments@add-comment-button"
                            class="button outline mb-m ml-auto"><?php echo e(trans('entities.comment_add')); ?></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div refs="page-comments@archive-container"
         id="comment-tab-panel-archived"
         tabindex="0"
         role="tabpanel"
         aria-labelledby="comment-tab-archived"
         hidden="hidden"
         class="comment-container no-outline">
        <?php $__currentLoopData = $commentTree->getArchived(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__themeViews->handleViewInclude('comments.comment-branch', ['branch' => $branch, 'readOnly' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <p class="text-center text-muted italic empty-state"><?php echo e(trans('entities.comment_none')); ?></p>
    </div>

    <?php if(userCan(\BookStack\Permissions\Permission::CommentCreateAll) || $commentTree->canUpdateAny()): ?>
        <?php $__env->startPush('body-end'); ?>
            <?php echo $__themeViews->handleViewInclude('form.editor-translations', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
            <?php echo $__themeViews->handleViewInclude('entities.selector-popup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>

</section><?php /**PATH /app/www/resources/views/comments/comments.blade.php ENDPATH**/ ?>