<?php
    if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <span class="h2">Multi Step Forms
        <a href="<?php echo $edit_url; ?>" class="page-title-action">
            <?php _e('Add New', 'multi-step-form'); ?>
        </a>
    </span>
    <form id="fw-wizard-table" method="get">
        <input type="hidden" name="page" value="mondula-multistep-forms" />
        <?php wp_nonce_field('bulk-delete-action', 'bulk_delete_nonce'); ?>
        <?php $table->display(); ?>
    </form>
    <span class="h2"><?php echo __('Import a Form', 'multi-step-form'); ?></span>
    <form id="msf-import" method="post" enctype="multipart/form-data">
        <input type='file' id='json-import' name='json-import' accept='application/json,.json'>
        <?php wp_nonce_field('json_upload_action', 'json_upload_nonce'); ?>
        <input name="submit" id="submit" class="button button-primary" value="<?php echo __('Upload & Import', 'multi-step-form'); ?>" type="submit">
    </form>
</div>
