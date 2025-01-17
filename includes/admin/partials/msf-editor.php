<?php
    if (!defined('ABSPATH')) exit;

    $block_types = Mondula_Form_Wizard_Block::get_block_types();
?>

<div id="fw-alert">
    <?php _e('Success. Form saved.', 'multi-step-form'); ?>
</div>
<div class="wrap">
    <span class="h2 nav-tab-wrapper">
        <a class="nav-tab nav-tab-active" id="fw-nav-steps"><?php _e('Steps', 'multi-step-form'); ?></a>
        <a class="nav-tab" id="fw-nav-settings"><?php _e('Form settings', 'multi-step-form'); ?></a>
</span>
    <div class="fw-mail-settings-container" style="display:none;">
        <div class="wrap">
            <span class="msf-h1"><?php _e('General settings', 'multi-step-form'); ?></span>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('"Thank you"-page:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-settings-thankyou" />
                        <p class="description"><?php _e('Users will be redirected to this URL after form submit. Leave blank if you don\'t need one.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
            </table>
            <span class="msf-h1"><?php _e('Mail settings', 'multi-step-form'); ?></span>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Send To:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-mail-to" />
                        <p class="description"><?php _e('Email address to which the mails are sent', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send From Email:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-mail" />
                        <p class="description"><?php _e('Email address and name from which the emails are sent. Leave blank for default admin email.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send From Name:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-mail-from-name" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Subject:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-mail-subject" />
                        <p class="description"><?php _e('If enabled, you can use string replacements in this field.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Reply-To:', 'multi-step-form'); ?></th>
                    <td>
                        <select class="fw-mail-replyto">
                            <option value="no-reply" selected>No Reply-To</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Send copy to user:', 'multi-step-form'); ?></th>
                    <td>
                        <select class="fw-mail-usercopy">
                            <option value="no-usercopy" selected>No Copy</option>
                        </select>
                        <p class="description"><?php _e('Should a copy of the submitted data be send to the user?', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('If yes, select an email field in which the user should enter her/his email.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('This email will then be used for the copy.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top"
                <?php if (!is_plugin_active('multi-step-form-plus/multi-step-form-plus.php')) { echo 'style="display: none;"'; } ?>
                >
                    <th scope="row"><?php _e('(BETA)', 'multi-step-form'); ?> <?php _e('Double Opt-In:', 'multi-step-form'); ?></th>
                    <td>
                        <select class="fw-mail-optin">
                            <option value="no-optin" selected>No Double Opt-In</option>
                        </select>
                        <p class="description msf-warn"><?php _e('This is a beta function. Please be aware that there may occur problems.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('If you enable double opt-in, instead of the normal e-mail an opt-in request e-mail is generated and send to the e-mail the user has entered in the field specified here.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('The entry is saved but displayed as pending. The opt-in request e-mail contains a link that the user has to open. Only after that the normal e-mail is generated and send. Also the entry is displayed as verified.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('You can specify how long pending entries are stored before they get deleted automatically in the settings.', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('Make sure that the e-mail field has the required flag set. If not, the user may send the form without filling out the required email field. This causes an error, as no opt-in request can be send.', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('This feature currently does NOT work with registration and file upload fields.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top"
                <?php if (!is_plugin_active('multi-step-form-plus/multi-step-form-plus.php')) { echo 'style="display: none;"'; } ?>
                >
                    <th scope="row"><?php _e('Double Opt-In Success Page:', 'multi-step-form'); ?></th>
                    <td>
                        <input type="text" class="fw-mail-optin-success" />
                        <p class="description msf-warn"><?php _e('This is a beta function. Please be aware that there may occur problems.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('This field is only used if double opt-in is active. In that case the user will be redirected to this URL after double opt-in was validated.', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('If double opt-in is active and you leave this field blank, the user will be redirected to the homepage.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Additional Email Headers:', 'multi-step-form'); ?></th>
                    <td>
                        <textarea rows="4" cols="55" class="fw-mail-headers"></textarea>
                        <p class="description"><?php _e('You can add additional email headers for CC/BCC or others. One per line e.g.:', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('CC: John Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('BCC: Jane Doe &lt;doe@example.com&gt;', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('WARNING: only enter custom email headers if you know what you\'re doing - it can break your form.', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('These settings may cause more problems on Windows Servers.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Email Headline:', 'multi-step-form'); ?></th>
                    <td>
                        <textarea rows="5" cols="55" class="fw-mail-header"></textarea>
                        <p class="description"><?php _e('Introductory text for email', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('If enabled, you can use string replacements in this field.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('String replacements:', 'multi-step-form'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" class="fw-mail-string-replacement" />
                            Enable string replacements
                        </label>
                        <p class="description"><?php _e('If you enable string replacements, you can use a special syntax in the email subject and headline field.', 'multi-step-form'); ?>
                        <?php _e('With that syntax it is possible to insert values entered by the user in this form.', 'multi-step-form'); ?>
                        <?php _e('The syntax uses the curly brackets { and } to mark the start and end of a replacement.', 'multi-step-form'); ?>
                        <?php _e('In between you enter the field label you want to insert: <b>{field label}</b>', 'multi-step-form'); ?></p>
                        <p class="description"><?php _e('Example: Hello {first name} {last name}!', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('Please make sure there is only one field with that label. Also make sure you have written it here exactly as in the form, including case.', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('If you want to use the { sign without a replacement, use \{. This will be reduced to one { sign.', 'multi-step-form'); ?></p>
                        <p class="description msf-warn"><?php _e('If the field is not required and the user enters nothin, the replacement will just disappear without new content.', 'multi-step-form'); ?></p>
                    </td>
                </tr>
            </table>
            <button class="fw-button-save"><?php _e('Save'); ?></button>
        </div>
    </div>
    <div id="fw-elements-container" class="fw-elements-container">
        <div class="postbox-container">
            <div class="metabox-holder">
                <div class="postbox">
                    <span class="msf-h3">
                        <?php echo apply_filters('multi-step-form/title-filter', __('Multi Step Form', 'multi-step-form')); ?>
                    </span>
                    <div class="inside">
                        <div class="fw-elements">
                            <input type="text" class="fw-wizard-title" value="Form Wizard" placeholder="<?php _e('Form Title', 'multi-step-form'); ?>">
                            <a class="fw-element-step"><i class="fa fa-plus"></i> <?php _e('Add Step'); ?></a>
                            <span class="msf-h4"><?php _e('Drag &amp; Drop an element from below to a section', 'multi-step-form'); ?></span>

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
                            <button class="fw-button-save"><?php _e('Save'); ?></button>
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
