<?php /* @var $project Project */ ?>
<?php /*include_partial('project/page_header', array('project' => $project));*/ ?>

<div class="row">
    <div class="span12">
        <?php include_partial('project/show', array('project' => $project)); ?>

        <?php echo link_to('Edit', url_for('project_edit', $project), array('class' => 'btn btn-info', 'id' => 'button-edit')); ?>
        <?php echo link_to('Back to list', '@project', array('class' => 'btn', 'id' => 'button-list')); ?>
    </div>
 </div>
