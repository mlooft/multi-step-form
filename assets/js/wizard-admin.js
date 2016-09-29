(function($) {
    'use strict';

    var container = '#fw-wizard-container';
    var elementsContainer = '#fw-elements-container';

    function log() {
        'console' in window && console.log.apply(console, arguments);
    }

    function warn() {
        'console' in window && console.warn.apply(console, arguments);
    }

    function alertMessage(message, success){
      var color;
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

    function renderBlockAction(type){
      var headingHtml = '<div class="fw-block-action fw-block-hndle">';
      headingHtml += '<i class="fa fa-remove fw-remove-block" title="remove element" aria-hidden="true"></i>'
      headingHtml += '<i class="fa fa-caret-down fw-toggle-block" aria-hidden="true"></i>';
      headingHtml += '<i class="fa fa-arrows fw-move-block fw-block-hndle" aria-hidden="true"></i>';
      headingHtml += '<h4>&nbsp;' + type.substring(0,5) + '</h4>';
      headingHtml += '</div>';
      return headingHtml;
    }

    /**
     * renderRadioHeader - renders the header for radio
     *
     * @param  radioHeader the radio header object
     */
    function renderRadioHeader(radioHeader) {
        var radioHeaderHtml = '<div class="fw-radio-option-element" data-type="header"><label>Label</label>';
        radioHeaderHtml += '<input type="text" class="fw-radio-header fw-block-label" value="' + radioHeader + '"></input>'
        radioHeaderHtml += '</div>';
        return radioHeaderHtml;
    }


    /**
     * renderRadioOption - description
     *
     * @param  radioOption the radio option
     * @param  idx this options index
     * @return the html for the radio option
     */
    function renderRadioOption(radioOption, idx) {
        var radioOptionHtml = '<div class="fw-radio-option-element" data-type="option">'; //'<label>Option ' + idx + '</label>';
        radioOptionHtml += '<input type="text" class="fw-radio-option" placeholder="Option ' + idx + '" value="' + radioOption + '"></input>';
        radioOptionHtml += '<div class="fw-remove-radio-option"><i class="fa fa-minus-circle" aria-hidden="true"></i></div></div>';
        return radioOptionHtml;
    }


    function renderRadio(radio) {
        log('radio', radio);
        var i, n, optCount = 0;
        var radioHtml = '';
        var element;
            // elements
        radioHtml += '<div class="fw-radio-option-container">';
        for (i = 0, n = radio.elements.length; i < n; i++) {
            element = radio.elements[i];
            log('element', element);
            if (element.type === 'option') {
                if (i == 1) {
                    radioHtml += '<label>Options</label>';
                }
                radioHtml += renderRadioOption(element.value, (1 + optCount++));
            } else {
                radioHtml += renderRadioHeader(element.value);
            }

        }
        radioHtml += '</div>';
        radioHtml += '<button class="fw-radio-add button-secondary"><i class="fa fa-plus" aria-hidden="true"></i> Add radio option</button><br/>';
        radioHtml += '<label><input type="checkbox" class="fw-required"'+ checkRequired(radio) + '/> choice required</label>'

        return radioHtml;
    }

    function renderSubmit(block) {
        var nameRequired = block.namerequired && 'checked';
        var mailRequired = block.mailrequired && 'checked';
        var submitHtml = '';
        submitHtml += '<p>This will allow the user to submit the form. <br>You can only have one submit-block.</p>';
        submitHtml += '<label><input type="checkbox" class="fw-submit-name" '+ nameRequired +'/>Require Name</label>';
        submitHtml += '<label><input type="checkbox" class="fw-submit-mail" '+ mailRequired + '/>Require E-Mail Address</label>';
        return submitHtml;
    }

    function renderCheckbox(block) {
        log('checkbox', block);
        var textHtml = '';
        textHtml += '<label>Label</label>';
        textHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="Label" value="' + block.label + '"></input><br/>';
        textHtml += '<label><input type="checkbox" class="fw-required"'+ checkRequired(block) + '/>require checked</label>'
        return textHtml;
    }

    function renderTextInput(block) {
        log('textInput', block);
        var textHtml = '';
        textHtml += '<label>Label</label>';
        textHtml += '<input type="text" class="fw-text-label fw-block-label" placeholder="Label" value="' + block.label + '"></input><br/>';
        textHtml += '<label><input type="checkbox" class="fw-required"'+ checkRequired(block) + '/>required</label>'
        return textHtml;
    }

    function renderTextArea(block) {
        log('textArea', block);
        var textAreaHtml = '';
        textAreaHtml += '<label>Label</label>';
        textAreaHtml += '<input type="text" class="fw-textarea-label fw-block-label" placeholder="Label" value="' + block.label + '"></input><br/>';
        textAreaHtml += '<label><input type="checkbox" class="fw-required"'+ checkRequired(block) + '/>required</label>'
        return textAreaHtml;
    }

    function renderBlock(block) {
        log('block', block);
        var error = false;
        var blockHtml = '<div class="fw-step-block" data-type="' + block.type + '" >';
        // removepart button
        blockHtml += renderBlockAction(block.type);
        blockHtml += '<div class="fw-block-fields">'
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
            case 'checkbox':
                blockHtml += renderCheckbox(block);
                break;
            case 'text':
                blockHtml += renderTextInput(block);
                break;
            case 'textarea':
                blockHtml += renderTextArea(block);
                break;
            case 'submit':
                if ($('.fw-step-block[data-type=submit]').length < 1) {
                  blockHtml += renderSubmit(block);
                } else {
                  error = true;
                  alertMessage('ERROR: Only one submit block allowed', false);
                }
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
        var i, n;
        for (i = 0, n = blocks.length; i < n; i++) {
            blocksHtml += renderBlock(blocks[i]);
        }
        return blocksHtml;
    }

    function renderPart(part, partClass) {
        log('part', part);
        var partHtml = '<div class="' + partClass + '">';


        // title
        partHtml += '<input type="text" class="fw-part-title" value="' + part.title + '" placeholder="' + wizard.i18n.partTitle + '"></input>'

        // removepart button
        partHtml += '<div class="fw-remove-part" title="remove section">'
        partHtml += '<i class="fa fa-times"></i>'
        partHtml += '</div><div class="inside">'

        // blocks
        partHtml += renderBlocks(part.blocks);

        // drag&drop or click here to add elements
        partHtml += '</div><div class="fw-add-element">'
        partHtml += '<a href="#TB_inline?width=400&height=200&inlineId=fw-thickbox-content" class="thickbox"><i class="fa fa-plus"></i><br> drag and drop elements or click here to add elements</a>'
        partHtml += '</div>'

        partHtml += '</div>'

        return partHtml;
    }

    function getPartClass(i, n) {
        var partClass = 'fw-step-part';
        // if (n > 1) {
        //     if (i == 0) {
        //         partClass += ' fw-left';
        //     } else {
        //         partClass += ' fw-right';
        //     }
        // }
        return partClass;
    }


    function renderParts(parts) {
        var i, n = parts.length,
            partsHtml = '<div><div class="fw-parts-header"><h3>Sections</h3></div>';
        partsHtml += '<div class="fw-column-buttons">';
        partsHtml += '<button type="button" class="fw-button-one-column"><i class="fa fa-align-justify"></i></button>';
        partsHtml += '<button type="button" class="fw-button-two-columns"><i class="fa fa-align-justify"></i> <i class="fa fa-align-justify"></i></button>';
        partsHtml += '</div>';
        var i, n;
        for (i = 0, n = parts.length; i < n; i++) {
            var partClass = getPartClass(i, n);
            partsHtml += renderPart(parts[i], partClass);
        }
        partsHtml += '<div class="fw-parts-footer">';
        partsHtml += '<a class="fw-add-part button-secondary"><i class="fa fa-plus"></i> Add Section</a>'
        partsHtml += '</div>'
        partsHtml += '</div>'
        return partsHtml;
    }

    function renderStepInside(step, idx) {
        var titleId = "fw-title-" + idx;
        var headlineId = "fw-headline-" + idx;
        var copyTextId = "fw-copy-text-" + idx;
        var stepHtml = '<div class="fw-step"><div class="form-wrap">';

        // title
        stepHtml += '<div class="input form-field">';
        stepHtml += '<input type="text" class="fw-step-title" value="' + step.title + '" placeholder="' + wizard.i18n.title + '"></input>'
        stepHtml += '</div>';

        // headline
        stepHtml += '<div class="input form-field">';
        stepHtml += '<label for="' + headlineId + '"><b>' + wizard.i18n.headline + '</b></label>';
        stepHtml += '<input type="text" class="fw-step-headline" value="' + step.headline + '"></input>'
        stepHtml += '</div>';

        // copy text
        stepHtml += '<div class="input form-field">';
        stepHtml += '<label for="' + copyTextId + '"><b>' + wizard.i18n.copyText + '</b></label>';
        stepHtml += '<input type="text" class="fw-step-copy_text" value="' + step.copy_text + '"></input>'
        stepHtml += '</div>';

        // parts
        stepHtml += '<div class="fw-step-parts">' + renderParts(step.parts) + '</div>';

        stepHtml += '</div><div class="fw-clearfix"></div></div>';
        return stepHtml;
    }

    function renderStep(step) {
        var stepHtml = '<div class="postbox">' +
            '<div class="fw-collapsediv"><i class="fa fa-caret-down" aria-hidden="true"></i></div>' +
            '<div class="fw-movediv hndle ui-sortable-handle"><i class="fa fa-arrows"></i></div>' +
            '<h1 class="fw-step-h1 hndle ui-sortable-handle"><span>' +
            step.title + '</span></h1>' +
            '<div class="fw-trashdiv" title="remove step"><i class="fa fa-trash"></i></div>' +
            '<div class="fw-clearfix"></div>' +
            renderStepInside(step) +
            '<div class="fw-clearfix"></div>' +
            '</div>';
        return stepHtml;
    }

    function renderMailSettings(mailSettings){
      if(mailSettings) {
        if(mailSettings.subject) {
          $('.fw-mail-subject').val(mailSettings.subject);
        }
        if(mailSettings.to) {
          $('.fw-mail-to').val(mailSettings.to);
        }
        if(mailSettings.header) {
          $('.fw-mail-header').val(mailSettings.header);
        }
      }
    }

    function renderSteps(steps) {
        var i, n;
        var stepsHtml = '<div class="postbox-container"><div class="metabox-holder"><div class="meta-box-sortables">';
        for (i = 0, n = steps.length; i < n; i++) {
            stepsHtml += renderStep(steps[i], i);
        }
        stepsHtml += '</div></div></div>';
        $(container).html(stepsHtml);
    }


    /**
     * getRadioElementData - retrieve the data for a set of radio buttons
     *
     * @param $element the radio DOM element
     * @return an object with the radio header and options
     */
    function getRadioElementData($element) {
        var data = {};
        var type = data.type = $element.attr('data-type');
        if (type === 'option') {
            data.value = $element.find('.fw-radio-option').val();
        } else if (type === 'header') {
            data.value = $element.find('.fw-radio-header').val();
        }
        return data;
    }

    function getRadioData($radio, radio) {
        var elements = radio['elements'] = [];
        $radio.find('.fw-radio-option-element').each(function(idx, element) {
            elements.push(getRadioElementData($(element)));
        });
        radio['required'] = $radio.find('.fw-required').prop('checked');
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

    function getTextareaData($text, text) {
        text['label'] = $text.find('.fw-textarea-label').val();
        text['required'] = $text.find('.fw-required').prop('checked');
    }

    function getSubmitData($submit, submit){
      submit['requirename'] = $('.fw-submit-name').prop('checked');
      submit['requiremail'] = $('.fw-submit-mail').prop('checked');
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
                getRadioData($block, block)
                break;
            case 'checkbox':
                getCheckboxData($block, block)
                break;
            case 'text':
                getTextData($block, block)
                break;
            case 'textarea':
                getTextareaData($block, block)
                break;
            case 'submit':
                getSubmitData($block, block)
                break;
        }
        return block;
    }

    function getPartData($part) {
        var part = {};
        part['title'] = $part.find('.fw-part-title').val();
        var blocks = part['blocks'] = [];
        $part.find('.fw-step-block').each(function(idx, element) {
            blocks.push(getBlockData($(element)));
        });
        return part;
    }

    function getStepData($step) {
        var step = {};
        step['title'] = $step.find('.fw-step-title').val();
        step['headline'] = $step.find('.fw-step-headline').val();
        step['copy_text'] = $step.find('.fw-step-copy_text').val();
        var parts = step['parts'] = [];
        $step.find('.fw-step-part').each(function(idx, element) {
            parts.push(getPartData($(element)))
        });

        return step;
    }

    function getMailData() {
      var mailData = {};
      mailData.subject = $('.fw-mail-subject').val();
      mailData.to = $('.fw-mail-to').val();
      mailData.header = $('.fw-mail-header').val();
      return mailData;
    }

    function save() {
        var $container = $(container);
        var title  = $('.fw-wizard-title').val();
        var data = {
            title: title,
            wizard: {}
        };
        // data['title']
        data.wizard.steps = [];
        data.wizard.mail = getMailData();
        $container.find('.fw-step').each(
            function(idx, element) {
                data.wizard.steps.push(getStepData($(element)));
            }
        );

        log('save', data);
        log('ajaxurl', wizard.ajaxurl);
        log('nonce', wizard.nonce);

        $.ajax({
            type: 'POST',
            url: wizard.ajaxurl,
            dataType: 'json',
            data: {
                action: 'fw_wizard_save',
                data: data,
                nonce: wizard.nonce,
                id: wizard.id
            },
            success: function(response) {
                log('response', response);
                alertMessage(response.data.msg, response.success);
            },
            error: function(response) {
                log('fail', arguments);
                log('response', response);
            }
        });
    }

    /**
     * blockOut - description
     *
     * @param  event the event
     * @param  ui the ui element
     */
    function blockOut(event, ui) {
        log('blockOut', event, ui);
        $(event.target).removeClass('fw-over');
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
            start: function(event, ui) {
              var height = $(ui.item).height();
              $('.fw-block-placeholder').height(height);
            },
            update: function(event, ui) {
                warn('sortables update', event, ui);
                $(ui.item).removeAttr('style');
                setupDragNDrop();
            }
        });

        $('.fw-step-part .inside').sortable({
          opacity: 0.6,
          cursor: 'move',
          handle: '.fw-block-hndle',
          tolerance: 'intersect',
          placeholder: 'fw-block-placeholder',
          revert: 100,
          start: function(event, ui) {
            var height = $(ui.item).height();
            $('.fw-block-placeholder').height(height);
          },
          update: function(event, ui) {
              warn('block sortables update', event, ui);
              var blockType = $(ui.item).attr('data-type');
              if ($(ui.item).is('.fw-draggable-block')) {
                $(ui.item).replaceWith($(renderBlock({
                    type: blockType,
                    label: ''
                  }))
                );
              }
              setupDragNDrop();
              setupClickHandlers();
          }
        });

        //        make step divs toggleable
        //        console.log(postboxes);
        //        postboxes.add_postbox_toggles('mondula-form-wizard');

        var stepScope = 'fw-wizard-elements-scope';
        var blockScope = 'fw-wizard-block-scope';

        $(elementsContainer + ' .fw-draggable-block').draggable({
            connectToSortable : '.fw-step-part .inside',
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
      $('.fw-trashdiv').tooltip();
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

        $this.closest('.postbox').find('h1 > span').html($this.val());
    }


    /**
     * updateOptions - updates the radioOptions data-attribute after adding/removing
     *
     * @param  $container the radio container
     */
    function updateOptions($container) {
        $container.find('.fw-radio-option-element[data-type="option"] > label').each(
            function(idx, elt) {
                log('updateOptions', elt);
                $(elt).html('Option ' + (idx + 1));
            }
        )
    }


    /**
     * addStep - add a step to the wizard
     */
    function addStep() {
        var part = {
            title: '',
            blocks: []
        };
        var $step = $(renderStep({
            title: '',
            headline: '',
            copy_text: '',
            parts: [part]
        }));
        $step.appendTo($(container).find('.meta-box-sortables'));

        setupClickHandlers();
        setupDragNDrop();
        setupThickbox();

        // scroll down to new step
        $("html, body").animate({
            scrollTop: $(document).height()
        }, 500);

    }

    /**
     * checkRequired - description
     *
     * @param  block the block to check if it's required
     * @return the checked-attribure for html or nothing at all
     */
    function checkRequired(block) {
      if (block.required == 'true') {
        return 'checked';
      }
      return '';
    }

    /**
     * addPart - adds a part to a step
     *
     * @param  evt the addPart-Button in a step
     */
    function addPart(evt) {
        var target = evt.target;
        var $part = renderPart({
            title: '',
            blocks: []
        }, 'fw-step-part')
        $(target).closest('.fw-parts-footer').before($part);
        // setup handler for new part
        $('.fw-remove-part').click(function(event) {
            removePart(event);
        });
        setupThickbox();
    }

    function removeStep() {
      var $this = $(this);
      var $step = $this.closest('.postbox');
      var r = confirm("Do you really want to delete this step?");
      if (r == true) {
        $step.slideUp(700, function() {
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
        var r = confirm("Do you really want to delete this section?");
        if (r == true) {
          $part.slideUp(500, function() {
              $part.remove();
          });
        }
    }

    function removeBlock(evt) {
        var $block = $(evt.target).closest('.fw-step-block');
        var label = $block.find('.fw-block-label').val();
        var r = confirm("Do you really want to delete this block?\n\n" + label);
        if (r == true) {
          $block.slideUp(300, function() {
              $block.remove();
          });
        }
    }

    function setupThickbox() {
        $(".thickbox").click(function(thickEvent) {
            // RADIO BUTTONS
            $("#fw-thickbox-radio").unbind('click').click(function(thickRadioEvent) {
                tb_remove();
                var block = $(renderBlock({
                    type: 'radio'
                }));
                var $part = $(thickEvent.target).parents('.fw-step-part');
                $part.find('.inside').append(block);
                setupClickHandlers();
            });
            // CHECKBOX
            $("#fw-thickbox-checkbox").unbind('click').click(function(thickRadioEvent) {
              tb_remove();
              var block = $(renderBlock({
                  type: 'checkbox',
                  value: ''
              }));
              var $part = $(thickEvent.target).parents('.fw-step-part');
              $part.find('.inside').append(block);
              setupClickHandlers();
            });

            // TEXT FIELD
            $("#fw-thickbox-text").unbind('click').click(function(thickRadioEvent) {
              tb_remove();
              var block = $(renderBlock({
                  type: 'text',
                  value: ''
              }));
              var $part = $(thickEvent.target).parents('.fw-step-part');
              $part.find('.inside').append(block);
              setupClickHandlers();
            });
            // TEXT AREA
            $("#fw-thickbox-textarea").unbind('click').click(function(thickRadioEvent) {
              tb_remove();
              var block = $(renderBlock({
                  type: 'textarea',
                  value: ''
              }));
              var $part = $(thickEvent.target).parents('.fw-step-part');
              $part.find('.inside').append(block);
              setupClickHandlers();
            });
            // SUBMIT
            $("#fw-thickbox-submit").unbind('click').click(function(thickRadioEvent) {
              tb_remove();
              var block = $(renderBlock({
                  type: 'submit',
                  value: ''
              }));
              var $part = $(thickEvent.target).parents('.fw-step-part');
              $part.find('.inside').append(block);
              setupClickHandlers();
            });
        });
    }

    function setupClickHandlers(){
      // add step handler
      $('.fw-element-step').unbind( "click" ).click(function(event) {
          addStep();
      });

      // add part handler
      $('.fw-add-part').unbind( "click" ).click(function(event) {
          addPart(event);
          setupDragNDrop();
      });

      $('.fw-collapsediv').unbind( "click" ).click(function(event) {
          $(this).parent().find('.fw-step').slideToggle();
          $(this).find('.fa').toggleClass('fa-caret-right');
      });

      // remove part handler
      $('.fw-remove-part').unbind( "click" ).click(function(event) {
          removePart(event);
      })

      // remove block handler
      $('.fw-remove-block').unbind( "click" ).click(function(event) {
          removeBlock(event);
      })

      $('.fw-block-action > .fa-caret-down').unbind( "click" ).click(function(event) {
        $(this).parent().parent().toggleClass('fw-block-collapsed');
      });

      $('.fw-toggle-block').click(function(event) {
        var $block = $(this).parent().parent();
        if ($block.hasClass('fw-block-collapsed')) {
          var label = $block.find('.fw-block-label').val();
          $(this).siblings('h4').text(label.substring(0,60));
        } else {
          var blockType = $block.data('type');
          $(this).siblings('h4').text(blockType.substring(0,5));
        }
      });
    }


    /**
     * run - this function sets everything up
     */
    function run() {
        try {
            var w = JSON.parse(wizard.json);
            var $container = $(container);
            log(wizard);
            log(w);
            // load the wizard title
            $('.fw-wizard-title').val(w.title);

            renderSteps(w.wizard.steps);

            // get mail settings
            renderMailSettings(w.wizard.mail);

            $('.fw-button-save').click(save);

            //TODO: put all click handlers in the corresponding function

            // make elements sticky
            $(window).scroll(function() {
                var offset = $(container).offset().top;
                return function() {
                    var scrollTop = $(this).scrollTop();
                    if (scrollTop > offset) {
                        $(elementsContainer).addClass('fw-sticky');
                    } else {
                        $(elementsContainer).removeClass('fw-sticky');
                    }
                };
            }())

            // toggle postboxes
            $container.on('click', '.postbox .handlediv', function() {
                $(this).closest('.postbox').toggleClass('closed');
            });

            $container.on('click', '.fw-radio-add', function() {
                var $cnt = $(this).prev('.fw-radio-option-container');
                var idx = $cnt.children('.fw-radio-option-element').length;
                var opt = renderRadioOption('', idx);
                $(opt).appendTo($cnt);
                updateOptions($cnt);
            });

            $container.on('click', '.fw-remove-radio-option', function() {
                log('remove on click');
                var $this = $(this);
                var $container = $this.closest('.fw-radio-option-container');
                $this.closest('.fw-radio-option-element').remove();
                updateOptions($container);
            });

            $container.on('click', '.fw-trashdiv', removeStep);

            $(".fw-remove-part").tooltip({
                content: "Remove this section"
            });

            setupDragNDrop();
            setupTooltips();
            setupThickbox();
            setupClickHandlers();

            // tab menu toggle
            $('#fw-nav-mail').click(function(e) {
              $('#fw-nav-steps').toggleClass('nav-tab-active');
              $('#fw-nav-mail').toggleClass('nav-tab-active');
              $(container).hide();
              $(elementsContainer).hide();
              $('.fw-mail-settings-container').show();
            });

            $('#fw-nav-steps').click(function(e) {
              $('#fw-nav-steps').toggleClass('nav-tab-active');
              $('#fw-nav-mail').toggleClass('nav-tab-active');
              $('.fw-mail-settings-container').hide();
              $(container).show();
              $(elementsContainer).show();
            })

            // modal
            $('#fw-elements-modal').dialog({
                dialogClass: 'wp-dialog',
                modal: true,
                autoOpen: false,
                closeOnEscape: true,
                buttons: {
                    'Close': function() {
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
