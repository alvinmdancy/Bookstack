<?php $__env->startSection('document-class', 'bg-white ' .  (setting()->getForCurrentUser('dark-mode-enabled') ? 'dark-mode ' : '')); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-m">

        <h4 class="mt-s"><?php echo e(trans('editor.editor_license')); ?></h4>
        <p>
            <?php echo trans('editor.editor_tiny_license', ['tinyLink' => '<a href="https://www.tiny.cloud/" target="_blank" rel="noopener noreferrer">TinyMCE</a>']); ?>

            <br>
            <a href="<?php echo e(url('/libs/tinymce/license.txt')); ?>" target="_blank"><?php echo e(trans('editor.editor_tiny_license_link')); ?></a>
        </p>

        <h4><?php echo e(trans('editor.shortcuts')); ?></h4>

        <p><?php echo e(trans('editor.shortcuts_intro')); ?></p>
        <table>
            <thead>
            <tr>
                <th><?php echo e(trans('editor.shortcut')); ?> <?php echo e(trans('editor.windows_linux')); ?></th>
                <th><?php echo e(trans('editor.shortcut')); ?> <?php echo e(trans('editor.mac')); ?></th>
                <th><?php echo e(trans('editor.description')); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><code>Ctrl</code>+<code>S</code></td>
                <td><code>Cmd</code>+<code>S</code></td>
                <td><?php echo e(trans('entities.pages_edit_save_draft')); ?></td>
            </tr>
            <tr>
                <td><code>Ctrl</code>+<code>Enter</code></td>
                <td><code>Cmd</code>+<code>Enter</code></td>
                <td><?php echo e(trans('editor.save_continue')); ?></td>
            </tr>
            <tr>
                <td><code>Ctrl</code>+<code>B</code></td>
                <td><code>Cmd</code>+<code>B</code></td>
                <td><?php echo e(trans('editor.bold')); ?></td>
            </tr>
            <tr>
                <td><code>Ctrl</code>+<code>I</code></td>
                <td><code>Cmd</code>+<code>I</code></td>
                <td><?php echo e(trans('editor.italic')); ?></td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>1</code><br>
                    <code>Ctrl</code>+<code>2</code><br>
                    <code>Ctrl</code>+<code>3</code><br>
                    <code>Ctrl</code>+<code>4</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>1</code><br>
                    <code>Cmd</code>+<code>2</code><br>
                    <code>Cmd</code>+<code>3</code><br>
                    <code>Cmd</code>+<code>4</code>
                </td>
                <td>
                    <?php echo e(trans('editor.header_large')); ?> <br>
                    <?php echo e(trans('editor.header_medium')); ?> <br>
                    <?php echo e(trans('editor.header_small')); ?> <br>
                    <?php echo e(trans('editor.header_tiny')); ?>

                </td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>5</code><br>
                    <code>Ctrl</code>+<code>D</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>5</code><br>
                    <code>Cmd</code>+<code>D</code>
                </td>
                <td><?php echo e(trans('editor.paragraph')); ?></td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>6</code><br>
                    <code>Ctrl</code>+<code>Q</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>6</code><br>
                    <code>Cmd</code>+<code>Q</code>
                </td>
                <td><?php echo e(trans('editor.blockquote')); ?></td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>7</code><br>
                    <code>Ctrl</code>+<code>E</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>7</code><br>
                    <code>Cmd</code>+<code>E</code>
                </td>
                <td><?php echo e(trans('editor.insert_code_block')); ?></td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>8</code><br>
                    <code>Ctrl</code>+<code>Shift</code>+<code>E</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>8</code><br>
                    <code>Cmd</code>+<code>Shift</code>+<code>E</code>
                </td>
                <td><?php echo e(trans('editor.inline_code')); ?></td>
            </tr>
            <tr>
                <td><code>Ctrl</code>+<code>9</code></td>
                <td><code>Cmd</code>+<code>9</code></td>
                <td>
                    <?php echo e(trans('editor.callouts')); ?> <br>
                    <small><?php echo e(trans('editor.callouts_cycle')); ?></small>
                </td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>O</code> <br>
                    <code>Ctrl</code>+<code>P</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>O</code> <br>
                    <code>Cmd</code>+<code>P</code>
                </td>
                <td>
                    <?php echo e(trans('editor.list_numbered')); ?> <br>
                    <?php echo e(trans('editor.list_bullet')); ?>

                </td>
            </tr>
            <tr>
                <td>
                    <code>Ctrl</code>+<code>Shift</code>+<code>K</code>
                </td>
                <td>
                    <code>Cmd</code>+<code>Shift</code>+<code>K</code>
                </td>
                <td><?php echo e(trans('editor.link_selector')); ?></td>
            </tr>
            </tbody>
        </table>

    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.plain', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/www/resources/views/help/tinymce.blade.php ENDPATH**/ ?>