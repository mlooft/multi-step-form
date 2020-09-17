<?php
    if (!defined('ABSPATH')) exit;
?>

<style>
    .wrap {
        width: 75%;
        float: left;
        margin-right: 0px;
    }

    #msf-sidebar-container {
        width: 260px;
        padding: 0 0 0 30px;
        float: left;
        width: 20%;
        margin-top: 20px;
    }

    #sidebar {
        padding: 10px 15px 20px 15px;
        background:#fff;
        box-sizing: border-box;
        border: 1px solid #ddd;
    }

    #sidebar h2 {
        font-size: 22px;
        font-weight: 400;
        margin: 10px 0px 20px 0px;
        color:#00b0a4;
    }

    #sidebar h4 {
        margin: 20px 0px 0px 0px;
        color:#ff6d00;
    }

    #sidebar p {
        color:#777;
        margin: 0px 0px 20px 0px;
    }

    img {
        width: 100%;
        height: auto;
    }
</style>
<div class="msf_content_cell" id="msf-sidebar-container">
    <div id="sidebar">
        <div>
            <h2>Multi Step Form Plus</h2>
            <h4>Up to 10 steps</h4>
            <p>
                You can now divide your form in up to 10 Steps.
            </p>
            <h4>Save and export Form Data</h4>
            <p>
                With PLUS, you can save filled forms in the database and export them as CSV
            </p>
            <h4>Conditional fields</h4>
            <p>
                You want to bring more flexibility into your forms? Use conditional fields.
            </p>
            <p>
                <a href="https://mondula.com/multi-step-form-plus/" target="_blank">
                    <img src="<?php echo plugins_url('assets/images/msf_plus_extension.png', dirname(__FILE__)) ?>" alt="MSF Plus">
                </a>
            </p>
            <p>
                <a class="button button-primary" href="https://mondula.com/multi-step-form-plus/" target="_blank">Get Multi Step Form Plus</a>
            </p>
        </div>
    </div>
</div>
