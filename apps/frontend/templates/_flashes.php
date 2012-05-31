<?php if ($sf_user->hasFlash('error')) : ?>
<div class="alert-message error"><?php echo $sf_user->getFlash('error'); ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('success')) : ?>
<div class="alert-message success"><?php echo $sf_user->getFlash('success'); ?></div>
<?php endif; ?>