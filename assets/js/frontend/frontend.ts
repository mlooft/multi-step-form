/// <reference path="../../../node_modules/@types/jqueryui/index.d.ts" />
/// <reference path="../../../node_modules/@types/select2/index.d.ts" />

declare var msfAjax: any;
declare interface Window { grecaptcha: any };

jQuery(document).ready(function ($) {
	"use strict";

	let data = {};

	let captchaId : any = null;
	let useCaptcha : boolean = false;
	let invisibleCaptcha : boolean = false;

	const logStyle = "color: white; background-color: purple; padding: 3px; display: block; line-height: 25px; border-radius: 2px;";

	/**
	 * Shows a log message if the console is available.
	 * 
	 * @param args data to display
	 */
	function log(...args: any[]) {
		if (window.console) console.log.apply(console, ["%cMSF", logStyle, ...args]);
	}

	/**
	 * Shows a warn message if the console is available.
	 * 
	 * @param args data to display
	 */
	function warn(...args: any[]) {
		if (window.console) console.warn.apply(console, ["%cMSF", logStyle, ...args]);
	}

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
	}

	function disablePrevious($wizard) {
		$wizard.find('.fw-button-previous').hide();
	}

	function disableNext($wizard) {
		$wizard.find('.fw-button-next').hide();
	}

	function enablePrevious($wizard) {
		$wizard.find('.fw-button-previous').show();
	}

	function enableNext($wizard) {
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
		if (stepInt == 5) {
			$wizard.find('.fw-progress-bar').removeClass('fw-step-after-fifth')
		}
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
		var $wizard = getWizard($(this));
		var step = getStep($wizard);
		var stepInt = parseInt(step, 10);
		var $circle = $wizard.find('.fwp-progress-bar .fwp-circle[data-id="' + step + '"]');
		var $bar = $wizard.find('.fwp-progress-bar .fwp-bar[data-id="' + step + '"]');
		if (validateStep($wizard.find('.fw-current'))) {
			$wizard.find('.fw-progress-step[data-id="' + step + '"]').addClass('fw-visited');
			if (stepInt == 4) {
				$wizard.find('.fw-progress-bar').addClass('fw-step-after-fifth');
			}
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
			// scroll back to top on next step
			$('html, body').animate({
				scrollTop: $("#multi-step-form").offset().top - 100
			}, 500);
		}
	}

	function escapeHtml(str : any) : string {
		return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}

	function textSummary(summaryObj, $block, title, required) {
		const header = $block.find('h3').first().text();
		let value = $block.find('.fw-text-input').val().trim();
		value = escapeHtml(value);
		pushToSummary(summaryObj, title, header, value, required);
	}

	function hiddenSummary(summaryObj, $block, title, required) {
		const header = $block.data('label');
		let value = $block.find('.fw-text-input').val().trim();
		value = escapeHtml(value);
		pushToSummary(summaryObj, title, header, value, required);
	}

	function textareaSummary(summaryObj, $block, title, required) {
		const header = $block.find('h3').text();
		let value = $block.find('.fw-textarea').val().trim();
		value = escapeHtml(value);
		value = value.replace(/\n/g, "<br/>\n");
		pushToSummary(summaryObj, title, header, value, required);
	}

	function pushToSummary(summaryObj, title, header, value, required) {
		var s = {};
		if (value) {
			s[header] = value;
			getArray(summaryObj, title).push(s);
		} else if (required == 'true') {
			s['<p class="fw-step-summary fw-summary-invalid">' + header] = '</p>';
			getArray(summaryObj, title).push(s);
		}

	}

	function radioSummary(summaryObj, $block, title, required) {
		var header = $block.find('h3').text();
		var value = '';
		$block.find('.fw-choice').each(function (idx, element) {
			if ($(element).find('input').is(':checked')) {
				if (value != '') {
					value += ', ';
				}
				value += $(element).find('label').text();
			}
		});
		pushToSummary(summaryObj, title, header, value, required);
	}

	function selectSummary(summaryObj, $block, title, required) {
		var header = $block.find('h3').text();
		var value = $block.find('select').select2('data')[0].text;
		pushToSummary(summaryObj, title, header, value, required);
	}

	function checkboxSummary(summaryObj, $block, title, required) {
		var header = $block.find('label').text();
		var value;
		if ($block.find('.fw-checkbox').is(':checked')) {
			value = 'yes';
		}
		if ($block.hasClass('fw-block-invalid')) {
			log('INVALID' + $block);
		}
		pushToSummary(summaryObj, title, header, value, required);
	}

	function registrationSummary(summaryObj, $block, title, required) {
		var header = msfAjax.i18n.registration;
		var username = $block.find('.msfp-registration-input[data-id=username]').val();
		var email = $block.find('.msfp-registration-input[data-id=email]').val();
		var value = '';
		if (username && email) {
			value = username + ' (' + email + ')';
		} else {
			value = msfAjax.i18n.registrationFailed;
		}
		pushToSummary(summaryObj, title, header, value, required);
	}

	function blockSummary(summaryObj, $block, title) {
		var required = $block.attr('data-required');
		switch ($block.attr('data-type')) {
			case 'fw-email':
			case 'fw-numeric':
			case 'fw-date':
			case 'fw-text':
			case 'fw-regex':
				textSummary(summaryObj, $block, title, required);
				break;
			case 'fw-textarea':
				textareaSummary(summaryObj, $block, title, required);
				break;
			case 'fw-radio':
				radioSummary(summaryObj, $block, title, required);
				break;
			case 'fw-select':
				selectSummary(summaryObj, $block, title, required);
				break;
			case 'fw-checkbox':
				checkboxSummary(summaryObj, $block, title, required);
				break;
			case 'fw-registration':
				registrationSummary(summaryObj, $block, title, required);
				break;
			case 'fw-get-variable':
				hiddenSummary(summaryObj, $block, title, required);
				break;
			default:
				break;
		}
	}

	function stepSummary($wizard, stepNum, summaryObj) {
		var summary = '';
		var $step = $wizard.find('.fw-wizard-step[data-stepId="' + stepNum + '"]');
		$step.find('.fw-step-part').each(function (idx, element) {
			var title = $(element).find('.fw-step-part-title').text().trim();
			// here comes the ugliest jQ selector
			var $visibleBlocks = $(element).find('.fw-step-block:not(.fw-step-block[style="display: none;"] > .fw-step-block):not(.msfp-block-conditional)');
			$visibleBlocks.each(function (idx, element) {
				blockSummary(summaryObj, $(element), title);
			});
		});
		return summary;
	}

	function stripScripts(s : string) : string {
		var div = document.createElement('div');
		div.innerHTML = s;
		var scripts = div.getElementsByTagName('script');
		var i = scripts.length;
		while (i--) {
			scripts[i].parentNode.removeChild(scripts[i]);
		}
		return div.innerHTML;
	}

	function getAttachments() {
		var files = [];
		$('.fw-step-block[data-type=fw-file]').each(function (i, e) {
			getAttachment(e, files);
		});
		return files;
	}

	function getRegistration() {
		var res = {};
		$('.msfp-registration-input').each(function (index, element) {
			var field = $(element).attr('data-id');
			res[field] = $(element).val();
		});
		return res;
	}

	function getAttachment(e, files) {
		var attachments = $(e).find("input")[0].files;
		for (var i = 0; i < attachments.length; i++) {
			files.push(attachments[i].name);
		}
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
		var html = '';
		for (key in summary) {
			html += '<div class="fw-step-summary-field"><div class="fw-step-summary-field-title">' + key + '</div>';
			html += '<div class="fw-step-summary">' + stripScripts(summary[key]) + '</div></div>';
		}
		return html;
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
		$('.fw-toggle-summary').data("msf-toggle-active", 0);
		$('.fw-toggle-summary').unbind().click(
			function () {
				let active = $(this).data("msf-toggle-active");

				if (active == 0) {
					$('.fw-wizard-summary').slideDown();
					$('.fw-toggle-summary').text(msfAjax.i18n.hideSummary);
				} else {
					$('.fw-wizard-summary').slideUp();
					$('.fw-toggle-summary').text(msfAjax.i18n.showSummary);
				}

				$(this).data("msf-toggle-active", (active + 1) % 2);
			}
		);
		if ($('.fw-summary-invalid').length) {
			$summary.prepend('<div class="fw-summary-alert">' + msfAjax.i18n.errors.someRequired + '<br>' + msfAjax.i18n.errors.checkFields + '</div>');
		} else {
			$('.fw-summary-alert').remove();
		}
	}

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

	function get(obj, ...args: any) {
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
		if (!obj[prop]) {
			obj[prop] = [];
		}
		return obj[prop];
	}

	function check() {
		var $target = $(this);
		var $wizard = getWizard($target);
		var checked = $target.prop('checked');
		var obj = getObj($wizard, $target);
		var optId = $target.attr('data-id');
		if (checked) {
			obj[optId] = "checked";
		} else {
			delete (obj[optId]);
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
		$radios.each(function (idx, element) {
			if (blockId !== getBlockId($(element))) {
				multi = true;
			}
		});
		stepId = getStepId($target);
		partId = getPartId($target);

		if (multi) {
			obj = get(data, wizardId, stepId);
			delete (obj[partId]);
		} else {
			obj = get(data, wizardId, stepId, partId);
			delete (obj[blockId]);
		}
		updateSummary($wizard);
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

		var id = $target.attr('data-id');
		var obj = getObj($wizard, $target);
		obj[id] = value;
		$target.parents('.fw-step-block').removeClass('fw-block-invalid');
		$target.parents('.fw-step-block').find('.fw-block-invalid-alert').remove();
		updateSummary($wizard);
	}

	function selectOnChange() {
		var $target = $(this);
		var $wizard = getWizard($target);
		var value = $target.val();

		var id = $target.attr('data-id');
		var obj = getObj($wizard, $target);
		obj[id] = value;
		$target.parents('.fw-step-block').removeClass('fw-block-invalid');
		$target.parents('.fw-step-block').find('.fw-block-invalid-alert').remove();
		updateSummary($wizard);
	}

	function info() {
		log('Name', 'Multi-Step-Form by Mondula');
		log('Version', msfAjax.version);
		log('Data', data);
	}

	function validateRadio($element) {
		var valid = false;
		$element.children('.fw-choice').find('input').each(function (i, r) {
			var $r = $(r);
			if ($r.is(':checked')) {
				valid = true;
			}
		});
		if (!valid) {
			$element.addClass('fw-block-invalid');
		}
		return valid;
	}

	function validateSelect($element) {
		var valid = false;
		var $select = $element.find("select");
		if ($select.val()) {
			valid = true;
		} else {
			$element.addClass('fw-block-invalid');
		}
		return valid;

	}

	function validateEmail($element) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		var email = $element.find('.fw-text-input.one').val();
		var confirm = $element.find('.fw-text-input.two').val();
		if (!email || !re.test(email.trim())) {
			$element.addClass('fw-block-invalid');
			$element.removeClass('noMatch');
			return false;
		} else if (confirm != null && email !== confirm) {
			$element.addClass('fw-block-invalid noMatch');
			return false;
		} else {
			return true;
		}
	}

	function validateRegex($element) {
		var re = new RegExp($element.data("filter"));
		var regexInput = $element.find('.fw-text-input').val();
		if (!regexInput || !re.test(regexInput.trim())) {
			$element.addClass('fw-block-invalid');
			return false;
		} else {
			return true;
		}
	}

	function validateGetVariable($element) {
		if (!$element.find('.fw-text-input').val()) {
			$element.addClass('fw-block-invalid');
			return false;
		}
		return true;
	}

	function validateNumeric($element) {
		var re = /^-?\d+$/;
		var numeric = $element.find('.fw-text-input').val();
		var minimum = $element.data("min");
		var maximum = $element.data("max");

		if (!numeric || !re.test(numeric.trim())) {
			$element.addClass('fw-block-invalid');
			return false;
		} else {
			var numericValue = parseInt(numeric, 10);
			if (numericValue === NaN) {
				$element.addClass('fw-block-invalid');
				return false;
			}
			if (minimum !== undefined && parseInt(minimum, 10) !== NaN) {
				if (parseInt(minimum, 10) > numeric.length) {
					$element.addClass('fw-block-invalid');
					return false;
				}
			}
			if (maximum !== undefined && parseInt(maximum, 10) !== NaN) {
				if (parseInt(maximum, 10) < numeric.length) {
					$element.addClass('fw-block-invalid');
					return false;
				}
			}
			return true;
		}
	}

	function validateFile($element) {
		if (!$element.find('.fw-file-upload-input').val()) {
			$element.addClass('fw-block-invalid');
			return false;
		} else {
			return true;
		}
	}

	function validateDate($element) {
		if (!$element.find('.fw-text-input').val()) {
			$element.addClass('fw-block-invalid');
			return false;
		} else {
			return true;
		}
	}


	function validateText($element) {
		if (!$element.find('.fw-text-input').val()) {
			$element.addClass('fw-block-invalid');
			return false;
		}
		return true;
	}

	function validateTextArea($element) {
		if (!$element.find('.fw-textarea').val()) {
			$element.addClass('fw-block-invalid');
			return false;
		}
		return true;
	}

	function validateCheckbox($element) {
		if (!$element.find('.fw-checkbox').prop('checked')) {
			$element.addClass('fw-block-invalid');
			return false;
		}
		return true;
	}

	function validateSubmit($element) {
		var name = $element.find('[data-id=name]').val();
		var email = $element.find('[data-id=email]').val();
		var valid = true;
		if (!name || !email) {
			if ($element.has('input')) {
				valid = true;
			} else {
				$element.addClass('fw-block-invalid');
				valid = false;
			}
		}
		return valid;
	}

	function validateRegistration($block) {
		var valid = true;
		var $username = $block.find('[data-id=username]');
		var $email = $block.find('[data-id=email]');
		var hasValidUsername = $username.hasClass('msfp-reg-username-valid');
		var hasValidEmail = $email.hasClass('msfp-reg-email-valid');
		if (!hasValidUsername) {
			$block.addClass('fw-block-invalid');
			$username.removeClass("msfp-reg-username-valid");
			$username.addClass('msfp-registration-invalid');
			valid = false;
		}
		if (!hasValidEmail) {
			$block.addClass('fw-block-invalid');
			$email.removeClass("msfp-reg-email-valid");
			$email.addClass('msfp-registration-invalid');
			valid = false;
		}
		return valid;
	}

	function validateStep($step) {
		var valid = true;
		var stepValid = true;
		$step.find('.fw-step-block[data-required="true"]:visible').each(
			function (i, element) {
				var $element = $(element);
				var type = $element.attr('data-type');
				switch (type) {
					case 'fw-radio':
						valid = validateRadio($element);
						break;
					case 'fw-select':
						valid = validateSelect($element);
						break;
					case 'fw-textarea':
						valid = validateTextArea($element);
						break;
					case 'fw-text':
						valid = validateText($element);
						break;
					case 'fw-email':
						valid = validateEmail($element);
						break;
					case 'fw-get-variable':
						valid = validateGetVariable($element);
						break;
					case 'fw-numeric':
						valid = validateNumeric($element);
						break;
					case 'fw-file':
						valid = validateFile($element);
						break;
					case 'fw-date':
						valid = validateDate($element);
						break;
					case 'fw-checkbox':
						valid = validateCheckbox($element);
						break;
					case 'fw-submit':
						valid = validateSubmit($element);
						break;
					case 'fw-registration':
						valid = validateRegistration($element);
						break;
					case 'fw-regex':
						valid = validateRegex($element);
						break;
					default:
						break;
				}
				if (!valid) {
					stepValid = false;
				}
			}
		);

		// validate filled email fields
		$step.find('.fw-step-block[data-type="fw-email"]').each(
			function (i, element) {
				var $element = $(element);
				if ($element.find('.fw-text-input').val() != "") {
					valid = validateEmail($element);
					if (!valid) {
						stepValid = false;
					}
				}
			}
		);

		// validate filled numeric fields
		$step.find('.fw-step-block[data-type="fw-numeric"]').each(
			function (i, element) {
				var $element = $(element);
				if ($element.find('.fw-text-input').val() != "") {
					valid = validateNumeric($element);
					if (!valid) {
						stepValid = false;
					}
				}
			}
		);

		// validate filled regex fields
		$step.find('.fw-step-block[data-type="fw-regex"]').each(
			function (i, element) {
				var $element = $(element);
				if ($element.find('.fw-text-input').val() != "") {
					valid = validateRegex($element);
					if (!valid) {
						stepValid = false;
					}
				}
			}
		);

		if (!stepValid) {
			$('.fw-block-invalid').each(function (idx, element) {
				if ($(element).find('.fw-block-invalid-alert').length < 1) {
					if ($(element).attr('data-type') == 'fw-registration') {
						$(element).append('<div class="fw-block-invalid-alert">' + msfAjax.i18n.errors.checkFields + '</div>');
					} else if ($(element).attr('data-type') == 'fw-email') {
						if ($(element).hasClass('noMatch')) {
							$(element).append('<div class="fw-block-invalid-alert">' + msfAjax.i18n.errors.emailsDontMatch + '</div>');
						} else {
							$(element).append('<div class="fw-block-invalid-alert">' + msfAjax.i18n.errors.invalidEmail + '</div>');
						}
					} else if ($(element).attr('data-type') == 'fw-numeric') {
						$(element).append('<div class="fw-block-invalid-alert">' + msfAjax.i18n.errors.invalidNumeric + '</div>');
					} else if ($(element).attr('data-type') == 'fw-regex') {
						var errorMsg = $(element).data("error-msg") || msfAjax.i18n.errors.invalidRegex;
						$(element).append('<div class="fw-block-invalid-alert">' + errorMsg + '</div>');
					} else {
						$(element).append('<div class="fw-block-invalid-alert">' + msfAjax.i18n.errors.requiredField + '</div>');
					}
				}
			});
			alertUser(msfAjax.i18n.errors.requiredFields, false);
		}

		return stepValid;
	}

	function validateRegEmail($element) {
		var $block = $element.closest('.fw-step-block');
		var email = $element.val();
		var data = {
			action: 'msfp_pre_validate_reg_email',
			email: email,
		};
		$.ajax({
			type: 'POST',
			url: msfAjax.ajaxurl,
			data: data,
			dataType: "json",
			success: function (r) {
				if (r.success) {
					$element.removeClass('msfp-registration-invalid');
					$element.addClass("msfp-reg-email-valid");
					$element.next().next().remove('.fw-block-invalid-alert');
					// remove block-invalid if username is also valid
					if ($block.find('.msfp-reg-username-valid').length > 0) {
						$block.removeClass('fw-block-invalid');
					}

				} else {
					$block.addClass('fw-block-invalid');
					$element.addClass('msfp-registration-invalid');
					$element.removeClass("msfp-reg-email-valid");
					if (!$element.next().next().hasClass('fw-block-invalid-alert')) {
						$element.next().after('<div class="fw-block-invalid-alert">' + r.error + '</div>');
					}
				}
			},
			error: function (resp) {
				warn('response', resp);
				warn('responseText', resp.responseText);
			}
		});
	}

	function validateRegUsername($element) {
		var $block = $element.closest('.fw-step-block');
		var username = $element.val();
		var data = {
			action: 'msfp_pre_validate_reg_username',
			username: username,
		};
		$.ajax({
			type: 'POST',
			url: msfAjax.ajaxurl,
			data: data,
			dataType: "json",
			success: function (r) {
				if (r.success) {
					$element.removeClass('msfp-registration-invalid');
					$element.addClass("msfp-reg-username-valid");
					$element.next().next().remove('.fw-block-invalid-alert');
					// remove block-invalid if email is also valid
					if ($block.find('.msfp-reg-email-valid').length > 0) {
						$block.removeClass('fw-block-invalid');
					}

				} else {
					$block.addClass('fw-block-invalid');
					$element.removeClass("msfp-reg-username-valid");
					$element.addClass('msfp-registration-invalid');
					if (!$element.next().next().hasClass('fw-block-invalid-alert')) {
						$element.next().after('<div class="fw-block-invalid-alert">' + r.error + '</div>');
					}
				}
			},
			error: function (resp) {
				warn('response', resp);
				warn('responseText', resp.responseText);
			}
		});
	}

	function validate($wizard) {
		// reset fw-block-invalid flags
		$wizard.find('.fw-block-invalid').each(function (i, element) {
			$(element).removeClass('fw-block-invalid');
		})

		let formValid = true;
		$wizard.find('.fw-wizard-step').each(function (idx, element) {
			const $step = $(element);
			if (!validateStep($step)) {
				formValid = false;
			}
		});

		if (formValid && useCaptcha) {
			if (invisibleCaptcha && !checkCaptchaSolved()) {
				window.grecaptcha.execute(captchaId);
				return false;
			}

			if (!checkCaptchaSolved()) {
				alertUser(msfAjax.i18n.errors.noCaptcha, false);
				formValid = false;
			}
		}

		return formValid;
	}

    /**
     * responseMessage - the message that shows up after form submit
     *
     * @param  {string} rsp the response message
     * @param  {boolean} success successful submit of fail
     */
	function alertUser(message : string, success : boolean) {
		$('.fw-alert-user').empty().removeClass('fw-alert-user-fail fw-alert-user-success');
		if (success) {
			$('.fw-alert-user').addClass('fw-alert-user-success')
				.append('<i class="fa fa-check-circle" aria-hidden="true"></i>');
		} else {
			$('.fw-alert-user').addClass('fw-alert-user-fail')
				.append('<i class="fa fa-times-circle" aria-hidden="true"></i>');
		}
		$('.fw-alert-user').append(message)
			.fadeIn().delay(2000).fadeOut();
	}

	function submit() {
		const $wizard = $('.fw-wizard');
		
		if (validate($wizard)) {
			$('.fw-spinner').show();
			const summary = getSummary($wizard);
			const firstEmail = $wizard.find('[data-id="email"]').first().val();
			const files = getAttachments();
			let reg;
			if ($wizard.find('[data-type=fw-registration]')) {
				reg = getRegistration();
			}

			sendEmail(summary, firstEmail, files, reg);
		}
	}

	function sendEmail(summary, firstEmail, files, reg) {
		const id = $('#multi-step-form').attr('data-wizardid');
		const token = useCaptcha ? window.grecaptcha.getResponse(captchaId) : "";
		$('.fw-btn-submit').html('<i class="fa fa-spinner"></i> ' + msfAjax.i18n.sending);
		$.post(
			msfAjax.ajaxurl, {
				action: 'fw_send_email',
				id: id,
				fw_data: summary,
				first_email: firstEmail,
				reg: reg,
				attachments: files,
				recaptchaToken: token,
				nonce: msfAjax.nonce
			},
			function (resp) {
				if (resp.success) {
					var url = $('.fw-container').attr('data-redirect');
					if (url) {
						// redirect to thankyou page
						window.onbeforeunload = null;
						window.location.href = url;
					} else {
						$('.fw-btn-submit').addClass('fw-submit-success').html('<i class="fa fa-check-circle"></i> ' + msfAjax.i18n.submitSuccess);
						$('.fw-btn-submit').unbind("click");
					}
				} else {
					$('.fw-btn-submit').addClass('fw-submit-fail').html('<i class="fa fa-times-circle"></i> ' + msfAjax.i18n.submitError);
					warn('Server Response', resp);
				}
			}
		).fail(function (resp) {
			$('.fw-btn-submit').addClass('fw-submit-fail').html('<i class="fa fa-times-circle"></i> ' + msfAjax.i18n.submitError);
			warn('response', resp);
			warn('responseText', resp.responseText);
		});
	}

	function uploadFiles(e, $label) {
		var id = $('#multi-step-form').attr('data-wizardid');
		var files = $(e.target).prop('files');
		var formData = new FormData();

		formData.append('action', 'fw_upload_file');
		for (var i = 0; i < files.length; i++) {
			formData.append('file' + i, files[i]);
		}
		formData.append('id', id);

		$label.find('i').removeClass('fa-upload fa-times-circle fa-check-circle').addClass("fa-spinner");
		$label.find('span').text(msfAjax.i18n.uploadingFile);


		var $block = $(e.target).parent().parent();

		$.ajax({
			type: 'POST',
			url: msfAjax.ajaxurl,
			data: formData,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function (response) {
				setupLeaveWarning();
				if (response.success) {
					$block.attr('data-uploaded', 'true');
					$label.find('i').removeClass('fa-times-circle fa-spinner').addClass(" fa-check-circle");
					var fileNames = '';
					for (var i = 0; i < files.length; i++) {
						if (i > 0) {
							fileNames += ', ';
						}
						fileNames += files[i].name;
					}
					$label.find('span').html(fileNames);
				} else {
					$label.find('i').removeClass("fa-spinner fa-check-circle").addClass('fa-times-circle');
					$label.find('span').html(response.error);
					warn(response.error);
				}
			},
			error: function (res) {
				warn(res);
			}
		});
	}

	function deleteAttachments(attachments) {
		$.post(
			msfAjax.ajaxurl, {
				action: 'fw_delete_files',
				filenames: attachments,
			},
			function (resp) {
				if (resp) {
					$('[data-type=fw-file]').each(function (i, e) {
						var fileInput = $(e).find('input');
						fileInput.replaceWith(fileInput.val('').clone(true));
						$(e).find('label > i').removeClass('fa-check-circle').addClass('fa-upload');
						$(e).find('label > span').text(msfAjax.i18n.chooseFile);
						$(e).attr('data-uploaded', 'false');
					});
				}
			}
		).fail(function (resp) {
			warn('response', resp);
			warn('responseText', resp.responseText);
		});
	}

	function setupRegistration() {
		var $emailInput = $('.msfp-registration-input[data-id=email]');
		var $usernameInput = $('.msfp-registration-input[data-id=username]');
		var $block = $emailInput.closest('.fw-step-block');
		$emailInput.on("input", function (event) {
			validateRegEmail($emailInput);
		});
		$usernameInput.on("input", function (event) {
			validateRegUsername($usernameInput);
		});
	}

	function setupFileUpload() {
		$('.fw-file-upload-input').each(function () {
			var $input = $(this),
				$label = $input.next('label'),
				labelVal = $label.html(),
				$block = $input.parent().parent();

			$input.on('change', function (e: JQuery.ChangeEvent<HTMLInputElement>) {
				var fileName = '';
				if (e.target.value)
					fileName = e.target.value.split('\\').pop();
				if (fileName) {
					uploadFiles(e, $label);
				}
				else
					$label.html(labelVal);
			});
			$input.on('click', function (e: JQuery.ClickEvent<HTMLInputElement>) {
				// delete if input already has a file
				if (e.target.value) {
					var attachments = [];
					getAttachment($block, attachments);
					deleteAttachments(attachments);
				}
			});
			// Firefox bug fix
			$input.on('focus', function () {
				$input.addClass('has-focus');
			})
				.on('blur', function () {
					$input.removeClass('has-focus');
				});
		});
	}

	function setupSelect2() {
		$('.fw-select').each(function (idx, element) {
			if (!$(element).data('search')) {
				$(element).select2({
					minimumResultsForSearch: Infinity,
					allowClear: $(element).data('required') !== true,
					placeholder: ""
				});
			} else {
				$(element).select2({
					allowClear: $(element).data('required') !== true,
					placeholder: ""
				});
			}
		});
	}

	function setupColors() {
		var activeColor = $('.fw-progress-bar').attr('data-activecolor');
		var doneColor = $('.fw-progress-bar').attr('data-donecolor');
		var nextColor = $('.fw-progress-bar').attr('data-nextcolor');
		var buttonColor = $('.fw-progress-bar').attr('data-buttoncolor');
		$('head').append('<style id="fw-colors"></style>')
		if (activeColor) {
			$('head').append('<style>.fw-active .progress, ul.fw-progress-bar li.fw-active:before{background:' + activeColor + '!important;} [data-type=fw-checkbox] input[type=checkbox]:checked+label:before, ul.fw-progress-bar li.fw-active .fw-txt-ellipsis { color: ' + activeColor + ' !important; } .fw-step-part { border-color: ' + activeColor + ' !important; } .fw-step-summary-field { border-color: ' + activeColor + ' !important; } </style>');
		}
		if (doneColor) {
			$('head').append('<style>ul.fw-progress-bar .fw-active:last-child:before, .fw-progress-step.fw-visited:before{ background:' + doneColor + ' !important; } .fw-progress-step.fw-visited, ul.fw-progress-bar .fw-active:last-child .fw-txt-ellipsis, .fw-progress-step.fw-visited .fw-txt-ellipsis { color:' + doneColor + ' !important;} ul.fw-progress-bar li.fw-visited:after, .fw-progress-step.fw-visited .fw-circle, .fw-progress-step.fw-visited .fw-circle-1, .fw-progress-step.fw-visited .fw-circle-2{ background-color:' + doneColor + ' !important;}</style>');
		}
		if (nextColor) {
			$('head').append('<style>ul.fw-progress-bar li:before{background:' + nextColor + ' !important;} .fw-progress-bar li.fw-active:after, li.fw-progress-step::after, .fw-circle, .fw-circle-1, .fw-circle-2{ background-color:' + nextColor + ' !important;} .fw-txt-ellipsis { color: ' + nextColor + ' !important; } </style>');
		}
		if (buttonColor) {
			$('head').append('<style>.fw-button-previous, .fw-button-next, .fw-button-fileupload { background: ' + buttonColor + ' !important; }</style>');
		}
	}

	function setupLeaveWarning() {
		if ($('#multi-step-form').length) {
			// show warning and delete attachments before leaving page
			window.onbeforeunload = function () {
				var attachments = getAttachments();
				deleteAttachments(attachments);
				return 'Your uploaded files were deleted from the server for security reasons.'
			};
		}
	}

	function setupDatepicker() {
		var format = $('.fw-datepicker-here').attr('data-dateformat');
		$('.fw-datepicker-here').datepicker({
			dateFormat: format,
			changeMonth: true,
			changeYear: true,
			yearRange: '-100:+20',
		});
	}

	function setupChangeListeners() {
		$('.fw-checkbox').change(check);
		$('.fw-radio').change(checkRadio);
		$('.fw-radio-conditional').change(checkConditional);
		$('.fw-text-input').on('change input', textOnChange);
		$('.fw-textarea').on('change input', textOnChange);
		$('.fw-checkbox, .fw-radio').on('change', function () {
			$(this).parents('.fw-step-block').removeClass('fw-block-invalid');
			$(this).parents('.fw-step-block').find('.fw-block-invalid-alert').remove();
		});
		$('.msfp-registration-input').change(textOnChange);
		$('.fw-select').on('change', selectOnChange);
	}

	function setupReCaptcha() {
		const tokenFields = $('.msf-recaptcha-token');

		if (tokenFields.length > 0) {
			useCaptcha = true;
			invisibleCaptcha = tokenFields.data('invisible');
			const siteKey = tokenFields.data('sitekey');
			const recaptchaElement = $('.msf-recaptcha-element')[0];

			if (!window.grecaptcha) {
				useCaptcha = false;
				warn("ReCaptcha Object not found! This is likely because the ReCaptcha script couldn't be loaded!");
			}

			window.grecaptcha.ready(function () {
				let params = {
					sitekey: siteKey,
				};
				if (invisibleCaptcha) {
					params['callback'] = function(){submit()};
				}
				captchaId = window.grecaptcha.render(
					recaptchaElement, 
					params
				);
			});
		}
	}

	function setupGetVariables() {
		const $wizard = $('.fw-wizard');

		const browserParams = new URLSearchParams(window.location.search);

		$wizard.find('.fw-step-block[data-type="fw-get-variable"]').each(
			function (_, element) {
				const $element = $(element);
				const searchParam = $element.data("param");

				if (browserParams.has(searchParam)) {
					const param = browserParams.get(searchParam);
					$element.find('.fw-text-input').val(param);
				}
			}
		);
	}

	function checkCaptchaSolved() {
		return window.grecaptcha.getResponse(captchaId).length > 0;
	}

	function setup() {
		const $wizard = $('.fw-wizard');

		$wizard.each(function (idx, element) {
			showStep($(element), 0);
		});

		const count = getStepCount($wizard);
		const parentWidth = $wizard.parent().outerWidth();

		if ((count >= 5 && parentWidth >= 769) ||
			(parentWidth >= 500)) {
			$wizard.addClass('fw-large-container');
		}

		$('.fw-progress-step[data-id="0"]').addClass('fw-active');
		$('.fw-button-previous').hide();
		$('.fw-button-previous').click(previous);
		$('.fw-button-next').click(next);

		const showSummary = $('.fw-wizard-summary').attr('data-showsummary');
		if (showSummary == 'off') {
			$('.fw-toggle-summary').remove();
		}

		setupSelect2();
		setupChangeListeners();
		setupFileUpload();
		setupDatepicker();
		setupColors();
		setupRegistration();
		setupReCaptcha();
		setupGetVariables();

		$('.fw-btn-submit').click(submit);
		updateSummary($('.fw-wizard'));
	}

    /**
     * Fixing select2 positioning.
     */
    function select2Fix() {
        $("select").on("select2:open", function (e) {
            const select = $(e.target).next();
            const dropdown = $(".select2-dropdown");
            const selectOffset = select.offset();
            const selectHeight = select.outerHeight();
            const dropdownOffset = dropdown.offset();
            const dropdownHeight = dropdown.outerHeight();
            const selectTop = selectOffset?.top;
            const selectBottom = (selectTop || 0) + (selectHeight || 0);
            const dropdownTop = dropdownOffset?.top;
            const dropdownBottom = (dropdownTop || 0) + (dropdownHeight || 0);
            let distance;
            if (select.is('[class*="below"]')) {
                distance = selectBottom - (dropdownTop || 0);
            } else {
                distance = (selectTop || 0) - dropdownBottom;
            }
            const dropdownParent = dropdown.parent();

            if (distance && dropdownParent) {
                dropdownParent.css("transform", `translateY(${distance}px)`);
            }
        });

        $("select").on("select2:closing", function (e) {
            const dropdown = $(".select2-dropdown");
            const dropdownParent = dropdown.parent();
            if (dropdownParent.length > 0) {
                dropdownParent.css("transform", "");
            }
        });
    }

	function init() {
		$(document).ready(function (evt) {
			if ($('#multi-step-form').length) {
				setup();
                select2Fix();
				(window as any).msf = {};
				(window as any).msf.info = info;
			}
		});

		log("Multi Step Form loaded.");
	}

	init();
});
