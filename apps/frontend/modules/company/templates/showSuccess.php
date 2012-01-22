<?php include_partial('company/page_header', array('company' => $company)); ?>

<div class="row">
    <div class="span16">
        <?php include_partial('company/show', array('company' => $company)); ?>

        <?php echo link_to('Edit', url_for('company_edit', $company), array('class' => 'btn', 'id' => 'button_edit')); ?>
    </div>
</div>
