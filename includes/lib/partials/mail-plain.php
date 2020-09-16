<?php
    /**
	 * Default Plain Text Mail Template.
	 * 
	 * To override this file, add a "mail-plain.php" file to a "multi-step-form" directory in your
	 * theme directory. 
     * 
	 * $data contains the filled form data.
	 * $headline contains a message that can be set per form.
	 */
    if (!defined('ABSPATH')) exit;

    echo $headline . PHP_EOL . PHP_EOL;

    foreach ($data as $key => $value) {
        echo PHP_EOL .  $key . PHP_EOL . PHP_EOL;
        foreach ($value as $value2) {
            foreach ($value2 as $key2 => $value3) {
                $value3 = str_replace("\n", "\n\t\t", $value3);
                echo "\t" . $key2 . " - " . $value3 . PHP_EOL;
            }
        }
        echo PHP_EOL;
    }

    echo PHP_EOL . _e('End of form submission', 'multi-step-form') . PHP_EOL;
    echo "Multi Step Form | powered by Mondula GmbH ";
    echo date("Y");
