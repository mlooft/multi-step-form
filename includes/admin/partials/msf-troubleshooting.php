<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="wrap">
    <h2><? _e('Multi Step Form Troubleshooting', 'multi-step-form'); ?></h2>

    <h3><? _e('Send Testmail', 'multi-step-form'); ?></h3>
    <p><?php _e('Use the following form to check if the wp_mail function works.'.
                ' This function is used by MSF and it is crucial for sending emails.', 'multi-step-form'); ?></p>
    <form id="msf-testmail" method="post">
        <label for="testmail-receiver">Send to: </label>
        <input type="email" name="testmail-receiver" id="testmail-receiver" required>
        <input name="testmail-submit" class="button button-primary" value="<?php echo __('Send Testmail', 'multi-step-form'); ?>" type="submit">
    </form>

    <?php
        if ($testmail)
        {
            if ($email_result)
            {
                ?>
                <div class="notice notice-success">
                    <p>
                        <?php
                            printf(__('Tried to send an email to <b>%s</b> and the wp_mail function signaled success.<br/>'.
                            'Please check if you received an email at <b>%s</b> from this plugin. If you did not receive one,'.
                            'there might be something wrong with your Wordpress Mail Configuration. The other reason might be, that the E-Mail'.
                            'provider of <b>%s</b> automaticaly detected it as spam.'
                            , 'multi-step-form'), $dest_email, $dest_email, $dest_email);
                        ?>
                    </p>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="notice notice-error">
                    <p>
                        <?php
                            printf(__('Tried to send an email to <b>%s</b> but the'.
                            ' wp_mail function failed.<br/> Please check you Wordpress Mail Configuration '.
                            'as it seems to be broken.'
                            , 'multi-step-form'), $dest_email);
                        ?>
                        
                    </p>
                </div>
                <?php
            }
        }
    ?>

    <h3><? _e('Upload Problems', 'multi-step-form'); ?></h3>
    <p>
        <?php _e('If you have problems with the upload of files, please check '.
                'the "Important System Information" on this page first. Check if all limits and maximum sizes are '.
                'high enough to upload the files you want.', 'multi-step-form'); ?>
    </p>
    <p>
        <?php _e('If you changed these limits, make sure this change is shown in this page. '.
                'Sometimes it is not possible to change these limits in .htaccess files or with '.
                'ini_set() functions, as some webhosting providers block those changes. '.
                'In that case contact your provider and ask for support or a higher upload limit.', 'multi-step-form'); ?>
    </p>

    <h3><? _e('Important System Information', 'multi-step-form'); ?></h3>
    <p><?php _e('Please send us a screenshot of the following information, if you write us because of a technical problem.', 'multi-step-form'); ?></p>

    <b>OS:</b> <?php echo php_uname(); ?><br/>
    <b>Webserver:</b>  <?php echo $_SERVER['SERVER_SOFTWARE']; ?><br/>
    <b>PHP:</b> <?php echo phpversion(); ?><br/>
    <b>MySQL:</b> <?php echo $mysqlVersion; ?><br/>
    <b>Wordpress:</b> <?php echo $wp_version; ?><br/>
    <b>Multi Step Form:</b> <?php echo $msfVersion; ?><br/>
    <b>Multi Step Form Plus:</b> <?php echo $msfpVersion; ?><br/>
    <b>Wordpress Max Upload Size:</b> <?php echo size_format(wp_max_upload_size(), 1); ?><br/>
    <b>PHP Memory Limit:</b> <?php echo ini_get('memory_limit'); ?><br/>
    <b>PHP Post Max Size:</b> <?php echo ini_get('post_max_size'); ?><br/>
    <b>PHP Upload Max Filesize:</b> <?php echo ini_get('upload_max_filesize'); ?><br/>
    <b>PHP Max Input Time:</b> <?php echo ini_get('max_input_time'); ?><br/>
    <b>PHP Max Execution Time:</b> <?php echo ini_get('max_execution_time'); ?><br/>
</div>
