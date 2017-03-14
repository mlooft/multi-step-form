<?php


if ( ! defined( 'ABSPATH' ) ) exit;


class Mondula_Form_Wizard_Wizard {

    private $_steps = array();
    private $_settings = array();
    private $_title;

    public function __construct() {
    }

    public function get_settings(){
      return $this->_settings;
    }

    public function set_settings ( $settings ) {
      $this->_settings = $settings;
    }
    
    public function set_title ( $title ) {
      $this->_title = $title;
    }

    /**
     *
     * @param $array $elements Elements of the step to add
     * @return void
     */
    public function add_step ( $steps ) {
        $this->_steps[] = $steps;
    }

    private function _get_class ( $len ) {
        // PRO FEATURE
        // switch ( $len ) {
        //     case 3:
        //         return 'fw-one_third';
        //     case 2:
        //         return 'fw-one_half';
        //     default:
        //         return '';
        // }
        return '';
    }

    public static function fw_get_option($option, $section, $default = '') {
      $options = get_option($section);
      if ( isset( $options[$option] ) )
		    return $options[$option];
	    else
		    return $default;
    }

    private function render_progress_bar () {
        $cnt = count( $this->_steps );
        ?>
<div class="fw-progress-wrap">

    <ul class="fw-progress-bar"
        data-activecolor="<?php echo $this->fw_get_option('activecolor' ,'fw_settings_styling', '#546e7a');?>"
        data-donecolor="<?php echo $this->fw_get_option('donecolor' ,'fw_settings_styling', '#4caf50');?>"
        data-nextcolor="<?php echo $this->fw_get_option('nextcolor' ,'fw_settings_styling', '#aaa');?>"
        data-buttoncolor="<?php echo $this->fw_get_option('buttoncolor', 'fw_settings_styling', '#546e7a');?>">
        <?php
        for ($i = 0; $i < $cnt; $i++) {
            $step = $this->_steps[$i];
            ?>
        <li class="fw-progress-step"
            data-id="<?php echo $i; ?>">
            <span class="progress"></span>
            <span class="txt-ellipsis" data-title="<?php echo $step->render_title(); ?>"><?php echo $step->render_title(); ?></span>
        </li>
            <?php
        }
        ?>
    </ul>
</div>
        <?php
    }

    private function render_step_title ( $parts ) {
        $width = $this->_get_class( count($parts) );
?>
<div class="fw-step-title">
    <?php
        $len = count($parts);
        for ($i = 0; $i < $len; $i++) {
            $part = $parts[$i];
            if ($i > 0 && $part->same_title($parts[$i - 1])) {
                $class = $width . ' fw-title-hidden';
            } else {
                $class = $width;
            }
            ?>
    <div class="fw-step-part-title <?php echo $class; ?>">
            <?php
                $part->render_title();
            ?>
    </div>
            <?php
        }
    ?>
</div>
<?php
    }

    private function render_step_body ( $parts ) {
        $class = $this->_get_class( count($parts) );
        ?>
        <div class="fw-step-body">
            <?php
                $cnt = count( $parts );
                for ( $i = 0; $i < $cnt; $i++ ) {
                    ?>
            <div class="fw-step-part <?php echo $class; ?>" data-partId="<?php echo $i; ?>">
                    <?php
                        $part = $parts[$i];
                        $part->render_body( $i );
                    ?>
            </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }

    private function render_step_parts ( $parts ) {
        $cnt = count( $parts );
        $width = $this->_get_class( $cnt );

        for ($i = 0; $i < $cnt; $i++) {
            $part = $parts[$i];
            if ($i > 0 && $part->same_title($parts[$i - 1])) {
                $hidden = ' fw-title-hidden';
            } else {
                $hidden = '';
            }
            ?>
            <div class="fw-step-part <?php echo $width; ?>" data-partId="<?php echo $i ?>">
                <div class="fw-step-part-title <?php echo $hidden; ?>">
                        <?php
                            $part->render_title();
                        ?>
                </div>
                <div class="fw-step-part-body">
                        <?php
                            $part->render_body( $i );
                        ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     *
     */
    public function render ( $wizardId ) {
        $progressbar = $this->fw_get_option( 'progressbar', 'fw_settings_styling', 'on' ) === 'on';
        $showSummary = Mondula_Form_Wizard_Wizard::fw_get_option('showsummary' ,'fw_settings_email', 'true') === 'true';
        ob_start();
        ?>
        <div id="mondula-multistep-forms" class="fw-wizard" data-stepCount="<?php echo count( $this->_steps )?>" data-wizardid="<?php echo $wizardId ?>">
            <div class="fw-wizard-step-header-container">
                <div class="fw-container" data-redirect="<?php echo $this->_settings['thankyou']?>">
                <?php
                $len = count( $this->_steps );
                for ($i = 0; $i < $len; $i++) {
                    $step = $this->_steps[$i];
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
                    for ($i = 0; $i < $len; $i++) {
                        $step = $this->_steps[$i];
                        ?>
                        <div class="fw-wizard-step" data-stepId="<?php echo $i; ?>">
                            <?php
                                $step->render( $wizardId, $i );
                                if ($i == $len - 1) {
                                  if ($showSummary) {
                                  ?>
                                    <div class="fw-summary-container">
                                      <button type="button" class="fw-toggle-summary">SHOW SUMMARY</button>
                                      <div id="wizard-summary" class="fw-wizard-summary" style="display:none;" data-showsummary="on"><div class="fw-summary-alert">Some required Fields are empty<br>Please check the highlighted fields.</div><div class="fw-step-summary-part"><p class="fw-step-summary-title">Family</p><p class="fw-step-summary"> — few</p><p class="fw-step-summary"> — Deutsch</p><p class="fw-step-summary">I have a dog — yes</p></div><div class="fw-step-summary-part"><p class="fw-step-summary-title">About you</p><p class="fw-step-summary"> — gew</p></div><div class="fw-step-summary-part"><p class="fw-step-summary-title">Food</p><p class="fw-step-summary"> — Burgers</p></div><div class="fw-step-summary-part"><p class="fw-step-summary-title">Information</p><p class="fw-step-summary"></p><p class="fw-step-summary fw-summary-invalid">I would like to recieve the weekly newsletter — </p><p></p></div><div class="fw-step-summary-part"><p class="fw-step-summary-title">Terms of Service</p><p class="fw-step-summary"></p><p class="fw-step-summary fw-summary-invalid">I agree to the ToS — </p><p></p></div><div class="fw-step-summary-part"><p class="fw-step-summary-title">Submit your Data</p><p class="fw-step-summary"></p><p class="fw-step-summary fw-summary-invalid"> — </p><p></p></div></div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                  <button type="button" class="fw-btn-submit">Submit</button>
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
                        <button class="fw-button-previous"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> &nbsp;<?php _e( 'Previous Step' ) ?></button>
                        <button class="fw-button-next"><?php _e( 'Next Step' ) ?> &nbsp;<i class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="fw-alert-user" style="display:none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_header_html () {
        ?>
        <html><body>
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody><tr>
              <td bgcolor="#ffffff" align="center" style="padding: 20px 15px 70px;" class="section-padding">
                  <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                      <tbody><tr>
                          <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tbody><tr>
                                      <td>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tbody><tr>
                                                  <td align="left" style="font-size: 22px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy"><?php echo $this->_settings['header']?></td>
                                              </tr>
        <?php
    }

    private function render_header () {
        echo $this->_settings['header'] . PHP_EOL . PHP_EOL;
    }

    private function render_body_html( $data, $name, $email ){
                                              foreach ( $data as $key => $value ) {
                                                  echo '<tr><td align="left" style="padding: 30px 0 10px 0; font-size: 20px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy"><strong>' . $key . '</strong> </td></tr>';
                                                  foreach ( $value as $value2 ) {
                                                      foreach ( $value2 as $key2 => $value3 ) {
                                                          echo '<tr><td align="left" style="border:solid 1px #dadada; border-width:0 0 1px 0; padding: 10px 0 10px 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'. $key2 .'</td><td align="left" style=" border:solid 1px #dadada; border-width:0 0 1px 0; 10px 0 10px 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'. $value3 .'</td></tr>';
                                                      }
                                                  }
                                              } ?>
                                          </tbody></table>
                                      </td>
                                  </tr>
                              </tbody></table>
                          </td>
                      </tr>
                  </tbody></table>
              </td>
          </tr>
      </tbody></table>
      <?php
    }

    private function render_body ( $data, $name, $email ) {
        foreach ( $data as $key => $value ) {
            echo PHP_EOL .  $key . PHP_EOL . PHP_EOL;
            foreach ( $value as $value2 ) {
                foreach ( $value2 as $key2 => $value3 ) {
                    echo "\t" . $key2 . " - " . $value3 . PHP_EOL;
                }
            }
            echo PHP_EOL;
        }

        echo PHP_EOL . "Name: " . $name . PHP_EOL;
        echo "Email: " . $email . PHP_EOL;
    }

    private function render_footer () {
        echo PHP_EOL . "End of form submission" . PHP_EOL;
        echo "Multi Step Form | powered by Mondula GmbH ";
        echo date("Y");
    }

    private function render_footer_html() {
       ?>
       <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody><tr>
            <td bgcolor="#f5f5f5" align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tbody><tr>
                        <td style="padding: 40px 0px 40px 0px;">
                            <!-- UNSUBSCRIBE COPY -->
                            <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                                <tbody><tr>
                                    <td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                                        <span class="appleFooter" style="color:#666666;">Multi Step Form | powered by <a href="http://mondula.com">Mondula GmbH</a> <?php echo date("Y"); ?></span>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
    </tbody></table>
    </body></html>
    <?php
    }

    public function render_mail ( $data, $name, $email, $mailformat ) {
        if ($mailformat == 'text') {
          ob_start();
          $this->render_header();
          $this->render_body( $data, $name, $email );
          $this->render_footer();
        } else {
          ob_start();
          $this->render_header_html();
          $this->render_body_html( $data, $name, $email );
          $this->render_footer_html();
        }
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    public function as_aa() {
        $steps_json = array();
        foreach ($this->_steps as $step) {
            $steps_json[] = $step->as_aa();
        }
        return array(
          'title' => $this->_title,
          'steps' => $steps_json,
          'settings' => $this->_settings
        );
    }

    public static function from_aa( $aa, $current_version, $serialized_version ) {
        $wizard = new Mondula_Form_Wizard_Wizard();
        $wizard->set_settings( $aa['settings'] );
        $wizard->set_title( $aa['title'] );
        foreach ( $aa['steps'] as $step ) {
            $wizard->add_step(
                Mondula_Form_Wizard_Wizard_Step::from_aa( $step, $current_version, $serialized_version )
            );
        }
        return $wizard;
    }


}
