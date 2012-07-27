<?php /* @var $form ProjectForm */ ?>

<?php echo form_tag_for($form, '@project', array('class' => 'form-horizontal')); ?>
    
    <?php echo $form->render(); ?>

    <div class="form-actions">
        <button type="submit" id="button-<?php echo $form->isNew() ? 'create' : 'update'; ?>" class="btn btn-success">Create</button>
    </div>

</form>
