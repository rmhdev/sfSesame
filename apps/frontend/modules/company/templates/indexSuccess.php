<?php
/* @var $pager sfPager */
/* @var $company Company */
?>
<div class="content">
    <div class="page-header">
    <h1>Companies</h1>
    </div>

<?php if (!$pager->getNbResults()) : ?>
    <div class="alert-message block-message warning">
        <p><strong>No items in the list</strong> </p>
    </div>
<?php else : ?>
    
    <?php if ($pager->haveToPaginate()) : ?>
    <?php echo sesame_render_pager($pager, '@company'); ?>
    <?php endif; ?>
    
    <table cellspacing="0">
        <thead>
            <tr>
                <th><?php 
                $sortType = 'asc';
                $name = "Id";
                if (($sort[0] == 'id')) {
                    $sortType = ($sort[1] == 'asc') ? 'desc' : 'asc';
                    $name .= sprintf(" (%s)", $sort[1]);
                }
                echo link_to($name, '@company?sort=id&sort_type=' . $sortType); ?></th>
                <th><?php 
                $sortType = 'asc';
                $name = "Name";
                if (($sort[0] == 'name')) {
                    $sortType = ($sort[1] == 'asc') ? 'desc' : 'asc';
                    $name .= sprintf(" (%s)", $sort[1]);
                }
                echo link_to($name, '@company?sort=name&sort_type=' . $sortType); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="2"><span><?php echo $pager->getNbResults(); ?> results</span> Page <?php echo sprintf("%d/%d", $pager->getPage(), $pager->getLastPage()); ?></td>
            </tr>
        </tfoot>
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