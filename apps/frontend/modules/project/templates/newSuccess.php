<?php /* @var $form ProjectForm */ ?>
<?php include_partial('project/page_header', array('project' => $project)); ?>

<?php echo form_tag_for($form, '@project', array('class' => 'form-horizontal')); ?>
    
    <?php echo $form->render(); ?>

    <div class="form-actions">
        <button type="submit" id="button-create" class="btn btn-success">Create</button>
        <button type="submit" id="button-save-and-add" name="save_and_add" class="btn btn-success">Create and add new</button>
    </div>

</form>
