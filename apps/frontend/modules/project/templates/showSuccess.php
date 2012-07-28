<?php /* @var $project Project */ ?>
show

<?php echo link_to('Edit', url_for('project_edit', $project), array('class' => 'btn btn-info', 'id' => 'button-edit')); ?>
<?php echo link_to('Back to list', '@project', array('class' => 'btn', 'id' => 'button-list')); ?>