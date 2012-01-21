<?php
/* @var $form CompanyForm */
?>
<div class="page-header">
    <h1>Company <small>new</small></h1>
</div>

<div class="row">
    <div class="span16">
        <?php echo form_tag_for($form, '@company'); ?>
            <fieldset>
                <legend>Create a new company</legend>

                <?php echo $form->render(); ?>
                <button type="submit" id="button_create">Create</button>
                
            </fieldset>
        </form>
    </div>
</div>
