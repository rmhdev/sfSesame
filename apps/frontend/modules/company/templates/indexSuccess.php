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
            <?php if ($sf_user->hasFlash('error')) : ?>
            <div class="alert-message error"><?php echo $sf_user->getFlash('error'); ?></div>
            <?php endif; ?>
            
            <?php if ($sf_user->hasFlash('success')) : ?>
            <div class="alert-message success"><?php echo $sf_user->getFlash('success'); ?></div>
            <?php endif; ?>
            
            <?php if (!$pager->getNbResults()) : ?>
                <div class="alert-message block-message warning">
                    <p><strong>No items in the list</strong> </p>
                </div>
            <?php else : ?>
                <?php if ($pager->haveToPaginate()) : ?>
                <?php echo sesame_render_pager($pager, '@company'); ?>
                <?php endif; ?>

                <form action="<?php echo url_for('company_collection', array('action' => 'batch')); ?>" method="post" class="form-batch">
                <table cellspacing="0" class="zebra-striped">
                    <thead>
                        <tr>
                            <th class="col-batch">&nbsp;</th>
                            <th class="col-id"><?php 
                            echo sesame_link_to_sort('Id', '@company', array('field' => 'id', 'sort' => $sort[0], 'sort_type' => $sort[1]));
                            ?></th>
                            <th class="col-name"><?php 
                            echo sesame_link_to_sort('Name', '@company', array('field' => 'name', 'sort' => $sort[0], 'sort_type' => $sort[1]));
                            ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="3"><span><?php echo $pager->getNbResults(); ?> results</span> <?php echo sprintf("Page %d/%d", $pager->getPage(), $pager->getLastPage()); ?></td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php foreach ($pager->getResults() as $company) : ?>
                        <tr>
                            <td class="col-batch"><input type="checkbox" name="ids[]" value="<?php echo $company->getId(); ?>" id="ids_<?php echo $company->getId(); ?>"/></td>
                            <td class="col-id"><?php echo $company->getPrimaryKey(); ?></td>
                            <td class="col-name"><?php echo link_to($company->getName(), url_for('company_show', $company)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                    
                    <select name="batch_action">
                        <option value="">- Select an action -</option>
                    </select>
                    <button type="submit" class="btn success">Execute</button>
                    
                </form>
            <?php endif; ?>
        </div>
        
    </div>



</div>