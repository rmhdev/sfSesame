<?php echo form_tag_for($form, '@project', array('class' => 'form-horizontal')); ?>
    
    <?php echo $form->render(); ?>

    <div class="form-actions">
        <button type="submit" id="button-create" class="btn btn-success">Create</button>
    </div>

</form>
