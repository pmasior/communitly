<div class="dialog-background js-message-dialog-background"></div>
<div class="dialog js-message-dialog">
    <?php if(isset($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <span class="error"><?= $message; ?></span>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
