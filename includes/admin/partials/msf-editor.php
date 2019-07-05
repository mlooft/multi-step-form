<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    $block_types = Mondula_Form_Wizard_Block::get_block_types();
?>

<div id="fw-alert">
    <?php _e('Success. Form saved.', 'multi-step-form'); ?>
</div>
<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-active" id="fw-nav-steps"><?php _e('Steps', 'multi-step-form'); ?></a>
        <a class="nav-tab" id="fw-nav-settings"><?php _e('Form settings', 'multi-step-form'); ?></a>
    </h2>
    <div class="fw-mail-settings-container" style="display:none;">
        <div class="wrap">
            <h1><?php _e('General settings', 'multi-step-form'); ?></h1>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('"Thank you"-page:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-settings-thankyou" />
                        <p class="description"><?php _e('Users will be redirected to this URL after form submit. Leave blank if you don\'t need one.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
            </table>
            <h1><?php _e('Mail settings', 'multi-step-form'); ?></h1>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Send To:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-to" />
                        <p class="description"><?php _e('Email address to which the mails are sent', 'multi-step-form' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send From Email:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-mail" />
                        <p class="description"><?php _e('Email address and name from which the emails are sent. Leave blank for default admin email.', 'multi-step-form' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send From Name:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-name" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Subject:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-subject" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Reply-To:', 'multi-step-form' ); ?></th>
                    <td>
                        <select class="fw-mail-replyto">
                            <option value="no-reply" selected>No Reply-To</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Additional Email Headers:', 'multi-step-form' ); ?></th>
                    <td>
                        <textarea rows="4" cols="55" class="fw-mail-headers"></textarea>
                        <p class="description"><?php _e('You can add additional email headers for CC/BCC or others. One per line e.g.:', 'multi-step-form' ); ?></p>
                        <p class="description"><?php _e('CC: John Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('BCC: Jane Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('WARNING: only enter custom email headers if you know what you\'re doing - it can break your form.', 'multi-step-form'); ?></p>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Email Headline:', 'multi-step-form'); ?></th>
                    <td>
                        <textarea rows="5" cols="55" class="fw-mail-header"></textarea>
                        <p class="description"><?php _e( 'Introductory text for email', 'multi-step-form' ); ?></p>
                    </td>
                </tr>
            </table>
            <button class="fw-button-save"><?php _e( 'Save' ); ?></button>
        </div>
    </div>
    <div id="fw-elements-container" class="fw-elements-container">
        <div class="postbox-container">
            <div class="metabox-holder">
                <div class="postbox">
                    <h3>
                        <?php echo apply_filters('multi-step-form/title-filter', __('Multi Step Form', 'multi-step-form')); ?>
                    </h3>
                    <div class="inside">
                        <div class="fw-elements">
                            <input type="text" class="fw-wizard-title" value="Form Wizard" placeholder="<?php _e('Form Title', 'multi-step-form'); ?>">
                            <a class="fw-element-step"><i class="fa fa-plus"></i> <?php _e( 'Add Step' ); ?></a>
                            <h4><?php _e( 'Drag &amp; Drop an element from below to a section', 'multi-step-form' ); ?></h4>

                            <?php 
                            foreach ($block_types as $type => $type_data) {
                                if ($type_data['show_admin']) {
                                    ?>
                                    <a class="fw-draggable-block fw-element-<?php echo $type; ?>" data-type="<?php echo $type; ?>"><i class="fa fa-arrows"></i> <?php echo $type_data['title'] ?></a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="fw-actions">
                            <button class="fw-button-save"><?php _e( 'Save' ); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="fw-wizard-container" class="fw-wizard-container"></div>
    <div id="fw-elements-modal">
        <p>Content!</p>
    </div>
    <div id="fw-thickbox-content" style="display:none;">
        <?php 
        foreach ($block_types as $type => $type_data) {
            if ($type_data['show_admin']) {
                ?>
                <div id="fw-thickbox-<?php echo $type; ?>"><?php echo $type_data['title'] ?></div>
                <?php
            }
        }
        ?>
    </div>
</div>
