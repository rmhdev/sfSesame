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
            <div class="clearfix">
            <?php echo link_to('Create new company', '@company_new', array('class' => 'btn btn-success')); ?>
                
            </div>
            <br />
            
            <form action="<?php echo url_for('company_collection', array('action' => 'filter')); ?>" method="post" class="form-filter form-stacked">
                <fieldset>
                    <legend>Search</legend>
                    <?php echo $formFilter->render(); ?>
                    <div class="actions">
                        <button type="submit" class="btn btn-info">Search</button>
                        <?php echo link_to('Reset', url_for('company_collection', array('action' => 'filter')), array('class' => 'reset-filter btn', 'method' => 'post', 'query_string' => '_reset')); ?>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="span8">
            <?php include_partial('global/flashes'); ?>
            
            <?php if (!$pager->getNbResults()) : ?>
                <div class="hero-unit alert alert-block">
                    <h2 class="alert-heading">No results</h2>
                    <p>No items in the list.</p>
                </div>
            <?php else : ?>
                <?php if ($pager->haveToPaginate()) : ?>
                <?php echo sesame_render_pager($pager, '@company'); ?>
                <?php endif; ?>

                <form action="<?php echo url_for('company_collection', array('action' => 'batch')); ?>" method="post" class="form-batch">
                <table cellspacing="0" class="table table-striped table-bordered">
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
                        <option value="batchDelete">Delete selected items</option>
                    </select>
                    <button type="submit" class="btn btn-warning">Execute</button>
                    
                    <?php $form = new BaseForm();?>
                    <?php if ($form->isCSRFProtected()) : ?>
                    <input type="hidden" name="<?php echo $form->getCSRFFieldName(); ?>" value="<?php echo $form->getCSRFToken(); ?>" />
                    <?php endif; ?>
                    
                </form>
            <?php endif; ?>
        </div>
        
    </div>

</div>