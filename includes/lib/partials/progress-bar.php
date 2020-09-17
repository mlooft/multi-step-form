<?php
    if (!defined('ABSPATH')) exit;
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
            <span class="fw-progress-bar-bar"></span>
            <span class="fw-txt-ellipsis" data-title="<?php echo $step->render_title(); ?>"><?php echo $step->render_title(); ?></span>
            <?php if ($i == 4 && ($i < ($cnt - 1))): ?>
                <span class="fw-circle"></span>
                <span class="fw-circle-1"></span>
                <span class="fw-circle-2"></span>
            <?php endif; ?>
        </li>
            <?php
        }
        ?>
    </ul>
</div>
