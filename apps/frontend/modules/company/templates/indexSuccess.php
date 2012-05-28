<?php
/* @var $pager sfPager */
/* @var $company Company */
/* @var $formFilter CompanyFormFilter */ 
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
    
    <form action="<?php echo url_for('company_collection', array('action' => 'filter')); ?>" method="post" class="form-filter">
        <?php echo $formFilter->render(); ?>
        
        <input type="submit" value="Filter" class="btn success"/>
    </form>
    
    <?php if ($pager->haveToPaginate()) : ?>
    <?php echo sesame_render_pager($pager, '@company'); ?>
    <?php endif; ?>
    
    <table cellspacing="0">
        <thead>
            <tr>
                <th><?php 
                echo sesame_link_to_sort('Id', '@company', array('field' => 'id', 'sort' => $sort[0], 'sort_type' => $sort[1]));
                ?></th>
                <th><?php 
                echo sesame_link_to_sort('Name', '@company', array('field' => 'name', 'sort' => $sort[0], 'sort_type' => $sort[1]));
                ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="2"><span><?php echo $pager->getNbResults(); ?> results</span> Page <?php echo sprintf("%d/%d", $pager->getPage(), $pager->getLastPage()); ?></td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($pager->getResults() as $company) : ?>
            <tr>
                <td><?php echo $company->getPrimaryKey(); ?></td>
                <td><?php echo link_to($company->getName(), url_for('company_show', $company)); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>