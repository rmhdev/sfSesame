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
    
    <?php if ($pager->haveToPaginate()) : ?>
    <div class="pagination">
        <ul>
            <li class="prev <?php echo ($pager->getPage() == 1) ? "disabled" : "disabled"; ?>"><a href="<?php echo ($pager->getPage() == 1) ? "#" : url_for('@company?page=1'); ?>">Previous</a></li>
            <li class="<?php echo ($pager->getPage() == 1) ? "active" : ""; ?>"><?php echo link_to(1, url_for('@company?page=1')); ?></li>
            <li class="<?php echo ($pager->getPage() == 2) ? "active" : ""; ?>"><?php echo link_to(2, url_for('@company?page=2')); ?></li>
            <li class=""><?php echo link_to("Next", url_for('@company?page=2')); ?></li>
        </ul>
    </div>
    <?php endif; ?>
    
    
    <table cellspacing="0">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
            </tr>
        </thead>
        <?php foreach ($pager->getResults() as $company) : ?>
        <tbody>
            <tr>
                <td><?php echo $company->getPrimaryKey(); ?></td>
                <td><?php echo link_to($company->getName(), url_for('company_show', $company)); ?></td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

</div>