<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class-mondula-form-wizard-admin
 *
 * @author alex
 */
class Mondula_Form_Wizard_Admin {

    private $_wizard_service;

    private $_token;

    private $_assets_url;

    private $_script_suffix;

    private $_version;

    private $_text_domain;

    public function __construct ( Mondula_Form_Wizard_Wizard_Service $wizard_service, $token, $assets_url, $script_suffix, $version, $text_domain ) {
        $this->_wizard_service = $wizard_service;
        $this->_token = $token;
        $this->_assets_url = $assets_url;
        $this->_script_suffix = $script_suffix;
        $this->_version = $version;
        $this->_text_domain = $text_domain;
        $this->init();
    }

    private function init () {
        add_action( 'admin_menu', array( $this, 'setup_menu' ) );

        add_action( 'wp_ajax_fw_wizard_save', array( $this, 'save' ) );
        add_action( 'wp_ajax_nopriv_fw_wizard_save', array( $this, 'save' ) );
    }

    public function setup_menu () {
        $all = add_menu_page( 'Mondula Form Wizard', 'Form Wizards', 'manage_options', 'mondula-form-wizard', array( $this, 'menu' ), 'dashicons-feedback', '35' );
        $add = add_submenu_page( 'mondula-form-wizard', 'Mondula List Table', 'Add New', 'manage_options', 'mondula-form-wizard&edit', array( $this, 'menu' ));

        add_action( 'admin_print_styles-' . $all, array( $this, 'admin_js' ) );
        add_action( 'admin_print_styles-' . $add, array( $this, 'admin_js' ) );
    }

    public function admin_js() {
        $edit = isset( $_GET['edit'] );

        if ( $edit ) {
            $id = isset( $_GET['edit'] ) ? $_GET['edit'] : '';
            $json = $this->_wizard_service->get_as_json( $id );
            $i18n = array(
                'title' => __( 'Title', $this->_text_domain ),
                'headline' => __( 'Headline', $this->_text_domain ),
                'copyText' => __( 'Step description', $this->_text_domain  ),
                'partTitle' => __( 'Section Title', $this->_text_domain ),
                'radioHeader' => __( 'Header', $this->_text_domain ),
                'radioHeading' => __( 'Radio Buttons', $this->_text_domain)
            );
            wp_register_script( $this->_token . '-wizard-admin', esc_url( $this->_assets_url ) . 'js/wizard-admin' . $this->_script_suffix . '.js', array( 'postbox', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-tooltip', 'jquery' ), $this->_version );
            $ajax = array(
                'i18n' => $i18n,
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'id' => $id,
                'nonce' => wp_create_nonce( $this->_token . $id ),
                'json' => $json
            );
            wp_localize_script( $this->_token . '-wizard-admin', 'wizard', $ajax ); // array( 'i18n' => $i18n, 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'json' => $json ) );

            wp_enqueue_script( $this->_token . '-wizard-admin');

            wp_register_style( $this->_token . '-wizard-admin', esc_url( $this->_assets_url ) . 'css/wizard-admin.css', array(), $this->_version );
            wp_enqueue_style( $this->_token . '-wizard-admin' );

            // wp_enqueue_style( 'wp-jquery-ui-dialog' );
        }
    }

    public function menu () {
        $edit_url = esc_url( add_query_arg( array('edit' => '')) );
        $edit = isset($_GET['edit']);
        $delete = isset( $_GET['delete'] );

        if ($edit) {
            $this->edit( $_GET['edit'] );
        } else if ($delete) {
            $this->delete( $_GET['delete'] );
        } else {
//            $this->wizard_list();
            $this->table();
        }
    }

    public function delete( $id ) {
        $this->_wizard_service->delete( $id );
        $this->table();
    }

    public function table ( ) {
        $table = new Mondula_Form_Wizard_List_Table( $this->_wizard_service, $this->_text_domain );
        $table->prepare_items();
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2>Form Wizards</h2>
            <form id="fw-wizard-table" method="get">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $table->display(); ?>
            </form>
        </div>
        <?php
    }

    public function wizard_list() {
        $edit_url = esc_url( add_query_arg( array('edit' => '')) );
        ?>
        <div class="wrap">
            <h2>Wizard <a class="add-new-h2" href="<?php echo $edit_url; ?>">Add New</a></h2>
            <ul class="subsubsub"></ul>
            <form>

                <div class="tablenav top"></div>
                <table class="wp-list-table widefat fixed striped posts">
                    <thead>
                        <tr>
                            <th scope="col">Titel</th>
                            <th scope="col">Shortcode</th>
                            <th scope="col">Datum</th>
                        </tr>
                    </thead>
                    <?php

                    ?>
                </table>
            </form>
        </div>
        <?php
    }

    public function edit( $id ) {
        echo esc_url( add_query_arg( array('edit' => '') ) );
        add_thickbox();
        ?>
        <div class="wrap">
            <!--<pre>
                <?php //var_dump( $_GET ); var_dump( empty($id ) ); ?>
            </pre> -->
            <!--<button type="button" class="fw-button-save">Save</button>-->
            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" id="fw-nav-steps">Steps</a>
                <a class="nav-tab" id="fw-nav-mail">Mail Settings</a>
            </h2>
            <div class="fw-mail-settings-container" style="display:none;">
              <div class="wrap">
                      <table class="form-table">
                          <tr valign="top">
                          <th scope="row">Send Mails To:</th>
                          <td>
                            <input type="text" class="fw-mail-to"/>
                            <p class="description">Email address to which the mails are sent</p>
                          </td>
                          </tr>
                          <tr valign="top">
                          <th scope="row">Subject:</th>
                          <td>
                            <input type="text" class="fw-mail-subject"/>
                          </td>
                          </tr>

                          <tr valign="top">
                          <th scope="row">Email Header:</th>
                          <td>
                            <textarea rows="5" cols="55" class="fw-mail-header"></textarea>
                            <p class="description">Introductory text for email</p>
                          </td>
                          </tr>
                      </table>
                      <button class="button button-primary button-large fw-button-save"><?php _e( 'Save' ) ?></button>
              </div>
            </div>
            <div id="fw-elements-container" class="fw-elements-container">
                <div class="postbox-container">
                    <div class="metabox-holder">
                        <div class="postbox">
                            <h3>Mondula Form Wizard</h3>
                            <div class="inside">
                                <div class="fw-elements">
                                    <input type="text" class="fw-wizard-title" value="Form Wizard" placeholder="Wizard Title">
                                    <a class="fw-element-step button-secondary"><i class="fa fa-plus"></i> <?php _e( 'Add Step' ) ?></a>
                                    <h4>Drag & drop elements to steps on the right</h4>
                                    <a class="fw-draggable-block fw-element-radio button-secondary" data-type="radio"><i class="fa fa-arrows"></i> Radio Buttons</a>
                                    <a class="fw-draggable-block fw-element-checkbox button-secondary" data-type="checkbox"><i class="fa fa-arrows"></i> Checkbox</a>
                                    <a class="fw-draggable-block fw-element-text button-secondary" data-type="text"><i class="fa fa-arrows"></i> Text field</a>
                                    <a class="fw-draggable-block fw-element-textarea button-secondary" data-type="textarea"><i class="fa fa-arrows"></i> Text Area</a>
                                    <a class="fw-draggable-block fw-element-submit button-secondary" data-type="submit"><i class="fa fa-arrows"></i> Submit</a>
                                </div>
                                <div class="fw-actions">
                                    <button class="button button-primary button-large fw-button-save"><?php _e( 'Save' ) ?></button>
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
              <div id="fw-thickbox-radio">Radio Buttons</div>
              <div id="fw-thickbox-checkbox">Checkbox</div>
              <div id="fw-thickbox-text">Text Field</div>
              <div id="fw-thickbox-textarea">Text Area</div>
              <div id="fw-thickbox-submit">Submit</div>
            </div>

        </div>
        <?php
    }

    public function save() {
        // var_dump( $_POST );
        // wp_die('success');
        $id = isset( $_POST['id'] ) ? $_POST['id'] : '';
        $nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
        $data = isset( $_POST['data'] ) ? $_POST['data'] : array();

        if ( wp_verify_nonce( $nonce, $this->_token . $id ) ) {
           if ( ! empty( $data ) ) {
               $this->_wizard_service->save( $id, $data );
               $response['msg'] = "Success! Wizard saved.";
               wp_send_json_success($response);
           } else {
               wp_send_json_error( array( "errorMsg" => "Data is empty." ) );
           }
        } else {
            wp_send_json_error( array( "errorMsg" => "Nonce failed to verify." ) );
        }

        wp_send_json_error( array( "errorMsg" => 'error' ) );
    }
}
