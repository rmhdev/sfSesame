<?php
/* @var $pager sfPager */
/* @var $company Company */
?>
<div class="content">

    <?php if (!$pager->getNbResults()) : ?>
    <div class="alert-message block-message warning">
        <p><strong>No items in the list</strong> </p>
    </div>
    <?php else : ?>
    <table cellspacing="0">
        <?php foreach ($pager->getResults() as $company) : ?>
        <tbody>
            <tr>
                <td><?php echo link_to($company->getName(), url_for('company_show', $company)); ?></td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

</div>