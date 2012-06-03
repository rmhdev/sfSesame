<?php include_partial('company/page_header', array('company' => $company)); ?>

<div class="row">
    <div class="span12">
        <?php include_partial('company/show', array('company' => $company)); ?>
        
        <?php echo link_to('Back to list', '@company', array('class' => 'btn ')); ?>
        <?php echo link_to('Edit', url_for('company_edit', $company), array('class' => 'btn btn-info', 'id' => 'button-edit')); ?>
    </div>
</div>
