<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
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
                    <th scope="row"><?php _e( 'Send To:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-to" />
                        <p class="description"><?php _e( 'Email address to which the mails are sent', 'multi-step-form' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Send From Email:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-mail" />
                        <p class="description"><?php _e( 'Email address and name from which the emails are sent. Leave blank for default admin email.', 'multi-step-form' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Send From Name:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-name" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Subject:', 'multi-step-form' ); ?></th>
                    <td>
                        <input type="text" class="fw-mail-subject" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Additional Email Headers:', 'multi-step-form' ); ?></th>
                    <td>
                        <textarea rows="4" cols="55" class="fw-mail-headers"></textarea>
                        <p class="description"><?php _e( 'You can add additional email headers for CC/BCC, Reply-To. One per line e.g.:', 'multi-step-form' ); ?></p>
                        <p class="description"><?php _e('Reply-To: John Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('CC: Jane Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
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
                    <h3><?php _e('Multi Step Form', 'multi-step-form'); ?>
                        <?php do_action( 'msf_echopro', $id ); ?>
                    </h3>
                    <div class="inside">
                        <div class="fw-elements">
                            <input type="text" class="fw-wizard-title" value="Form Wizard" placeholder="Form Title">
                            <a class="fw-element-step"><i class="fa fa-plus"></i> <?php _e( 'Add Step' ); ?></a>
                            <h4><?php _e( 'Drag &amp; Drop an element from below to a section', 'multi-step-form' ); ?></h4>
                            <a class="fw-draggable-block fw-element-radio" data-type="radio"><i class="fa fa-arrows"></i> <?php _e('Radio/Checkbox', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-select" data-type="select"><i class="fa fa-arrows"></i> <?php _e('Select/Dropdown', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-text" data-type="text"><i class="fa fa-arrows"></i> <?php _e('Text field', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-textarea" data-type="textarea"><i class="fa fa-arrows"></i> <?php _e('Textarea', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-email" data-type="email"><i class="fa fa-arrows"></i> <?php _e('Email', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-numeric" data-type="numeric"><i class="fa fa-arrows"></i> <?php _e('Numeric', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-file" data-type="file"><i class="fa fa-arrows"></i> <?php _e('File Upload', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-date" data-type="date"><i class="fa fa-arrows"></i> <?php _e('Date', 'multi-step-form'); ?></a>
                            <a class="fw-draggable-block fw-element-paragraph" data-type="paragraph"><i class="fa fa-arrows"></i> <?php _e('Paragraph', 'multi-step-form'); ?></a>
                            <?php
                            if ( is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' )) {
                                if (Mondula_Form_Wizard_Wizard::fw_get_option( 'regex_enable' ,'fw_settings_conditional' ) === 'on' ) {
                                ?>
                                    <a class="fw-draggable-block fw-element-regex" data-type="regex"><i class="fa fa-arrows"></i> <?php _e('Regex', 'multi-step-form'); ?></a>
                                <?php
                                }

                                if (Mondula_Form_Wizard_Wizard::fw_get_option( 'registration_enable' ,'fw_settings_registration' ) === 'on' ) {
                                ?>
                                    <a class="fw-draggable-block fw-element-registration" data-type="registration"><i class="fa fa-arrows"></i> <?php _e('Registration', 'multi-step-form'); ?></a>
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
        <div id="fw-thickbox-radio"><?php _e('Radio/Checkbox', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-select"><?php _e('Select/Dropdown', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-text"><?php _e('Text field', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-email"><?php _e('Email', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-numeric"><?php _e('Numeric', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-fileupload"><?php _e('File Upload', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-textarea"><?php _e('Textarea', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-date"><?php _e('Date', 'multi-step-form'); ?></div>
        <div id="fw-thickbox-paragraph"><?php _e('Paragraph', 'multi-step-form'); ?></div>
        <?php
            if ( is_plugin_active( 'multi-step-form-plus/multi-step-form-plus.php' ) ) {
                if (Mondula_Form_Wizard_Wizard::fw_get_option( 'regex_enable' ,'fw_settings_conditional' ) === 'on' ) {
                ?>
                <div id="fw-thickbox-regex"><?php _e('Regex', 'multi-step-form'); ?></div>
                <?php
                }
                if (Mondula_Form_Wizard_Wizard::fw_get_option( 'registration_enable' ,'fw_settings_registration' ) === 'on' ) {
                ?>
                <div id="fw-thickbox-registration"><?php _e('Registration', 'multi-step-form'); ?></div>
                <?php
                }
            }
        ?>
    </div>
</div>
