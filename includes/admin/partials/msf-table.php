<?php
    if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h2>Multi Step Forms
        <a href="<?php echo $edit_url; ?>" class="page-title-action">
            <?php _e('Add New', 'multi-step-form'); ?>
        </a>
    </h2>
    <form id="fw-wizard-table" method="get">
        <input type="hidden" name="page" value="mondula-multistep-forms" />
        <?php $table->display(); ?>
    </form>
    <h2><?php echo __('Import a Form', 'multi-step-form'); ?></h2>
    <form id="msf-import" method="post" enctype="multipart/form-data">
        <input type='file' id='json-import' name='json-import' accept='application/json,.json'>
        <input name="submit" id="submit" class="button button-primary" value="<?php echo __('Upload & Import', 'multi-step-form'); ?>" type="submit">
    </form>
</div>
