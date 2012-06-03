<?php if ($sf_user->hasFlash('error')) : ?>
<div class="alert alert-error">
    <h4 class="alert-heading">Error</h4>
    <p><?php echo $sf_user->getFlash('error'); ?></p>
</div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('success')) : ?>
<div class="alert alert-success">
    <h4 class="alert-heading">Success</h4>
    <p><?php echo $sf_user->getFlash('success'); ?></p>
</div>
<?php endif; ?>
