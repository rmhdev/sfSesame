<?php /* @var $form CompanyForm */ ?>
<div class="actions">
    <button type="submit" id="button_<?php echo $form->isNew() ? 'create' : 'update'; ?>" name="save" class="btn success">
        <?php echo $form->isNew() ? 'Create' : 'Update'; ?>
    </button>

    <button type="submit" id="button_save_and_add" name="save_and_add" class="btn info">
        <?php echo $form->isNew() ? 'Create and add new' : 'Update and add new'; ?>
    </button>
</div>
