<?php
/* @var $pager sfPager */
/* @var $company Company */
/* @var $formFilter CompanyFormFilter */ 
?>
<div class="content">
    <div class="page-header">
    <h1>Companies</h1>
    </div>
    
    <div class="row">
        <div class="span4">
            <form action="<?php echo url_for('company_collection', array('action' => 'filter')); ?>" method="post" class="form-filter form-stacked">
                <fieldset>
                    <legend>Search</legend>
                    <?php echo $formFilter->render(); ?>
                    <div class="actions">
                        <button type="submit" class="btn success">Search</button>
                        <?php echo link_to('Reset', url_for('company_collection', array('action' => 'filter')), array('class' => 'reset-filter btn', 'method' => 'post', 'query_string' => '_reset')); ?>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="span8">
            <?php if (!$pager->getNbResults()) : ?>
                <div class="alert-message block-message warning">
                    <p><strong>No items in the list</strong> </p>
                </div>
            <?php else : ?>
                <?php if ($pager->haveToPaginate()) : ?>
                <?php echo sesame_render_pager($pager, '@company'); ?>
                <?php endif; ?>

                <form action="#" method="post" class="form-batch">
                <table cellspacing="0" class="zebra-striped">
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
                            <td colspan="2"><span><?php echo $pager->getNbResults(); ?> results</span> <?php echo sprintf("Page %d/%d", $pager->getPage(), $pager->getLastPage()); ?></td>
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
                    
                </form>
            <?php endif; ?>
        </div>
        
    </div>



</div>