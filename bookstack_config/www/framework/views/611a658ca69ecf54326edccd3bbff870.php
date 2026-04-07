<?php if(isset($pageNav) && count($pageNav)): ?>
    <nav id="page-navigation" class="mb-xl" aria-label="<?php echo e(trans('entities.pages_navigation')); ?>">
        <h5><?php echo e(trans('entities.pages_navigation')); ?></h5>
        <div class="body">
            <div class="sidebar-page-nav menu">
                <?php $__currentLoopData = $pageNav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="page-nav-item h<?php echo e($navItem['level']); ?>">
                        <a href="<?php echo e($navItem['link']); ?>" class="text-limit-lines-1 block"><?php echo e($navItem['text']); ?></a>
                        <div class="link-background sidebar-page-nav-bullet"></div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </nav>
<?php endif; ?><?php /**PATH /app/www/resources/views/pages/parts/show-sidebar-section-page-nav.blade.php ENDPATH**/ ?>