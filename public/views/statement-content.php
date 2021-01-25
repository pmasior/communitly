<p class="date-and-source">
    <?php if ($statement->getApproveDate()): ?>
        <span title="Dodane <?= $statement->getCreationDate()->format('d.m.Y H:i:s') ?> przez <?= $statement->getCreationUser()->getEmail(); ?> &#xAZweryfikowane <?= $statement->getApproveDate()->format('d.m.Y H:i:s') ?> przez <?= $statement->getApproveUser()->getEmail(); ?>">
            <i class='fas fa-check-circle verified-statement'></i>
        </span>
    <?php else: ?>
        <span title='Dodane <?= $statement->getCreationDate()->format('d.m.Y H:i:s') ?> przez <?= $statement->getCreationUser()->getEmail(); ?>'>
            <i class='fas fa-exclamation-circle unverified-statement'></i>
        </span>
    <?php endif; ?>
    <?= $statement->getCreationDate()->format('d.m.Y H:i:s'); ?>
    <?php if ($statement->getSourceURL()): ?>
        z
        <?php $regex_match_result = preg_match("/:\/\/(\S+\.\w+)\//", $statement->getSourceURL(), $url); ?>
        <?php if ($regex_match_result): ?>
            <a href="<?= $statement->getSourceURL(); ?>"><?= $url[1];?></a>
        <?php else: ?>
            <?= $statement->getSourceURL(); ?>
        <?php endif; ?>
    <?php endif; ?>
</p>

<pre class="statement-content"><?= $statement->getContent(); ?></pre>
<?php foreach ($statement->getAttachments() as $attachment): ?>
    <a href="<?= '/public/uploads/' . $attachment->getFilename(); ?>" class="attachment">
        <i class="fas fa-paperclip"></i>
        <?= $attachment->getFilename(); ?>
    </a>
<?php endforeach; ?>
