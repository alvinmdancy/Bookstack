<!DOCTYPE html>
<html lang="<?php echo e(isset($locale) ? $locale->htmlLang() : config('app.default_locale')); ?>"
      dir="<?php echo e(isset($locale) ? $locale->htmlDirection() : 'auto'); ?>"
      class="<?php echo $__env->yieldContent('document-class'); ?>">
<head>
    <title><?php echo e(isset($pageTitle) ? $pageTitle . ' | ' : ''); ?><?php echo e(setting('app-name')); ?></title>

    <!-- Meta -->
    <meta name="viewport" content="width=device-width">
    <meta charset="utf-8">

    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(versioned_asset('dist/styles.css')); ?>">

    <!-- Custom Styles & Head Content -->
    <?php echo $__themeViews->handleViewInclude('layouts.parts.custom-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
    <?php echo $__themeViews->handleViewInclude('layouts.parts.custom-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH /app/www/resources/views/layouts/plain.blade.php ENDPATH**/ ?>