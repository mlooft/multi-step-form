/// <reference path="../../../node_modules/@types/jqueryui/index.d.ts" />
/// <reference path="../../../node_modules/@types/select2/index.d.ts" />

declare var wizard: any;
declare var msfp: boolean | undefined;
declare var setupConditionals: Function | undefined;
declare var tb_remove: Function;
declare var wp: any;

(function ($) {
	'use strict';

	const container = '#fw-wizard-container';
	const elementsContainer = '#fw-elements-container';

	const logStyle = "color: white; background-color: purple; padding: 3px; display: block; line-height: 25px; border-radius: 2px;";

	/**
	 * Shows a log message if the console is available.
	 * 
	 * @param args data to display
	 */
	function log(...args : any[]) {
		if (window.console) console.log.apply(console, ["%cMSF", logStyle, ...args]);
	}

	/**
	 * Shows a warn message if the console is available.
	 * 
	 * @param args data to display
	 */
	function warn(...args : any[]) {
		if (window.console) console.warn.apply(console, ["%cMSF", logStyle, ...args]);
	}

    /**
     * Return a step with an empty section and no values set.
     */
	function emptyStep() {
		return {
			title: '',
			headline: '',
			copy_text: '',
			parts: [{
				title: '',
				blocks: []
			}]
		};
	}

	/**
	 * Displays a message to the user. The message can be colored based on
	 * success or failure of the operation.
	 * 
	 * @param message the message
	 * @param success true for success and false for an error message
	 */
	function alertMessage(message : string, success : boolean) {
		let color : string;
		if (success) {
			color = '#4caf50';
		} else {
			color = '#f44336';
		}
		$('<div id="fw-alert" style="background-color:' + color + '">' +
			message + '</div>')
			.hide().appendTo("#wpbody-content")
			.slideDown()
			.delay(3000)
			.slideUp();
	}

	/**
	 * Converts the parameter to a safe, escaped string.
	 * 
	 * @param s the data to escape
	 * @returns the escaped, stringified data
	 */
	function escapeAttribute(s : any) : string {
		return ('' + s).replace(/\\/g, '\\\\').replace(/"/g, '\\\"').replace(/'/g, '\\\'');
	}

	/**
	 * Creates a HTML string to dusplay the type of a block.
	 * 
	 * @param type the name of the block type
	 * @returns HTML Code
	 */
	function renderBlockAction(type : string) : string {
		let blockAction = '<div class="fw-block-action fw-block-hndle">';
		blockAction += '<i class="fa fa-arrows fw-move-block fw-block-hndle" aria-hidden="true"></i>';
		blockAction += '<h4>' + type + '</h4>';
		blockAction += '</div>';
		return blockAction;
	}

    /**
     * renderRadioHeader - renders the header for radio block
     *
     * @param radioHeader the radio header object
	 * @returns HTML Code
     */
	function renderRadioHeader(radioHeader : string) : string {
		let radioHeaderHtml = '<div class="fw-radio-option-element" data-type="header"><label>' + wizard.i18n.label + '</label>';
		radioHeaderHtml += '<input type="text" class="fw-radio-header fw-block-label" value="' + radioHeader + '"></input>';
		radioHeaderHtml += '</div>';
		return radioHeaderHtml;
	}


    /**
     * renderRadioOption - renders a single option for a radio
     *
     * @param  radioOption the radio option
     * @param  idx this options index
     * @return the html for the radio option
     */
	function renderRadioOption(radioOption : any, idx : number) : string {
		let radioOptionHtml = '<div class="fw-radio-option-element" data-type="option">'; //'<label>Option ' + idx + '</label>';
		radioOptionHtml += '<input type="text" class="fw-radio-option" placeholder="' + wizard.i18n.radio.option + ' ' + idx + '" value="' + escapeAttribute(radioOption) + '"></input>';
		radioOptionHtml += '<div class="fw-remove-radio-option"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>';
		return radioOptionHtml;
	}

	function renderRadio(radio) {
		var i, n, optCount = 0;
		var radioHtml = '';
		var element;
		// elements
		radioHtml += '<div class="fw-radio-option-container">';
		for (i = 0, n = radio.elements.length; i < n; i++) {
			element = radio.elements[i];
			// log('element', element);
			if (element.type === 'option') {
				if (i == 1) {
					radioHtml += '<label>' + wizard.i18n.radio.options + '</label>';
				}
				radioHtml += renderRadioOption(element.value, (1 + optCount++));
			} else {
				radioHtml += renderRadioHeader(element.value);
			}

		}
		radioHtml += '</div>';
		radioHtml += '<button class="fw-radio-add"><i class="fa fa-plus" aria-hidden="true"></i> ' + wizard.i18n.radio.addOption + '</button><br/>';
		radioHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(radio.required) + '/> ' + wizard.i18n.required + '</label>';
		if (radio.multichoice == "true") {
			radioHtml += '<label><input type="checkbox" class="fw-radio-multichoice" checked/>' + wizard.i18n.radio.multiple + ' <i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.multiChoice + '"></i></label>';
		} else {
			radioHtml += '<label><input type="checkbox" class="fw-radio-multichoice"/>' + wizard.i18n.radio.multiple + ' <i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.multiChoice + '"></i></label>';
		}
		return radioHtml;
	}

	function renderSelect(select) {
		let i = 0;
		let selectHtml = '';
		const placeholder = wizard.i18n.select.placeholder ? wizard.i18n.select.placeholder : '';
		selectHtml += '<div class="fw-select-option-container">';
		selectHtml += '<label>' + wizard.i18n.label + '</label>';
		selectHtml += '<input type="text" class="fw-block-label" value="' + select.label + '"></input>';
		selectHtml += '<label>' + placeholder + '</label>';
		selectHtml += '<input type="text" class="fw-select-placeholder" value="' + select.placeholder + '"></input>';
		selectHtml += '<label>' + wizard.i18n.select.options + '</label>';
		selectHtml += '<textarea class="fw-select-options" rows="4" cols="50">';
		for (i = 0; i < select.elements.length; i++) {
			selectHtml += select.elements[i] + "\n";
		}
		selectHtml += '</textarea>';
		selectHtml += '</div>';
		selectHtml += '<label><input type="checkbox" class="fw-select-search"' + isChecked(select.search) + '/>' + wizard.i18n.select.search + '</label>';
		selectHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(select.required) + '/> ' + wizard.i18n.required + '</label>';

		return selectHtml;
	}

	function renderCheckbox(block) {
		var textHtml = '';
		textHtml += '<label>' + wizard.i18n.label + '</label>';
		textHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		textHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return textHtml;
	}

	function renderTextInput(block) {
		var textHtml = '';
		textHtml += '<label>' + wizard.i18n.label + '</label>';
		textHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		textHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return textHtml;
	}

	function renderEmail(block) {
		var emailHtml = '';
		emailHtml += '<label>' + wizard.i18n.label + '</label>';
		emailHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		emailHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return emailHtml;
	}

	function renderNumeric(block) {
		var numericHtml = '';
		numericHtml += '<label>' + wizard.i18n.label + '</label>';
		numericHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		numericHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		numericHtml += '<label>' + wizard.i18n.numeric.minimum + '</label>';
		numericHtml += '<input type="text" class="fw-numeric-minimum fw-block-label" placeholder="' + wizard.i18n.numeric.no_minimum + '" value="' + (block.minimum ? block.minimum : '') + '" pattern="-?\\d*"></input><br/><br/>';
		numericHtml += '<label>' + wizard.i18n.numeric.maximum + '</label>';
		numericHtml += '<input type="text" class="fw-numeric-maximum fw-block-label" placeholder="' + wizard.i18n.numeric.no_maximum + '" value="' + (block.maximum ? block.maximum : '') + '" pattern="-?\\d*"></input><br/>';
		return numericHtml;
	}

	function renderFile(block) {
		var fileHtml = '';
		fileHtml += '<label>' + wizard.i18n.label + '</label>';
		fileHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		fileHtml += '<label><input type="checkbox" class="fw-file-multi"' + isChecked(block.multi) + '/>' + wizard.i18n.multifile + '</label>';
		fileHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return fileHtml;
	}

	function renderDate(block) {
		var dateHtml = '';
		dateHtml += '<label>' + wizard.i18n.label + '</label>';
		dateHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		dateHtml += '<label>' + wizard.i18n.dateformat + '<a target="_blank" href="http://t1m0n.name/air-datepicker/docs/#sub-section-9"><i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.dateformat + '"></i></a></label>';
		dateHtml += '<input type="text" class="fw-date-format fw-block-label" placeholder="' + wizard.i18n.dateformat + '" value="' + (block.format ? block.format : 'yy-mm-dd') + '" ></input><br/>';
		dateHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return dateHtml;
	}

	function renderTextArea(block) {
		var textAreaHtml = '';
		textAreaHtml += '<label>' + wizard.i18n.label + '</label>';
		textAreaHtml += '<input type="text" class="fw-textarea-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		textAreaHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return textAreaHtml;
	}

	function renderParagraph(block) {
		var paragraphHtml = '';
		paragraphHtml += '<label>' + wizard.i18n.paragraph.textHtml + ' <i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.paragraph + '"></i></label>';
		paragraphHtml += '<textarea class="fw-paragraph-text fw-block-label" placeholder="' + wizard.i18n.paragraph.text + '">' + (block.text ? block.text : '') + '</textarea>';
		paragraphHtml += '<label style="display:none;"><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return paragraphHtml;
	}

	function renderMedia(block) {
		var mediaHtml = '';
		mediaHtml += '<label>' + wizard.i18n.media.title + ' <i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.media.tooltip + '"></i></label>';

		mediaHtml += '<div style="float:left; width: 49%;"><label>' + wizard.i18n.media.file_title + '</label>';
		mediaHtml += '<input style="width: 90%" type="text" disabled class="fw-media-element-title fw-block-label" value=""></input><br/></div>';
		mediaHtml += '<div style="float: left; width: 49%;"><label>' + wizard.i18n.media.file_name + '</label>';
		mediaHtml += '<input style="width: 90%" type="text" disabled class="fw-media-element-filename fw-block-label" value=""></input><br/></div>';
		mediaHtml += '<br style="clear: both;" />';

		mediaHtml += '<label>' + wizard.i18n.media.preview + '</label>';
		mediaHtml += '<div class="fw-media-preview-wrapper"><img class="fw-media-preview" src="" height="100" style="height: 100px; width: auto; max-width: 100%;"></div>';
		mediaHtml += '<input type="hidden" name="fw-media-element" class="fw-media-element" value="' + (block.attachmentId ? block.attachmentId : 0) + '" class="regular-text" />';
		mediaHtml += '<input type="button" class="button-primary fw-media-select" value="' + wizard.i18n.media.select + '"/><br /><br />';

		mediaHtml += '<label style="display:none;"><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return mediaHtml;
	}

	function renderRegex(block) {
		var regexHtml = '';
		regexHtml += '<label>' + wizard.i18n.label + '</label>';
		regexHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="' + wizard.i18n.label + '" value="' + block.label + '"></input><br/>';
		regexHtml += '<label>' + wizard.i18n.filter + '</label>';
		regexHtml += '<input type="text" class="fw-regex-filter fw-block-label" placeholder="' + wizard.i18n.filter + '" value="' + (block.filter ? block.filter : '') + '"></input><br/>';
		regexHtml += '<label>' + wizard.i18n.filterError + '</label>';
		regexHtml += '<input type="text" class="fw-regex-error fw-block-label" placeholder="' + wizard.i18n.filterError + '" value="' + (block.customError ? block.customError : '') + '"></input><br/>';
		regexHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		return regexHtml;
	}

	function renderRegistration(block) {
		var registrationHtml = '';
		registrationHtml += '<label><input type="checkbox" class="fw-required"' + isChecked(block.required) + '/>' + wizard.i18n.required + '</label>';
		registrationHtml += '<p class="msfp-registration-info">' + wizard.i18n.registration.info + '</p>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-email" checked disabled="disabled"/>' + wizard.i18n.registration.email + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-username" checked disabled="disabled"/>' + wizard.i18n.registration.username + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-password"' + isChecked(block.password) + '/>' + wizard.i18n.registration.password + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-firstname"' + isChecked(block.firstname) + '/>' + wizard.i18n.registration.firstname + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-lastname"' + isChecked(block.lastname) + '/>' + wizard.i18n.registration.lastname + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-website"' + isChecked(block.website) + '/>' + wizard.i18n.registration.website + '</label>';
		registrationHtml += '<label class="msfp-registration-option"><input type="checkbox" class="msfp-registration-bio"' + isChecked(block.bio) + '/>' + wizard.i18n.registration.bio + '</label>';
		return registrationHtml;
	}

	function renderBlock(block) {
		let error = false;
		let blockHtml = '<div class="fw-step-block" data-type="' + block.type + '" >';
		blockHtml += '<div class="fw-block-controls">';
		blockHtml += '<i class="fa fa-remove fw-remove-block" title="' + wizard.i18n.tooltips.removeBlock + '" aria-hidden="true"></i>';
		blockHtml += '<i class="fa fa-caret-up fw-toggle-block" aria-hidden="true"></i>';
		blockHtml += '</div>';
		// removepart button
		blockHtml += renderBlockAction(block.type);
		blockHtml += '<div class="fw-block-fields">';
		switch (block.type) {
			case 'radio':
				if (!block.elements) {
					block.elements = [{
						type: 'header',
						value: ''
					}, {
						type: 'option',
						value: ''
					}];
				}
				blockHtml += renderRadio(block);
				break;
			case 'select':
				if (!block.elements) {
					block.elements = [];
				}
				blockHtml += renderSelect(block);
				break;
			case 'checkbox':
				blockHtml += renderCheckbox(block);
				break;
			case 'text':
				blockHtml += renderTextInput(block);
				break;
			case 'email':
				blockHtml += renderEmail(block);
				break;
			case 'numeric':
				blockHtml += renderNumeric(block);
				break;
			case 'file':
				blockHtml += renderFile(block);
				break;
			case 'date':
				blockHtml += renderDate(block);
				break;
			case 'textarea':
				blockHtml += renderTextArea(block);
				break;
			case 'paragraph':
				blockHtml += renderParagraph(block);
				break;
			case 'media':
				blockHtml += renderMedia(block);
				break;
			case 'regex':
				blockHtml += renderRegex(block);
				break;
			case 'registration':
				blockHtml += renderRegistration(block);
				break;
			default:
				break;
		}
		blockHtml += '</div>';
		blockHtml += '<div class="fw-clearfix"></div>';
		blockHtml += '</div>';
		if (error) {
			blockHtml = '';
		}
		return blockHtml;
	}

	function renderBlocks(blocks) {
		var blocksHtml = '';
		const blockCount = blocks.length;

		for (let i = 0; i < blockCount; i++) {
			if (blocks[i].type == 'conditional') {
				// unwrap conditional block
				blocksHtml += renderBlock(blocks[i].block);
				// add conditional settings as block metadata
				var conditionalSettings = {
					prec_block_id: blocks[i].prec_block_id,
					prec_operator: blocks[i].prec_operator,
					prec_value: blocks[i].prec_value,
					visible: blocks[i].visible
				};
				// remove last closing div tag to add conditional meta information
				blocksHtml = blocksHtml.slice(0, -6)
				blocksHtml += '<input class="msf-block-meta" name="msf-block-meta-' + i + '" type="hidden" value="' + encodeURI(JSON.stringify(conditionalSettings)) + '">';
				blocksHtml += '</div>';
			} else {
				blocksHtml += renderBlock(blocks[i]);
			}
		}
		return blocksHtml;
	}

	function renderPart(part, partClass) {
		log('part', part);
		var partHtml = '<div class="' + partClass + '">';

		// handle
		partHtml += '<div class="fw-section-hndle"><i class="fa fa-arrows"></i></div>';

		// title
		partHtml += '<input type="text" class="fw-part-title" value="' + part.title + '" placeholder="' + wizard.i18n.partTitle + '"></input>';

		// removepart button
		partHtml += '<div class="fw-remove-part" title="' + wizard.i18n.removeSection + '">';
		partHtml += '<i class="fa fa-remove"></i>';
		partHtml += '</div><div class="inside connectedSortable">';

		// blocks
		partHtml += renderBlocks(part.blocks);

		// drag&drop or click here to add elements
		partHtml += '</div><div class="fw-add-element">';
		partHtml += '<a href="#TB_inline?width=400&height=200&inlineId=fw-thickbox-content" class="thickbox"><i class="fa fa-plus"></i> ' + wizard.i18n.addElement + '</a>';
		partHtml += '</div>';

		partHtml += '</div>';

		return partHtml;
	}

	function renderParts(parts) {
		const partCount = parts.length;
		let partsHtml = '<div><div class="fw-parts-header"><h3>' + wizard.i18n.sections + '</h3></div>';
		partsHtml += '<div class="fw-column-buttons">';
		partsHtml += '<button type="button" class="fw-button-one-column"><i class="fa fa-align-justify"></i></button>';
		partsHtml += '<button type="button" class="fw-button-two-columns"><i class="fa fa-align-justify"></i> <i class="fa fa-align-justify"></i></button>';
		partsHtml += '</div>';
		partsHtml += '<div class="fw-parts-container">';

		for (let i = 0; i < partCount; i++) {
			partsHtml += renderPart(parts[i], 'fw-step-part');
		}

		partsHtml += '</div>';
		partsHtml += '<div class="fw-parts-footer">';
		partsHtml += '<a class="fw-add-part"><i class="fa fa-plus"></i> ' + wizard.i18n.addSection + '</a>';
		partsHtml += '</div>';
		partsHtml += '</div>';
		return partsHtml;
	}

	function renderStepInside(step, idx) {
		var titleId = "fw-title-" + idx;
		var headlineId = "fw-headline-" + idx;
		var copyTextId = "fw-copy-text-" + idx;
		var stepHtml = '<div class="fw-step"><div class="form-wrap">';

		// title
		stepHtml += '<div class="input form-field">';
		stepHtml += '<label for="' + titleId + '"><b>' + wizard.i18n.title + '</b>';
		stepHtml += '<i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.title + '"></i></label>';
		stepHtml += '<input type="text" class="fw-step-title" value="' + step.title + '"></input>';
		stepHtml += '</div>';

		// headline
		stepHtml += '<div class="input form-field">';
		stepHtml += '<label for="' + headlineId + '"><b>' + wizard.i18n.headline + '</b>';
		stepHtml += '<i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.tooltips.headline + '"></i></label>';
		stepHtml += '<input type="text" class="fw-step-headline" value="' + step.headline + '"></input>';
		stepHtml += '</div>';

		// copy text
		stepHtml += '<div class="input form-field">';
		stepHtml += '<label for="' + copyTextId + '"><b>' + wizard.i18n.copyText + '</b>';
		stepHtml += '<i class="fa fa-info-circle" aria-hidden="true" title="' + wizard.i18n.copyText + '"></i></label>';
		stepHtml += '<input type="text" class="fw-step-copy_text" value="' + step.copy_text + '"></input>';
		stepHtml += '</div>';

		// parts
		stepHtml += '<div class="fw-step-parts">' + renderParts(step.parts) + '</div>';
		stepHtml += '</div><div class="fw-clearfix"></div></div>';
		return stepHtml;
	}

	function renderStep(step, idx) {
		var stepHtml = '<div class="postbox">';
		stepHtml += '<div class="fw-movediv hndle ui-sortable-handle"><i class="fa fa-arrows"></i></div>';
		stepHtml += '<h1 class="fw-step-h1 hndle ui-sortable-handle"><span>';
		stepHtml += step.title + '</span></h1>';
		stepHtml += '<div class="fw-step-controls">';
		stepHtml += '<i class="fa fa-remove fw-remove-step" title="' + wizard.i18n.tooltips.removeStep + '" aria-hidden="true"></i>';
		stepHtml += '<i class="fa fa-caret-up fw-toggle-step" aria-hidden="true"></i>';
		stepHtml += '<i class="fa fa-files-o fw-duplicate-step" title="duplicate step" aria-hidden="true"></i>';
		stepHtml += '</div>';
		stepHtml += '<div class="fw-clearfix"></div>';
		stepHtml += renderStepInside(step, idx);
		stepHtml += '<div class="fw-clearfix"></div>';
		stepHtml += '</div>';
		return stepHtml;
	}

	function renderFormSettings(formSettings) {
		if (formSettings) {
			if (formSettings.thankyou) {
				$('.fw-settings-thankyou').val(formSettings.thankyou);
			}
			if (formSettings.subject) {
				$('.fw-mail-subject').val(formSettings.subject);
			}
			if (formSettings.to) {
				$('.fw-mail-to').val(formSettings.to);
			}
			if (formSettings.frommail) {
				$('.fw-mail-from-mail').val(formSettings.frommail);
			}
			if (formSettings.fromname) {
				$('.fw-mail-from-name').val(formSettings.fromname);
			}
			if (formSettings.header) {
				$('.fw-mail-header').val(formSettings.header);
			}
			if (formSettings.headers) {
				$('.fw-mail-headers').val(formSettings.headers);
			}
			if (formSettings.replyto) {
				$('.fw-mail-replyto').val(formSettings.replyto);
				$('.fw-mail-replyto').trigger('change');
			}
		}
	}

	function renderSteps(steps) {
		var i, n;
		var stepsHtml = '<div class="postbox-container"><div class="metabox-holder"><div class="meta-box-sortables">';
		for (i = 0, n = steps.length; i < n; i++) {
			stepsHtml += renderStep(steps[i], i);
		}
		stepsHtml += '</div>';
		stepsHtml += '<a class="fw-element-step"><i class="fa fa-plus"></i> ' + wizard.i18n.addStep + '</a>';
		stepsHtml += '</div></div>';
		$(container).html(stepsHtml);
	}


    /**
     * getRadioElementData - retrieve the data for a set of radio buttons
     *
     * @param $element the radio DOM element
     * @return an object with the radio header and options
     */
	function getRadioElementData($element) {
		let data = {
			type: $element.attr('data-type'),
			value: null
		};

		if (data.type === 'option') {
			data.value = $element.find('.fw-radio-option').val();
		} else if (data.type === 'header') {
			data.value = $element.find('.fw-radio-header').val();
		}
		return data;
	}

	function getRadioData($radio, radio) {
		var elements = radio['elements'] = [];
		$radio.find('.fw-radio-option-element').each(function (idx, element) {
			elements.push(getRadioElementData($(element)));
		});
		radio['required'] = $radio.find('.fw-required').prop('checked');
		radio['multichoice'] = $radio.find('.fw-radio-multichoice').prop('checked');
	}

	function getSelectData($select, select) {
		var options = $select.find(".fw-select-options").val().split("\n");
		select['required'] = $select.find('.fw-required').prop('checked');
		select['search'] = $select.find('.fw-select-search').prop('checked');
		select['label'] = $select.find('.fw-block-label').val();
		select['placeholder'] = $select.find('.fw-select-placeholder').val();
		select['elements'] = options.filter(function (v) { return v !== '' && v !== ' ' });
	}


	// TODO: redundant functions

	function getCheckboxData($checkbox, checkbox) {
		checkbox['label'] = $checkbox.find('.fw-text-label').val();
		checkbox['required'] = $checkbox.find('.fw-required').prop('checked');
	}

	function getTextData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['required'] = $text.find('.fw-required').prop('checked');
	}

	function getEmailData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['required'] = $text.find('.fw-required').prop('checked');
	}

	function getNumericData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['required'] = $text.find('.fw-required').prop('checked');
		text['minimum'] = $text.find('.fw-numeric-minimum').val();
		text['maximum'] = $text.find('.fw-numeric-maximum').val();
	}

	function getFileData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['required'] = $text.find('.fw-required').prop('checked');
		text['multi'] = $text.find('.fw-file-multi').prop('checked');
	}

	function getDateData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['format'] = $text.find('.fw-date-format').val();
		text['required'] = $text.find('.fw-required').prop('checked');
	}

	function getTextareaData($text, text) {
		text['label'] = $text.find('.fw-textarea-label').val();
		text['required'] = $text.find('.fw-required').prop('checked');
	}

	function getParagraphData($text, text) {
		text['text'] = $text.find('.fw-paragraph-text').val();
	}

	function getMediaData($text, text) {
		text['attachmentId'] = $text.find('.fw-media-element').val();
		// TODO
	}

	function getRegexData($text, text) {
		text['label'] = $text.find('.fw-text-label').val();
		text['filter'] = $text.find('.fw-regex-filter').val();
		text['customError'] = $text.find('.fw-regex-error').val();
		text['required'] = $text.find('.fw-required').prop('checked');
	}

	function getRegistrationData($text, text) {
		text['required'] = $text.find('.fw-required').prop('checked')
		text['password'] = $text.find('.msfp-registration-password').prop('checked')
		text['firstname'] = $text.find('.msfp-registration-firstname').prop('checked')
		text['lastname'] = $text.find('.msfp-registration-lastname').prop('checked')
		text['website'] = $text.find('.msfp-registration-website').prop('checked')
		text['bio'] = $text.find('.msfp-registration-bio').prop('checked')
	}

	function getConditionalData($block) {
		var block = {};
		block['type'] = 'conditional';
		block['visible'] = $block.find('.msfp-conditional-visible').val();
		block['prec_block_id'] = $block.find('.msfp-conditional-prec-block-id').val();
		block['prec_operator'] = $block.find('.msfp-conditional-prec-op').val();
		block['prec_value'] = $block.find('.msfp-conditional-prec-value').val();
		block['block'] = getBlockData($block);
		return block;
	}

    /**
     * getBlockData - get the data from backend input fields
     *
     * @param $block the block to get data from
     * @return the block data
     */
	function getBlockData($block) {
		var block = {};
		var type = block['type'] = $block.attr('data-type');
		switch (type) {
			case 'radio':
				getRadioData($block, block);
				break;
			case 'select':
				getSelectData($block, block);
				break;
			case 'checkbox':
				getCheckboxData($block, block);
				break;
			case 'text':
				getTextData($block, block);
				break;
			case 'email':
				getEmailData($block, block);
				break;
			case 'numeric':
				getNumericData($block, block);
				break;
			case 'file':
				getFileData($block, block);
				break;
			case 'date':
				getDateData($block, block);
				break;
			case 'textarea':
				getTextareaData($block, block);
				break;
			case 'paragraph':
				getParagraphData($block, block);
				break;
			case 'media':
				getMediaData($block, block);
				break;
			case 'regex':
				getRegexData($block, block);
				break;
			case 'registration':
				getRegistrationData($block, block);
				break;
		}
		return block;
	}

	function getPartData($part) {
		var part = {};
		part['title'] = $part.find('.fw-part-title').val();
		var blocks = part['blocks'] = [];
		$part.find('.fw-step-block').each(function (idx, element) {
			var $block = $(element);
			// PLUS: mark as conitional if settings are set
			if ($block.find('.msfp-conditional').prop("checked")) {
				blocks.push(getConditionalData($block));
			} else {
				blocks.push(getBlockData($block));
			}
		});
		return part;
	}

	function getStepData($step) {
		let step = {
			title: $step.find('.fw-step-title').val(),
			headline: $step.find('.fw-step-headline').val(),
			copy_text: $step.find('.fw-step-copy_text').val(),
			parts: []
		};

		const $parts = $step.find('.fw-step-part');
		$parts.each(function (idx, element) {
			step.parts.push(getPartData($(element)));
		});

		return step;
	}

	function getSettings() {
		var settings = {
			// General settings
			thankyou: $('.fw-settings-thankyou').val(),

			// Mail settings
			to: $('.fw-mail-to').val(),
			frommail: $('.fw-mail-from-mail').val(),
			fromname: $('.fw-mail-from-name').val(),
			subject: $('.fw-mail-subject').val(),
			header: $('.fw-mail-header').val(),
			headers: $('.fw-mail-headers').val(),
			replyto: $('.fw-mail-replyto').val(),
		};

		return settings;
	}

	function isEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!email) {
			return false;
		}
		return regex.test(email);
	}

	function validateSettings(settings) {
		var valid = true;
		if (!isEmail(settings.to)) {
			valid = false;
			$('#fw-nav-settings').trigger('click');
			alertMessage(wizard.i18n.alerts.invalidEmail, false);
		} else if (!settings.subject) {
			valid = false;
			$('#fw-nav-settings').trigger('click');
			alertMessage(wizard.i18n.alerts.noSubject, false);
		}
		return valid;
	}

	function validateSteps(steps) {
		var valid = true;
		for (var i = 0; i < steps.length; i++) {
			var step = steps[i];
			if (!step.title) {
				valid = false;
				alertMessage(wizard.i18n.alerts.noStepTitle, false);
			} else {
				for (var j = 0; j < steps[i].parts.length; j++) {
					if (steps[i].parts[j].title === "") {
						valid = false;
						alertMessage(wizard.i18n.alerts.noSectionTitle, false);
					}
					for (var k = 0; k < steps[i].parts[j].blocks.length; k++) {
						var block = steps[i].parts[j].blocks[k];
						console.log(block);
						if (block.label !== undefined && block.label === "") {
							valid = false;
							alertMessage(wizard.i18n.alerts.noBlockTitle, false);
						}
						// TODO: validate conditional if checkbox is checked
					}
				}
			}
		}
		return valid;
	}

	function validate(data) {
		var valid = true;
		if (data.title === "") {
			valid = false;
			alertMessage(wizard.i18n.alerts.noFormTitle, false);
		} else {
			valid = validateSteps(data.wizard.steps) && validateSettings(data.wizard.settings);
		}
		return valid;
	}

	function save() {
		var $container = $(container);
		var title = $('.fw-wizard-title').val();
		var data = {
			wizard: {
				title: title,
				steps: [],
				settings: getSettings()
			}
		};
		var $steps = $container.find('.fw-step');
		$steps.each(
			function (idx, element) {
				var last = idx == $steps.length - 1;
				data.wizard.steps.push(getStepData($(element)));
			}
		);
		data.wizard.steps.push();

		if (validate(data)) {
			log('Save', data);

			$.ajax({
				type: 'POST',
				url: wizard.ajaxurl,
				dataType: 'json',
				data: {
					action: 'fw_wizard_save',
					data: JSON.stringify(data),
					nonce: wizard.nonce,
					id: wizard.id
				},
				success: function (response) {
					if (response.data.nonce !== undefined) {
						wizard.nonce = response.data.nonce;
					}

					wizard.id = response.data.id;
					alertMessage(response.data.msg, response.success);
				},
				error: function (response) {
					warn('Fail', arguments);
					warn('Response', response);
					alertMessage(wizard.i18n.alerts.ajaxSendError, false);
				}
			});
		}
	}

    /**
     * setupDragNDrop - prepare the draggables, sortables and droppables
     *
     * @return {helper}  some helpers for draggables
     */
	function setupDragNDrop() {
		$('.meta-box-sortables').sortable({
			opacity: 0.6,
			revert: true,
			cursor: 'move',
			handle: '.hndle',
			tolerance: 'pointer',
			placeholder: 'fw-block-placeholder',
			start: function (event, ui) {
				var height = $(ui.item).height();
				$('.fw-block-placeholder').height(height);
			},
			update: function (event, ui) {
				warn('sortables update', event, ui);
				$(ui.item).removeAttr('style');
				setupDragNDrop();
				setupTooltips();
			}
		});

		$('.fw-step-part .inside').sortable({
			opacity: 0.6,
			cursor: 'move',
			connectWith: '.connectedSortable',
			handle: '.fw-block-hndle',
			tolerance: 'intersect',
			placeholder: 'fw-block-placeholder',
			revert: 100,
			start: function (event, ui) {
				var height = $(ui.item).height(),
					$placeholder = $('.fw-block-placholder');
				$placeholder.height(height);
				$placeholder.attr('data-type', ui.item.attr('data-type'));
			},
			update: function (event, ui) {

				var blockType = $(ui.item).attr('data-type');
				var newBlockIdx = -1;
				if ($(ui.item).is('.fw-draggable-block')) {
					if (blockType === 'registration' && hasRegistration()) {
						alertMessage(wizard.i18n.alerts.onlyOneRegistration, false);
						$(ui.item).remove();
					} else {
						// add element
						var $newBlock = $(renderBlock({
							type: blockType,
							label: ''
						}));
						$(ui.item).replaceWith($newBlock);
						// get new Block index for keeping conditionals working
						newBlockIdx = $('.fw-step-block').index($newBlock);
						log('New block: ', $newBlock + " " + newBlockIdx);
					}
				}
				setupDragNDrop();
				setupTooltips();
				setupClickHandlers();
				if (msfp) {
					setupConditionals(newBlockIdx);
				}
			}
		});

		$('.fw-parts-container').sortable({
			opacity: 0.6,
			cursor: 'move',
			connectWith: '.fw-parts-container',
			handle: '.fw-section-hndle',
			tolerance: 'intersect',
			placeholder: 'fw-section-placeholder',
			revert: 100,
			start: function (event, ui) {
				var height = $(ui.item).height();
				$('.fw-section-placeholder').height(height);
			},
			update: function (event, ui) {
				setupDragNDrop();
				setupTooltips();
				setupClickHandlers();
			}
		});

		$(elementsContainer + ' .fw-draggable-block').draggable({
			connectToSortable: '.fw-step-part .inside',
			revert: 'invalid',
			helper: 'clone',
			cursor: 'move',
		});

		$(container).find('.fw-step-title').on('change input', titleOnChange);
	}


    /**
     * setupTooltips - creates tooltips for better usability
     */
	function setupTooltips() {
		$('.fa-info-circle').tooltip();
		$('.fw-remove-step').tooltip();
		$('.fw-duplicate-step').tooltip();
		$('.fw-remove-part').tooltip();
		$('.fw-remove-block').tooltip();
		$('.hndle.ui-sortable-handle').tooltip();
	}


    /**
     * titleOnChange - Dynamically changes the step h1 to
     * the current title
     *
     * @param  {type} evt description
     * @return {type}     description
     */
	function titleOnChange(evt) {
		var $this = $(this);

		log('titleOnChangeU', $this.val());

		$this.closest('.postbox').find('h1 > span').html($this.val().toString());
	}


    /**
     * updateOptions - updates the radioOptions data-attribute after adding/removing
     *
     * @param  $container the radio container
     */
	function updateOptions($container) {
		$container.find('.fw-radio-option-element[data-type="option"] > label').each(
			function (idx, elt) {
				log('updateOptions', elt);
				$(elt).html('Option ' + (idx + 1));
			}
		);
	}


    /**
     * addStep - add a step to the wizard
     */
	function addStep(step) {
		const stepCount = $('.fw-step').length;
		if (stepCount < 5 || msfp) {
			if (stepCount < 10) {
				var $step = $(renderStep(step, stepCount));
				$step.appendTo($(container).find('.meta-box-sortables'));

				setupClickHandlers();
				setupDragNDrop();
				setupThickbox();

				if (stepCount > 0) {
					// scroll down to new step
					$("html, body").animate({
						scrollTop: $(document).height() - $step.height() - 180
					}, 500);
				}
			} else {
				alertMessage(wizard.i18n.alerts.onlyTen, false);
			}
		} else {
			alertMessage(wizard.i18n.alerts.onlyFive, false);
		}
	}

	function duplicateStep($step) {
		var data = getStepData($step);
		data.title += ' (COPY)';
		addStep(data);
	}

    /**
     * isChecked - description
     *
     * @param  block the block to check if it's required
     * @return the checked-attribure for html or nothing at all
     */
	function isChecked(val) {
		var attr = '';
		if (val == 'true') {
			attr = 'checked';
		}
		return attr;
	}

	function hasRegistration() {
		let result = false;
		$('.fw-step-block').each(function (i, element) {
			if ($(element).attr('data-type') == 'registration') {
				result = true;
			}
		});
		return result;
	}

    /**
     * addPart - adds a part to a step
     *
     * @param  evt the addPart-Button in a step
     */
	function addPart(evt) {
		var target = evt.target;
		var part = renderPart({
			title: '',
			blocks: []
		}, 'fw-step-part');
		$(target).closest('.fw-step-parts').find('.fw-parts-container').append(part);
		// setup handler for new part
		$('.fw-remove-part').click(function (event) {
			removePart(event);
		});
		setupThickbox();
	}

	function removeStep() {
		var $this = $(this);
		var $step = $this.closest('.postbox');
		var r = confirm(wizard.i18n.alerts.reallyDeleteStep);
		if (r === true) {
			$step.slideUp(700, function () {
				$step.remove();
			});
		}
	}

    /**
     * removePart - removes a part (section) from a step
     *
     * @param  evt the addPart-Button in a step
     */
	function removePart(evt) {
		var $part = $(evt.target).closest('.fw-step-part');
		var r = confirm(wizard.i18n.alerts.reallyDeleteSection);
		if (r === true) {
			$part.slideUp(500, function () {
				$part.remove();
			});
		}
	}

	function removeBlock(evt) {
		var $block = $(evt.target).closest('.fw-step-block');
		var label = $block.find('.fw-block-label').val();
		var r = confirm(wizard.i18n.alerts.reallyDeleteBlock + "\n\n" + label);
		if (r === true) {
			$block.slideUp(300, function () {
				$block.remove();
			});
		}
	}

	function setupThickbox() {
		$(".thickbox").click(function (thickEvent) {
			// RADIO BUTTONS
			$("#fw-thickbox-radio").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'radio'
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// SELECT
			$("#fw-thickbox-select").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'select',
					label: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});

			// TEXT FIELD
			$("#fw-thickbox-text").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'text',
					label: '',
					value: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// EMAIL
			$("#fw-thickbox-email").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'email',
					label: '',
					value: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// NUMERIC
			$("#fw-thickbox-numeric").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'numeric',
					label: '',
					value: '',
					minimum: '',
					maximum: '',
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// FILE UPLOAD
			$("#fw-thickbox-file").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'file',
					label: '',
					value: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
			});
			// TEXT AREA
			$("#fw-thickbox-textarea").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'textarea',
					label: '',
					value: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// DATE
			$("#fw-thickbox-date").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'date',
					label: '',
					format: 'yy-mm-dd'
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				setupTooltips();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// PARAGRAPH
			$("#fw-thickbox-paragraph").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'paragraph',
					text: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				setupTooltips();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// MEDIA
			$("#fw-thickbox-media").unbind('click').click(function () {
				tb_remove();
				var block = $(renderBlock({
					type: 'media',
					attachmentId: 0
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				setupTooltips();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// REGEX
			$("#fw-thickbox-regex").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'regex',
					label: '',
					filter: '',
					customError: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				$part.find('.inside').append(block);
				setupClickHandlers();
				if (msfp) {
					setupConditionals($('.fw-step-block').index(block));
				}
			});
			// REGISTRATION
			$("#fw-thickbox-registration").unbind('click').click(function (thickRadioEvent) {
				tb_remove();
				var block = $(renderBlock({
					type: 'registration',
					label: '',
					value: ''
				}));
				var $part = $(thickEvent.target).parents('.fw-step-part');
				if (hasRegistration()) {
					alertMessage(wizard.i18n.alerts.onlyOneRegistration, false);
				} else {
					$part.find('.inside').append(block);
				}
				setupClickHandlers();
			});
		});
	}

	function maskNumericInput(e) {
		if ($('input[type=text]').index($(e.target)) != -1) {
			let val = $(e.target).val();
			if (Array.isArray(val)) {
				val = val.join(' ');
			}
			val = val.toString();
			if (
				($.inArray(e.keyCode, [37, 38, 39, 40, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 8, 13, 189]) == -1) // digits, digits in num pad, 'back', 'enter',  '-'
				|| (e.keyCode == 189 && val.indexOf("-") != -1) // not allow double '-'
				|| (e.keyCode == 198 && val.length != 0) // only allow '-' at the begining
			) {
				e.preventDefault();
			}
		}
	}

	function fillMedia($block, data) {
		let src = data.url;
		const { title, filename } = data;

		switch (data.type) {
			case "image":
				if (data.sizes.thumbnail.url) {
					src = data.sizes.thumbnail.url;
				}
				break;
			case "video":
				if (data.thumb.src) {
					src = data.thumb.src;
				} else if (data.icon) {
					src = data.icon;
				}
				break;
		}

		$block.find('.fw-media-preview').attr('src', src);
		$block.find('.fw-media-element').val(data.id);
		$block.find('.fw-media-element-title').val(title);
		$block.find('.fw-media-element-filename').val(filename);
	}

	function setupMedia($container) {
		const mediaBlocks = $container.find('.fw-media-element');
		
		mediaBlocks.each(function () {
			if ($(this).val().toString() !== '0') {
				const $block = $(this).parent();
				const attachment = wp.media.attachment($(this).val());

				attachment.fetch({
					success : function(result) {
						fillMedia($block, result.attributes);
					}
				});
			}
		});
	}

	let media_frame = null;
	function selectMedia(e) {
		e.preventDefault();

		const $block = $(e.target).parent();

		if (media_frame) {
			media_frame.msf_block = $block;
			media_frame.open();
			return;
		}

		media_frame = wp.media({
			title: wizard.i18n.media.frame_title,
			multiple: false
		});

		media_frame.msf_block = $block;

		media_frame.on('close', function () {
			const $block = media_frame.msf_block;
			const selection = media_frame.state().get('selection').first().toJSON();
			fillMedia($block, selection);
		});

		media_frame.on('open', function () {
			const $block = media_frame.msf_block;
			let selection = media_frame.state().get('selection');
			const id = $block.find('.fw-media-element').val();
			const attachment = wp.media.attachment(id);
			attachment.fetch();
			selection.add(attachment ? [attachment] : []);
		});

		media_frame.open();
	}

	function setupClickHandlers() {
		// add step handler
		$('.fw-element-step').unbind("click").click(function (event) {
			addStep(emptyStep());
		});

		$('.fw-duplicate-step').unbind("click").click(function (event) {
			const $step = $(this).parent().parent().find('.fw-step');
			duplicateStep($step);
		});

		// add part handler
		$('.fw-add-part').unbind("click").click(function (event) {
			addPart(event);
			setupDragNDrop();
		});

		$('.fw-toggle-step').unbind("click").click(function (event) {
			$(this).parent().parent().find('.fw-step').slideToggle();
			$(this).toggleClass('fw-icon-rotated');
		});

		// remove part handler
		$('.fw-remove-part').unbind("click").click(function (event) {
			removePart(event);
		});

		// remove block handler
		$('.fw-remove-block').unbind("click").click(function (event) {
			removeBlock(event);
		});

		$('.fw-toggle-block').unbind("click").click(function (event) {
			var $block = $(this).parent().parent();
			$block.toggleClass('fw-block-collapsed');
			if ($block.hasClass('fw-block-collapsed')) {
				let label = $block.find('.fw-block-label').val();
				if (Array.isArray(label)) {
					label = label.join(" ");
				}
				$block.find('h4').text(label);
				$(this).addClass('fw-icon-rotated');
			} else {
				var blockType = $block.data('type');
				$block.find('h4').text(blockType);
				$(this).removeClass('fw-icon-rotated');
			}
		});
	}

	function updateReplyTo() {
		const $replyTo = $('.fw-mail-replyto');
		const oldSelection = $replyTo.val();
		let foundOldSelection = false;

		$replyTo.find("option").each((_, oldOption) => {
			if ($(oldOption).val() !== "no-reply") {
				oldOption.remove();
			}
		});

		$('.fw-step-block').each(function (_, element) {
			const blockType = $(element).attr('data-type');

			if (blockType === 'email') {
				const selection = String($(element).find('.fw-block-label').val());
				const escapedSelection = escapeAttribute(selection);

				if (!$replyTo.find("option[value='" + escapedSelection + "']").length) {
					if (oldSelection === escapedSelection) {
						foundOldSelection = true;
					}
					var newOption = new Option(selection, escapedSelection, false, false);
					$replyTo.append(newOption);
				} 
			}
		});
		if (foundOldSelection) {
			$replyTo.val(oldSelection);
		} else {
			$replyTo.val('no-reply');
		}
		$replyTo.trigger('change');
	}

	function setupReplyTo() {
		$('.fw-mail-replyto').select2({
			width: '60%'
		});

		updateReplyTo();
	}

    /**
     * run - this function sets everything up
     */
	function run() {
		try {
			var w = JSON.parse(wizard.json);
			var $container = $(container);

			if (w.wizard.title) {
				// load the wizard title
				$('.fw-wizard-title').val(w.wizard.title);
			} else {
				$('.fw-wizard-title').val('My Multi Step Form');
			}
			var steps = w.wizard.steps && w.wizard.steps.length > 0 ? w.wizard.steps : [emptyStep()];
			renderSteps(steps);

			setupReplyTo();
			// get mail settings
			renderFormSettings(w.wizard.settings);

			$('.fw-button-save').click(save);

			//TODO: put all click handlers in the corresponding function

			// make elements sticky
			$(window).scroll(function () {
				var offset = $('.nav-tab-wrapper').position().top + $('.nav-tab-wrapper').height() + 9;

				var scrollTop = $(this).scrollTop();
				if (scrollTop > offset) {
					$(elementsContainer).addClass('fw-sticky');
				} else {
					$(elementsContainer).removeClass('fw-sticky');
				}
			});

			// toggle postboxes
			$container.on('click', '.postbox .handlediv', function () {
				$(this).closest('.postbox').toggleClass('closed');
			});

			$container.on('click', '.fw-radio-add', function () {
				var $cnt = $(this).prev('.fw-radio-option-container');
				var idx = $cnt.children('.fw-radio-option-element').length;
				var opt = renderRadioOption('', idx);
				$(opt).appendTo($cnt);
				updateOptions($cnt);
			});

			$container.on('click', '.fw-remove-radio-option', function () {
				log('remove on click');
				var $this = $(this);
				var $container = $this.closest('.fw-radio-option-container');
				$this.closest('.fw-radio-option-element').remove();
				updateOptions($container);
			});

			$container.on('keydown', '.fw-numeric-minimum', maskNumericInput);
			$container.on('keydown', '.fw-numeric-maximum', maskNumericInput);

			$container.on('click', '.fw-media-select', selectMedia);

			$container.on('click', '.fw-remove-step', removeStep);

			setupDragNDrop();
			setupTooltips();
			setupThickbox();
			setupClickHandlers();
			setupMedia($container);

			// tab menu toggle
			$('#fw-nav-settings').click(function (e) {
				$('#fw-nav-steps').toggleClass('nav-tab-active');
				$('#fw-nav-settings').toggleClass('nav-tab-active');
				$(container).hide();
				$(elementsContainer).hide();
				updateReplyTo();
				$('.fw-mail-settings-container').show();
			});

			$('#fw-nav-steps').click(function (e) {
				$('#fw-nav-steps').toggleClass('nav-tab-active');
				$('#fw-nav-settings').toggleClass('nav-tab-active');
				$('.fw-mail-settings-container').hide();
				$(container).show();
				$(elementsContainer).show();
			});

			// modal
			$('#fw-elements-modal').dialog({
				dialogClass: 'wp-dialog',
				modal: true,
				autoOpen: false,
				closeOnEscape: true,
				buttons: {
					'Close': function () {
						$(this).dialog('close');
					}
				}
			});

		} catch (ex) {
			warn(ex);
		}
	}

	$(document).ready(run);
})(jQuery);
