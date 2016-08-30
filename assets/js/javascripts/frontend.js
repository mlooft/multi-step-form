jQuery(document).ready(function($) {
    "use strict";
    /*jshint validthis: true */

    var data = {};

    function log() {
        if (window.console) console.log.apply(console, arguments);
    }

    function warn() {
        if (window.console) console.warn.apply(console, arguments);
    }

    // <editor-fold desc="Navigation">
    function hideStep($wizard, stepId) {
        var $progress;
        $wizard.find('.fw-wizard-step[data-stepId="' + stepId + '"]')
            .removeClass('fw-current');
        $wizard.find('.fw-wizard-step-header[data-stepId="' + stepId + '"]')
            .removeClass('fw-current');
        $progress = $wizard.find('.fw-progress-step[data-id="' + stepId + '"]');
        $progress.removeClass('fw-active');
        $wizard.find('.fwp-progress-bar .fwp-circle[data-id="' + stepId + '"]').removeClass('fwp-active');
    }

    function showStep($wizard, stepId) {
        var $progress;
        $wizard.find('.fw-wizard-step[data-stepId="' + stepId + '"]')
            .addClass('fw-current');
        $wizard.find('.fw-wizard-step-header[data-stepId="' + stepId + '"]')
            .addClass('fw-current');
        $progress = $wizard.find('.fw-progress-step[data-id="' + stepId + '"]');
        $progress.addClass('fw-active');
        $wizard.find('.fw-button-next').html('Schritt ' + (stepId + 2));
        $wizard.find('.fw-button-previous').html('Schritt ' + (stepId));
    }

    function disablePrevious($wizard) {
        // $wizard.find('.fw-button-previous').prop('disabled', true);
        $wizard.find('.fw-button-previous').hide();
    }

    function disableNext($wizard) {
        // $wizard.find('.fw-button-next').prop('disabled', true);
        $wizard.find('.fw-button-next').hide();
    }

    function enablePrevious($wizard) {
        // $wizard.find('.fw-button-previous').prop('disabled', false);
        $wizard.find('.fw-button-previous').show();
    }

    function enableNext($wizard) {
        // $wizard.find('.fw-button-next').prop('disabled', false);
        $wizard.find('.fw-button-next').show();
    }

    function previous() {
        var $wizard = getWizard($(this));
        var step = getStep($wizard);
        var stepInt = parseInt(step, 10);
        var $circle = $wizard.find('.fwp-progress-bar .fwp-circle[data-id="' + step + '"]');
        var $bar = $wizard.find('.fwp-progress-bar .fwp-bar[data-id="' + (stepInt - 1) + '"]');
        var $progress = $wizard.find('.fw-progress-step[data-id="' + (stepInt - 1) + '"]');
        $progress.removeClass('fw-visited');
        $wizard.find('.fw-progress-step[data-id="' + step + '"]').removeClass('fw-visited');
        $circle.removeClass('fwp-done');
        $circle.find('.fwp-label').html(parseInt(step, 10) + 1);
        $bar.removeClass('fwp-active');
        if (stepInt >= 2) {
            $wizard.find('.fwp-progress-bar .fwp-bar[data-id="' + (stepInt - 2) + '"]')
                .removeClass('fwp-done').addClass('fwp-active');

        }
        hideStep($wizard, step--);
        $wizard.find('.fwp-progress-bar .fwp-circle[data-id="' + step + '"]')
            .find('.fwp-label').html(parseInt(step, 10) + 1);
        showStep($wizard, step);
        if (step === 0) {
            disablePrevious($wizard);
        }
        enableNext($wizard);
    }

    function next() {
        console.log("Moin!");
        var $wizard = getWizard($(this));
        var step = getStep($wizard);
        var stepInt = parseInt(step, 10);
        var $circle = $wizard.find('.fwp-progress-bar .fwp-circle[data-id="' + step + '"]');
        var $bar = $wizard.find('.fwp-progress-bar .fwp-bar[data-id="' + step + '"]');
        $wizard.find('.fw-progress-step[data-id="' + step + '"]').addClass('fw-visited');
        $circle.removeClass('fwp-active').addClass('fwp-done');
        $circle.find('.fwp-label').html('&#10003;');
        $bar.addClass('fwp-active');
        if (stepInt >= 1) {
            $wizard.find('.fwp-progress-bar .fwp-bar[data-id="' + (stepInt - 1) + '"]')
                .removeClass('fwp-active').addClass('fwp-done');
        }
        hideStep($wizard, step++);
        showStep($wizard, step);
        if (step === (getStepCount($wizard) - 1)) {
            disableNext($wizard);
        }
        enablePrevious($wizard);
    }

    // </editor-fold>

    // <editor-fold desc="Summary">
    function stepSummary($wizard, stepNum, summaryObj) {
        var d = data[$wizard.attr('id')][stepNum];
        var summary = '';
        var part, block, prop, title, header, value;
        var $title, $block;
        var $step = $wizard.find('.fw-wizard-step[data-stepId="' + stepNum + '"]');
        $step.find('.fw-step-part').each(function(idx, element) {
            var title = $(element).find('.fw-step-part-title').text().trim();
            log(title);
        });
        //        if (d) {
        //            for (part in d) {
        //                for (block in d[part]) {
        //                    for (prop in d[part][block]) {
        //                        val = d[part][block][prop];
        //                        if (val === "checked") {
        //                            $title = $($step.find('.fw-step-part-title')[part]);
        //                            title = $title.text().trim();
        //                            $block = $step.find('[data-partId="' + part + '"]')
        //                                .find('[data-blockId="' + block + '"]');
        //                            header = $block.find('.fw-block-header').text();
        //                            log(header);
        //                            if (header) {
        //                                header = header.trim();
        //                            }
        //                            value = $block.find('[data-labelId="' + prop + '"]').text();
        //                            if (value) {
        //                                value = value.trim();
        //                            }
        //                            s = {};
        //                            s[header] = value;
        //                            getArray(summaryObj, title).push(s);
        //                        }
        //                    }
        //                }
        //            }
        //        }
        return summary;
    }

    function getSummary($wizard) {
        var i;
        var stepCount = getStepCount($wizard);
        var summaryObj = {};
        for (i = 0; i < stepCount; i++) {
            stepSummary($wizard, i, summaryObj);
        }
        return summaryObj;
    }

    function getSummaryHtml($wizard) {
        var summaryHtml = '';
        var summaryObj = getSummary($wizard);
        for (var key in summaryObj) {
            summaryHtml += '<div class="fw-step-summary-part">';
            summaryHtml += renderStepSummaryTitle(key);
            summaryHtml += renderStepSummaries(summaryObj[key]);
            summaryHtml += '</div>';
        }
        return summaryHtml;
    }

    function renderStepSummaryTitle(title) {
        return '<p class="fw-step-summary-title">' + title + '</p>';
    }

    function renderStepSummary(summary) {
        var key;
        for (key in summary) {
            return '<p class="fw-step-summary">' + key + ' \u2014 ' + summary[key] + '</p>';
        }
    }

    function renderStepSummaries(summaries) {
        var i, n;
        var result = '';
        for (i = 0, n = summaries.length; i < n; i++) {
            result += renderStepSummary(summaries[i]);
        }
        return result;
    }

    function updateSummary($wizard) {
        var summary = getSummaryHtml($wizard);
        var $summary = $wizard.find('.fw-wizard-summary');
        $summary.empty();
        $summary.append(summary);
    }

    // </editor-fold>

    function getWizard($elt) {
        return $elt.closest('.fw-wizard');
    }

    function getStepCount($wizard) {
        return $wizard.attr('data-stepCount');
    }

    function getStep($wizard) {
        return $wizard.find('.fw-current').attr('data-stepId');
    }

    function getStepId($elt) {
        return $elt.closest('.fw-wizard-step').attr('data-stepId');
    }

    function getPartId($elt) {
        return $elt.closest('.fw-step-part').attr('data-partId');
    }

    function getBlockId($elt) {
        return $elt.closest('.fw-step-block').attr('data-blockId');
    }

    function get(obj) {
        var args = [].slice.call(arguments, 1);
        var i = 0,
            n = args.length;
        log('args', args);
        if (args[0] === "0") {
            throw new TypeError();
        }
        for (; i < n; i++) {
            obj = _get(obj, args[i]);
        }
        return obj;
    }

    function _get(obj, prop) {
        if (!obj[prop]) {
            obj[prop] = {};
        }
        return obj[prop];
    }

    function getObj($wizard, $target) {
        var stepId = getStepId($target);
        var partId = getPartId($target);
        var blockId = getBlockId($target);
        return get(data, $wizard.attr('id'), stepId, partId, blockId);
    }

    function getArray(obj, prop) {
        log('obj', obj, 'prop', prop);
        if (!obj[prop]) {
            obj[prop] = [];
        }
        return obj[prop];
    }

    // <editor-fold desc="Event handling">
    function check() {
        var $target = $(this);
        var $wizard = getWizard($target);
        var checked = $target.prop('checked');
        var obj = getObj($wizard, $target);
        var optId = $target.attr('data-id');
        if (checked) {
            obj[optId] = "checked";
        } else {
            delete(obj[optId]);
        }

        updateSummary($wizard);
    }

    function unset($wizard, $target) {
        var wizardId = $wizard.attr('id');
        var group = $target.attr("name");
        var blockId = getBlockId($target);
        var $radios = $('.fw-radio[name="' + group + '"]');
        var multi = false;
        var stepId, partId, obj;
        log('radios', $radios);
        $radios.each(function(idx, element) {
            if (blockId !== getBlockId($(element))) {
                multi = true;
            }
        });
        stepId = getStepId($target);
        partId = getPartId($target);

        if (multi) {
            obj = get(data, wizardId, stepId);
            delete(obj[partId]);
        } else {
            obj = get(data, wizardId, stepId, partId);
            delete(obj[blockId]);
        }

    }

    function checkConditional() {
        var $target = $(this);
        var id = $target.attr('data-id');
        var $conditional = $target.closest('.fw-conditional');

        $conditional.find('.fw-conditional-then').removeClass('fw-selected');

        $conditional.find('.fw-conditional-then[data-id="' + id + '"]').addClass('fw-selected');

    }

    function checkRadio() {
        var $target = $(this);
        var $wizard = getWizard($target);
        unset($wizard, $target);
        var obj = getObj($wizard, $target);
        var optId = $target.attr('data-id');
        obj[optId] = "checked";

        updateSummary($wizard);
    }

    function textOnChange() {
        var $target = $(this);
        var $wizard = getWizard($target);
        var value = $target.val();
        log(value);
        var id = $target.attr('data-id');
        var obj = getObj($wizard, $target);
        obj[id] = value;
    }

    function dump() {
        var $wizard = getWizard($(this));
        log('step', getStep($wizard));
        log('stepCount', getStepCount($wizard));
        log('data', data);
        log('summary', getSummary($wizard));
    }

    // </editor-fold>

    function validate($wizard) {
        var valid = true;
        $wizard.find('input[type="text"][data-required]').each(
            function(i, element) {
                var $element = $(element);
                if (!$element.val()) {
                    valid = false;
                    $element.addClass('fw-invalid');
                }
            }
        );
        return valid;
    }

    function submit(evt) {
        var summary, name, email;
        var $wizard = $(this).closest('.fw-wizard');
        log($wizard);
        if (validate($wizard)) {
            summary = getSummary($wizard);
            name = $wizard.find('[data-id="name"]').val();
            email = $wizard.find('[data-id="email"]').val();
            post(summary, name, email);
        }
    }

    function post(summary, name, email) {
        $.post(
            ajax.ajaxurl, {
                action: 'fw_send_email',
                fw_data: summary,
                name: name,
                email: email,
                nonce: ajax.nonce
            },
            function(resp) {
                log('resp', resp);
            }
        ).fail(function(resp) {
            warn('response', resp);
            warn('responseText', resp.responseText);
        });
    }

    function setup() {
        $('.fw-wizard').each(function(idx, element) {
            showStep($(element), 0);
        });

        $('.fw-progress-step[data-id="0"]').addClass('fw-active');
        $('.fw-button-previous').hide(); // prop('disabled', true);
        $('.fw-button-previous').click(previous);
        $('.fw-button-next').click(next);

        // TODO remove me
        $('.fw-button-dump').click(dump);

        $('.fw-checkbox').change(check);
        $('.fw-radio').change(checkRadio);
        $('.fw-radio-conditional').change(checkConditional);
        $('.fw-text-input').on('change input', textOnChange);
        $('.fw-btn-submit').click(submit);
        log(ajax);
    }

    $(document).ready(function() {
        setup();
    });
});
