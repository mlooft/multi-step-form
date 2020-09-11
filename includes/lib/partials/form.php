<div id="multi-step-form" class="<?php echo $classes; ?>" data-stepCount="<?php echo count( $this->_steps ); ?>" data-wizardid="<?php echo $wizard_id; ?>">
    <div class="fw-wizard-step-header-container">
        <div class="fw-container" data-redirect="<?php echo $this->_settings['thankyou']; ?>">
        <?php
        $len = count( $this->_steps );
        for ( $i = 0; $i < $len; $i++ ) {
            $step = $this->_steps[ $i ];
            ?>
        <div class="fw-wizard-step-header" data-stepId="<?php echo $i; ?>">
            <h2><?php echo $step->render_headline(); ?></h2>
            <p class="fw-copytext"><?php $step->render_copy_text(); ?></p>
        </div>
        <?php
        }
        ?>
        </div>
    </div>
    <div class="fw-progress-bar-container <?php echo ( $progressbar ? '' : ' fw-hide-progress-bar' ); ?>">
        <div class="fw-container">
    <?php
        $this->render_progress_bar( $this->_steps );
    ?>
        </div>
    </div>
    <div class="fw-wizard-step-container">
        <div class="fw-container">
        <?php
        for ( $i = 0; $i < $len; $i++ ) {
            $step = $this->_steps[ $i ];
            ?>
            <div class="fw-wizard-step" data-stepId="<?php echo $i; ?>">
                <?php
                $step->render($wizard_id, $i);
                if ($i == $len - 1) {
                    if ($show_summary) {
                        ?>
                        <div class="fw-summary-container">
                            <button type="button" class="fw-toggle-summary"><?php _e( 'SHOW SUMMARY', 'multi-step-form' ) ?></button>
                            <div id="wizard-summary" class="fw-wizard-summary" style="display:none;" data-showsummary="on">
                            <div class="fw-summary-alert"><?php _e( 'Some required Fields are empty', 'multi-step-form' ); ?><br><?php _e('Please check the highlighted fields.', 'multi-step-form') ?></div>
                            </div>
                        </div>
                        <?php
                    }
                    if ($use_captcha) {
                        ?>
                        <input 
                            type="hidden"
                            class="msf-recaptcha-token"
                            data-sitekey="<?php echo $captcha_key; ?>"
                            data-invisible="<?php echo $captcha_invisible ? "true" : "false"; ?>"
                        >
                        <br/><br/>
                        <div 
                            class="msf-recaptcha-element"
                            <?php 
                            if ($captcha_invisible)
                            {
                                echo 'data-size="invisible"';
                            } 
                        ?>
                        ></div>
                        <br/>
                        <?php
                    }
                    ?>
                    <button type="button" class="fw-btn-submit"><?php _e( 'Submit', 'multi-step-form' ); ?></button>
                <?php
                }
                ?>
                <div class="fw-clearfix"></div>
            </div>
            <?php
            }
        ?>
        </div>
    </div>
    <?php if (count($this->_steps) > 1) { ?>
    <div class="fw-wizard-button-container">
        <div class="fw-container">
            <div class="fw-wizard-buttons">
                <button class="fw-button-previous"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> &nbsp;<?php _e( 'Previous Step', 'multi-step-form' ) ?></button>
                <button class="fw-button-next"><?php _e( 'Next Step', 'multi-step-form' ) ?> &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="fw-alert-user" style="display:none;"></div>
</div>
