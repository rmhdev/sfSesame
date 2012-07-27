<?php /* @var $form ProjectForm */ ?>
<div class="form-actions">
    <button type="submit" id="button-<?php echo $form->isNew() ? 'create' : 'update'; ?>" name="save" class="btn btn-success">
        <?php echo $form->isNew() ? 'Create' : 'Update'; ?>
    </button>

    <button type="submit" id="button-save-and-add" name="save_and_add" class="btn btn-success">
        <?php echo $form->isNew() ? 'Create and add new' : 'Update and add new'; ?>
    </button>
</div>
