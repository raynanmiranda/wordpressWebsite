/*
 Plugin Name: WP Full Stripe
 Plugin URI: https://paymentsplugin.com
 Description: Complete Stripe payments integration for Wordpress
 Author: Mammothology
 Version: 3.7.0
 Author URI: https://paymentsplugin.com
 */

Stripe.setPublishableKey(stripekey);

function logError(handlerName, jqXHR, textStatus, errorThrown) {
    if (window.console) {
        console.log(handlerName + '.error(): textStatus=' + textStatus);
        console.log(handlerName + '.error(): errorThrown=' + errorThrown);
        if (jqXHR) {
            console.log(handlerName + '.error(): jqXHR.status=' + jqXHR.status);
            console.log(handlerName + '.error(): jqXHR.responseText=' + jqXHR.responseText);
        }
    }
}

function logException(source, response) {
    if (window.console && response) {
        if (response.ex_msg) {
            console.log('ERROR: source=' + source + ', message=' + response.ex_msg);
        }
        if (response.ex_stack) {
            console.log('ERROR: source=' + source + ', stack=' + response.ex_stack);
        }
    }
}
function findTemplateById() {
    var selectedTemplate;
    if (emailReceipts.hasOwnProperty(emailReceipts.currentTemplateId)) {
        selectedTemplate = emailReceipts[emailReceipts.currentTemplateId];
    }
    return selectedTemplate;
}
function saveEmailReceiptTemplateValues($) {
    var selectedTemplate = findTemplateById();
    if (selectedTemplate) {
        selectedTemplate.subject = $('#email_receipt_subject').val();
        selectedTemplate.html = $('#email_receipt_html').val();
    }
    return selectedTemplate;
}

jQuery(document).ready(function ($) {

    var regexPattern_AN_DASH_U = /^[a-zA-Z0-9-_]+$/;

    var $loading = $(".showLoading");
    var $update = $("#updateDiv");
    $loading.hide();
    $update.hide();

    $('#receiptEmailTypePlugin').click(function () {
        $('#email_receipt_row').show();
        $('#email_receipt_sender_address_row').show();
        $('#admin_payment_receipt_row').show();
    });
    $('#receiptEmailTypeStripe').click(function () {
        $('#email_receipt_row').hide();
        $('#email_receipt_sender_address_row').hide();
        $('#admin_payment_receipt_row').hide();
    });

    $('#email_receipt_template').change(function () {

        // tnagy save current values
        var selectedTemplate = saveEmailReceiptTemplateValues($);

        emailReceipts.currentTemplateId = $('#email_receipt_template').val();

        // tnagy update subject and html fields
        selectedTemplate = findTemplateById();
        if (selectedTemplate) {
            $('#email_receipt_subject').val(selectedTemplate.subject);
            $('#email_receipt_html').val(selectedTemplate.html);
        }
    });

    // tnagy select first template on page load
    $('#email_receipt_template option[selected="selected"]').each(
        function () {
            $(this).removeAttr('selected');
        }
    );
    $("#email_receipt_template option:first").attr('selected', 'selected').change();

    function resetForm($form) {
        $form.find('input:text, input:password, input:file, select, textarea').val('');
        $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    }

    function validField(field, fieldName, errorField) {
        var valid = true;
        if (field.val() === "") {
            showError(fieldName + " must contain a value");
            valid = false;
        }
        return valid;
    }

    function validFieldByRegex(field, regexPattern, errorMessage) {
        var valid = true;
        if (!regexPattern.test(field.val())) {
            showError(errorMessage);
            valid = false;
        }
        return valid;
    }

    function validFieldByLength(field, len, errorMessage) {
        var valid = true;
        if (field.val().length > len) {
            showError(errorMessage);
            valid = false;
        }
        return valid;
    }

    function validFieldWithMsg(field, msg) {
        var valid = true;
        if (field.val() === "") {
            showError(msg);
            valid = false;
        }
        return valid;
    }

    function showError(message) {
        showMessage('error', 'updated', message);
    }

    function showUpdate(message) {
        showMessage('updated', 'error', message);
    }

    function showMessage(addClass, removeClass, message) {
        $update.removeClass(removeClass);
        $update.addClass(addClass);
        $update.html("<p>" + message + "</p>");
        $update.show();
        document.body.scrollTop = document.documentElement.scrollTop = 0;
    }

    function clearUpdateAndError() {
        $update.html("");
        $update.removeClass('error');
        $update.removeClass('update');
        $update.hide();
        $(".error").remove();
    }

    //for uploading images using WordPress media library
    var custom_uploader;

    function uploadImage(inputID) {
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $(inputID).val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();
    }

    // called on form submit when we know includeCustomFields = 1
    function processCustomFields(form) {
        var valid = true;
        var count = $('#customInputNumberSelect').val();
        var customValues = '';
        for (var i = 1; i <= count; i++) {
            // first validate the field
            var field = '#form_custom_input_label_' + i;
            var fieldName = 'Custom Input Label ' + i;
            valid = validField($(field), fieldName, $update);
            valid = valid && validFieldByLength($(field), 40, 'You can enter up to 40 characters for ' + fieldName);
            if (!valid) return false;
            // save the value, stripping all single & double quotes
            customValues += $(field).val().replace(/['"]+/g, '');
            if (i < count)
                customValues += '{{';
        }

        // now append to the form
        form.append('<input type="hidden" name="customInputs" value="' + customValues + '"/>');

        return valid;
    }

    function validate_redirect() {
        var valid_redirect;
        if ($('#do_redirect_yes').prop('checked')) {
            if ($('#form_redirect_to_page_or_post').prop('checked')) {
                valid_redirect = validFieldWithMsg($('#form_redirect_page_or_post_id'), 'Select page or post to redirect to');
            } else if ($('#form_redirect_to_url').prop('checked')) {
                valid_redirect = validFieldWithMsg($('#form_redirect_url'), 'Enter an URL to redirect to', $update);
            } else {
                showError('You must check at least one redirect type');
                valid_redirect = false;
            }
        } else {
            valid_redirect = true;
        }
        return valid_redirect;
    }

    function validate_checkout_redirect() {
        var valid_redirect;
        if ($('#do_redirect_yes_ck').prop('checked')) {
            if ($('#form_redirect_to_page_or_post_ck').prop('checked')) {
                valid_redirect = validFieldWithMsg($('#form_redirect_page_or_post_id_ck'), 'Select page or post to redirect to');
            } else if ($('#form_redirect_to_url_ck').prop('checked')) {
                valid_redirect = validFieldWithMsg($('#form_redirect_url_ck'), 'Enter an URL to redirect to', $update);
            } else {
                showError('You must check at least one redirect type');
                valid_redirect = false;
            }
        } else {
            valid_redirect = true;
        }
        return valid_redirect;
    }

    function do_ajax_post(ajaxUrl, form, successMessage, doRedirect) {
        $loading.show();
        // Disable the submit button
        form.find('button').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: ajaxUrl,
            data: form.serialize(),
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    showUpdate(successMessage);
                    resetForm(form);

                    if (doRedirect) {
                        setTimeout(function () {
                            window.location = data.redirectURL;
                        }, 1000);
                    }
                } else {
                    // show the errors on the form
                    if (data.msg) {
                        showError(data.msg);
                    }
                    logException('do_ajax_post', data);
                    if (data.validation_result) {
                        var elementWithError = null;
                        for (var f in data.validation_result) {
                            if (data.validation_result.hasOwnProperty(f)) {
                                $('input[name=' + f + ']').after('<div class="error"><p>' + data.validation_result[f] + '</p></div>');
                                elementWithError = f;
                            }
                        }
                        if (elementWithError) {
                            var $el = $('input[name=' + elementWithError + ']');
                            if ($el && $el.offset() && $el.offset().top);
                            $('html, body').animate({
                                scrollTop: $el.offset().top
                            }, 2000);
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                showError('An error occurred. See Javascript console for details');
                logError('do_ajax_post', jqXHR, textStatus, errorThrown);
            },
            complete: function () {
                $loading.hide();
                form.find('button').prop('disabled', false);
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }
        });
    }

    function enable_combobox() {
        $('#createPaymentFormSection .page_or_post-combobox-input').prop('disabled', false);
        $('#createPaymentFormSection .page_or_post-combobox-toggle').button("option", "disabled", false);
        $('#create-subscription-form .page_or_post-combobox-input').prop('disabled', false);
        $('#create-subscription-form .page_or_post-combobox-toggle').button("option", "disabled", false);
        $('#edit-payment-form .page_or_post-combobox-input').prop('disabled', false);
        $('#edit-payment-form .page_or_post-combobox-toggle').button("option", "disabled", false);
        $('#edit-subscription-form .page_or_post-combobox-input').prop('disabled', false);
        $('#edit-subscription-form .page_or_post-combobox-toggle').button("option", "disabled", false);
    }

    function enable_combobox_ck() {
        $('#createCheckoutFormSection .page_or_post-combobox-input').prop('disabled', false);
        $('#createCheckoutFormSection .page_or_post-combobox-toggle').button("option", "disabled", false);
        $('#edit-checkout-form .page_or_post-combobox-input').prop('disabled', false);
        $('#edit-checkout-form .page_or_post-combobox-toggle').button("option", "disabled", false);
    }

    function disable_combobox() {
        $('#createPaymentFormSection .page_or_post-combobox-input').prop('disabled', true);
        $('#createPaymentFormSection .page_or_post-combobox-toggle').button("option", "disabled", true);
        $('#create-subscription-form .page_or_post-combobox-input').prop('disabled', true);
        $('#create-subscription-form .page_or_post-combobox-toggle').button("option", "disabled", true);
        $('#edit-payment-form .page_or_post-combobox-input').prop('disabled', true);
        $('#edit-payment-form .page_or_post-combobox-toggle').button("option", "disabled", true);
        $('#edit-subscription-form .page_or_post-combobox-input').prop('disabled', true);
        $('#edit-subscription-form .page_or_post-combobox-toggle').button("option", "disabled", true);
    }

    function disable_combobox_ck() {
        $('#createCheckoutFormSection .page_or_post-combobox-input').prop('disabled', true);
        $('#createCheckoutFormSection .page_or_post-combobox-toggle').button("option", "disabled", true);
        $('#edit-checkout-form .page_or_post-combobox-input').prop('disabled', true);
        $('#edit-checkout-form .page_or_post-combobox-toggle').button("option", "disabled", true);
    }

    function init_page_or_post_redirect() {
        $('#form_redirect_to_url').prop('checked', false);
        $('#form_redirect_to_page_or_post').prop('checked', true);
        $('#form_redirect_to_page_or_post').prop('disabled', false);
        $('#form_redirect_to_url').prop('disabled', false);
        enable_combobox();
        $('#form_redirect_page_or_post_id').prop('disabled', false);
        $('#form_redirect_url').prop('disabled', false);
    }

    function init_page_or_post_redirect_ck() {
        $('#form_redirect_to_url_ck').prop('checked', false);
        $('#form_redirect_to_page_or_post_ck').prop('checked', true);
        $('#form_redirect_to_page_or_post_ck').prop('disabled', false);
        $('#form_redirect_to_url_ck').prop('disabled', false);
        enable_combobox_ck();
        $('#form_redirect_page_or_post_id_ck').prop('disabled', false);
        $('#form_redirect_url_ck').prop('disabled', false);
    }

    $('.plan_checkbox_list').sortable({
        placeholder: "ui-sortable-placeholder",
        stop: function (event, ui) {
            var plan_id_order = $(this).sortable('toArray', {attribute: 'data-plan-id'});
            $('input[name=plan_order]').val(encodeURIComponent(JSON.stringify(plan_id_order)));
        }
    });

    $('#create-subscription-plan').submit(function (e) {
        clearUpdateAndError();

        var valid = validField($('#sub_id'), 'ID', $update);
        valid = valid && validField($('#sub_name'), 'Name', $update);
        valid = valid && validField($('#sub_amount'), 'Amount', $update);
        valid = valid && validField($('#sub_cancellation_count'), 'Payment Cancellation Count', $update);
        valid = valid && validField($('#sub_trial'), 'Trial Days', $update);

        if (valid) {
            var $form = $(this);
            do_ajax_post(admin_ajaxurl, $form, "Plan created.", false);
        }

        return false;

    });

    $('#create-subscription-form').submit(function (e) {
        clearUpdateAndError();

        //get the checked plans
        var checkedVals = $('.plan_checkbox:checkbox:checked').map(function () {
            return decodeURIComponent(this.value);
        }).get();
        var plans = encodeURIComponent(JSON.stringify(checkedVals));

        var valid = validField($('#form_name'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#form_title'), 'Form Title', $update);

        if (valid && checkedVals.length === 0) {
            showError("You must check at least one subscription plan");
            valid = false;
        }

        valid = valid && validate_redirect();

        var includeCustom = $('input[name=form_include_custom_input]:checked', '#create-subscription-form').val();
        if (includeCustom == 1) {
            valid = valid && processCustomFields($(this)); //NOTE: must do this last as it appends a hidden input.
        }

        if (valid) {
            var $form = $(this);
            //create a plans field for all the checked plans
            $form.append("<input type='hidden' name='selected_plans' value='" + plans + "' />");
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Subscription form created.", true);
        }

        return false;
    });

    $('#edit-subscription-form').submit(function (e) {
        clearUpdateAndError();

        //get the checked plans
        var checkedVals = $('.plan_checkbox:checkbox:checked').map(function () {
            return decodeURIComponent(this.value);
        }).get();
        var plans = encodeURIComponent(JSON.stringify(checkedVals));

        var valid = validField($('#form_name'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#form_title'), 'Form Title', $update);

        if (valid && checkedVals.length === 0) {
            showError("You must check at least one subscription plan");
            valid = false;
        }

        valid = valid && validate_redirect();

        var includeCustom = $('input[name=form_include_custom_input]:checked', '#edit-subscription-form').val();
        if (includeCustom == 1) {
            valid = valid && processCustomFields($(this)); //NOTE: must do this last as it appends a hidden input.
        }

        if (valid) {
            var $form = $(this);
            //create a plans field for all the checked plans
            $("<input>").attr("type", "hidden").attr("name", "selected_plans").attr("value", plans).appendTo($form);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Subscription form updated.", true);
        }

        return false;
    });

    $('#edit-subscription-plan').submit(function (e) {
        clearUpdateAndError();

        var valid = validField($('#form_plan_display_name'), 'Display name', $update);

        if (valid) {
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Subscription plan updated.", true);
        }

        return false;
    });

    $('#set_specific_amount, #set_custom_amount').click(function () {
        $('#payment_amount_list_row').hide();
        $('#payment_amount_row').show();
    });
    $('#set_amount_list').click(function () {
        $('#payment_amount_row').hide();
        $('#payment_amount_list_row').show();
        $('#payment_amount_value').focus();
    });

    function tabFocusRestrictor(lastItem, firstItem) {
        $(lastItem).blur(function () {
            $(firstItem).focus();
        });
    }

    tabFocusRestrictor('#payment_amount_description', '#add_payment_amount_button');

    $('#add_payment_amount_button').click(function (event) {
        event.preventDefault();
        $('.tooltip_error').remove();
        $('.field_error').removeClass('field_error');

        var value = $('#payment_amount_value').val();
        var description = $('#payment_amount_description').val();
        var validation_result = [];
        if (!value) {
            validation_result['payment_amount_value'] = "Required";
        } else if (isNaN(value)) {
            validation_result['payment_amount_value'] = "Numbers only";
        } else if (value.length > 6) {
            validation_result['payment_amount_value'] = "Too long";
        }
        if (!description) {
            validation_result['payment_amount_description'] = "Required";
        } else if (value.length > 128) {
            validation_result['payment_amount_description'] = "Too long";
        }

        if (!validation_result.hasOwnProperty('payment_amount_value') && !validation_result.hasOwnProperty('payment_amount_description')) {
            $('#payment_amount_list')
                .append(
                    $('<li>')
                        .addClass('ui-state-default')
                        .attr('title', 'You can reorder this list by using drag\'n\'drop.')
                        .attr('data-toggle', 'tooltip')
                        .attr('data-payment-amount-value', value)
                        .attr('data-payment-amount-description', description)
                        .append(
                            $('<a>')
                                .addClass('dd_delete')
                                .attr('href', '#')
                                .html('Delete')
                                .click(function (event) {
                                    event.preventDefault();
                                    $(this).closest('li').remove();
                                }))
                        .append($('<span>').addClass('amount').html(sprintf("%s %0.2f", currencySymbol, value / 100)))
                        .append($('<span>').addClass('desc').html(description))
                );

            $('#payment_amount_value').val('');
            $('#payment_amount_description').val('');
        } else {
            if (validation_result.hasOwnProperty('payment_amount_description')) {
                $('#payment_amount_description').addClass('field_error').prop('data-toggle', 'tooltip').prop('title', validation_result.payment_amount_description).focus();
            }
            if (validation_result.hasOwnProperty('payment_amount_value')) {
                $('#payment_amount_value').addClass('field_error').prop('data-toggle', 'tooltip').prop('title', validation_result.payment_amount_value).focus();
            }
        }
    });

    $('#payment_amount_list li a.dd_delete').click(function (event) {
        event.preventDefault();
        $(this).closest('li').remove();
    });
    $('#payment_amount_list').sortable({
        placeholder: "ui-sortable-placeholder",
        stop: function (event, ui) {
            var amounts = $(this).sortable('toArray', {attribute: 'data-payment-amount-value'});
            var descriptions = $(this).sortable('toArray', {attribute: 'data-payment-amount-description'});
            $('input[name=payment_amount_values]').val(amounts);
            $('input[name=payment_amount_descriptions]').val(descriptions);
        }
    });

    function updatePaymentAmountsAndDescriptions() {
        var amounts = $('#payment_amount_list').sortable('toArray', {attribute: 'data-payment-amount-value'});
        var descriptions = $('#payment_amount_list').sortable('toArray', {attribute: 'data-payment-amount-description'});
        $('input[name=payment_amount_values]').val(amounts);
        $('input[name=payment_amount_descriptions]').val(descriptions);
    }

    $('#create-payment-form').submit(function (e) {
        clearUpdateAndError();

        updatePaymentAmountsAndDescriptions();

        var customAmount = $('input[name=form_custom]:checked', '#create-payment-form').val();
        var includeCustom = $('input[name=form_include_custom_input]:checked', '#create-payment-form').val();

        var valid = validField($('#form_name'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#form_title'), 'Form Title', $update);
        if (customAmount == 'specified_amount') {
            valid = valid && validField($('#form_amount'), 'Amount', $update);
        }
        valid = valid && validate_redirect();
        if (includeCustom == 1) {
            valid = valid && processCustomFields($(this)); //NOTE: must do this last as it appends a hidden input.
        }

        if (valid) {
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Payment form created.", true);
        }

        return false;
    });

    $('#edit-payment-form').submit(function (e) {
        clearUpdateAndError();

        updatePaymentAmountsAndDescriptions();

        var customAmount = $('input[name=form_custom]:checked', '#edit-payment-form').val();
        var includeCustom = $('input[name=form_include_custom_input]:checked', '#edit-payment-form').val();

        var valid = validField($('#form_name'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#form_title'), 'Form Title', $update);
        if (customAmount == 'specified_amount') {
            valid = valid && validField($('#form_amount'), 'Amount', $update);
        }
        valid = valid && validate_redirect();
        if (includeCustom == 1) {
            valid = valid && processCustomFields($(this)); //NOTE: must do this last as it appends a hidden input.
        }

        if (valid) {
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Payment form updated.", true);
        }

        return false;
    });

    $('#create-checkout-form').submit(function (e) {
        clearUpdateAndError();

        var valid = validField($('#form_name_ck'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name_ck'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#company_name_ck'), 'Company Name', $update);
        valid = valid && validField($('#form_amount_ck'), 'Amount', $update);
        valid = valid && validate_checkout_redirect();

        if (valid) {
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Checkout form created.", true);
        }

        return false;
    });

    $('#edit-checkout-form').submit(function (e) {
        clearUpdateAndError();

        var valid = validField($('#form_name_ck'), 'Name', $update);
        valid = valid && validFieldByRegex($('#form_name_ck'), regexPattern_AN_DASH_U, 'Form Name should contain only alphanumerical characters, dashes, underscores, and whitespaces.');
        valid = valid && validField($('#company_name_ck'), 'Company Name', $update);
        valid = valid && validField($('#form_amount_ck'), 'Amount', $update);
        valid = valid && validate_checkout_redirect();

        if (valid) {
            $loading.show();
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Checkout form updated.", true);
        }

        return false;
    });

    //upload checkout form images
    $('#upload_image_button').click(function (e) {
        e.preventDefault();
        uploadImage('#form_checkout_image');
    });

    $('#settings-stripe-form').submit(function (e) {
        clearUpdateAndError();
        var $form = $(this);
        //post form via ajax
        do_ajax_post(admin_ajaxurl, $form, "Settings updated.", true);
        return false;
    });
    $('#settings-appearance-form').submit(function (e) {
        clearUpdateAndError();
        var $form = $(this);
        //post form via ajax
        do_ajax_post(admin_ajaxurl, $form, "Settings updated.", true);
        return false;
    });
    $('#settings-email-receipts-form').submit(function (e) {
        clearUpdateAndError();

        // tnagy save current email receipt template values before post
        saveEmailReceiptTemplateValues($);
        delete emailReceipts.currentTemplateId;
        $('#email_receipts').val(encodeURIComponent(JSON.stringify(emailReceipts)));

        var $form = $(this);
        //post form via ajax
        do_ajax_post(admin_ajaxurl, $form, "Settings updated.", true);
        return false;
    });

    // tnagy forms shortcode button
    $("[data-shortcode]").tooltip({
        items: "span.shortcode-tooltip",
        position: {
            my: "right top",
            at: "center bottom+15"
        },
        content: function () {
            var shortcode = $(this).data('shortcode');
            var shortcodeInput = $("<input>").attr("type", "text").attr("class", "large-text").attr("size", shortcode.length).attr("readonly", "").attr("value", shortcode);
            shortcodeInput.data("item", $(this).attr("id"));
            shortcodeInput.focus(function (event, handler) {
                $(this).select();
            });
            shortcodeInput.blur(function (event, handler) {
                var item = $(this).data("item");
                $("#" + item).tooltip("close");
            });
            return shortcodeInput;
        },
        open: function (event, ui) {
            $(document).find("div.ui-tooltip input.large-text").focus();
        }
    });
    $("span.shortcode-tooltip").on("tooltipopen", function (event, ui) {
        $(this).data("tooltip-visible", true);
    });
    $("span.shortcode-tooltip").on("tooltipclose", function (event, ui) {
        $(this).data("tooltip-visible", false);
    });
    $("a.shortcode-payment").click(function () {
        var formId = $(this).data("form-id");
        var $tooltip = $("#shortcode-payment-tooltip__" + formId);
        if ($tooltip.data("tooltip-visible")) {
            $tooltip.tooltip("close");
        } else {
            $tooltip.tooltip("open");
        }
    });
    $("a.shortcode-checkout").click(function () {
        var formId = $(this).data("form-id");
        var $tooltip = $("#shortcode-checkout-tooltip__" + formId);
        if ($tooltip.data("tooltip-visible")) {
            $tooltip.tooltip("close");
        } else {
            $tooltip.tooltip("open");
        }
    });
    $("a.shortcode-subscription").click(function () {
        var formId = $(this).data("form-id");
        var $tooltip = $("#shortcode-subscription-tooltip__" + formId);
        if ($tooltip.data("tooltip-visible")) {
            $tooltip.tooltip("close");
        } else {
            $tooltip.tooltip("open");
        }
    });

    //The forms delete button
    $('button.delete').click(function () {
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var to_confirm = $(this).attr('data-confirm');
        if (to_confirm == null) {
            to_confirm = 'true';
        }
        var confirm_message = 'Are you sure you want to delete the record?';
        var update_message = 'Record deleted.';
        var action = '';
        if (type === 'paymentForm') {
            action = 'wp_full_stripe_delete_payment_form';
            confirm_message = 'Are you sure you want to delete this payment form?';
            update_message = 'Payment form deleted.';
        } else if (type === 'subscriptionForm') {
            action = 'wp_full_stripe_delete_subscription_form';
            confirm_message = 'Are you sure you want to delete this subscription form?';
            update_message = 'Subscription form deleted.';
        } else if (type === 'checkoutForm') {
            action = 'wp_full_stripe_delete_checkout_form';
            confirm_message = 'Are you sure you want to delete this checkout form?';
            update_message = 'Checkout form deleted.';
        } else if (type === 'subscriber') {
            action = 'wp_full_stripe_cancel_subscription';
            confirm_message = 'Are you sure you would like to cancel this subscription?';
            update_message = 'Subscription cancelled.'
        } else if (type === 'subscription_record') {
            action = 'wp_full_stripe_delete_subscription_record';
            confirm_message = 'Are you sure you want to delete this subscription record from the Wordpress database?';
            update_message = 'Subscription record cancelled.'
        } else if (type === 'payment') {
            action = 'wp_full_stripe_delete_payment';
        } else if (type === 'subscriptionPlan') {
            action = 'wp_full_stripe_delete_subscription_plan';
            confirm_message = 'Are you sure you want to delete this subscription plan?';
            update_message = 'Subscription plan deleted.';
        }

        var row = $(this).parents('tr:first');

        var confirmed = true;
        if (to_confirm === 'true' || to_confirm === 'yes') {
            confirmed = confirm(confirm_message);
        }
        if (confirmed == true) {
            $.ajax({
                type: "POST",
                url: admin_ajaxurl,
                data: {id: id, action: action},
                cache: false,
                dataType: "json",
                success: function (data) {

                    if (data.success) {
                        var remove = true;
                        if (data.remove == false) {
                            remove = false;
                        }
                        if (remove == true) {
                            $(row).remove();
                        }

                        if (data.redirectURL) {
                            setTimeout(function () {
                                window.location = data.redirectURL;
                            }, 1000);
                        }
                        showUpdate(update_message);
                    } else {
                        logException('button.delete.click', data);
                    }

                }
            });
        }

        return false;

    });

    $('input#stripe-webhook-url').focus(function () {
        $(this).select();
    });

    $('#create-recipient-form').submit(function (e) {
        e.preventDefault();
        $update.removeClass('error');
        $update.text("");

        var $form = $(this);

        var valid = validField($('#recipient_name'), 'Recipient Name', $update);

        if (valid) {
            $loading.show();
            // Disable the submit button
            $form.find('button').prop('disabled', true);
            $(document).data('formSubmit', $form);
            Stripe.bankAccount.createToken($form, stripeResponseHandler);
        }
        return false;
    });

    $('#create-recipient-form-card').submit(function (e) {
        e.preventDefault();
        $update.removeClass('error');
        $update.text("");

        var $form = $(this);

        var valid = validField($('#recipient_name_card'), 'Recipient Name', $update);

        if (valid) {
            $loading.show();
            // Disable the submit button
            $form.find('button').prop('disabled', true);
            // get the pay to type to know what kind of token to create
            $(document).data('formSubmit', $form);
            Stripe.createToken($form, stripeResponseHandler);
        }
        return false;
    });

    $('#create-transfer-form').submit(function (e) {
        clearUpdateAndError();

        var valid = validField($('#transfer_amount'), 'Transfer Amount', $update);

        if (valid) {
            var $form = $(this);
            //post form via ajax
            do_ajax_post(admin_ajaxurl, $form, "Transfer initiated.", false);
        }
        return false;

    });

    /////////////////////////

    var stripeResponseHandler = function (status, response) {
        var $form = $(document).data('formSubmit');

        if (response.error) {
            // Show the errors
            showError(response.error.message);
            $form.find('button').prop('disabled', false);
            $loading.hide();
        }
        else {
            // token contains bank account
            var token = response.id;
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

            //post payment via ajax
            $.ajax({
                type: "POST",
                url: admin_ajaxurl,
                data: $form.serialize(),
                cache: false,
                dataType: "json",
                success: function (data) {
                    $loading.hide();

                    if (data.success) {
                        //clear form fields
                        $form.find('input:text, input:password').val('');
                        //inform user of success
                        showUpdate(data.msg);
                        $form.find('button').prop('disabled', false);
                    } else {
                        // re-enable the submit button
                        $form.find('button').prop('disabled', false);
                        // show the errors on the form
                        showError(data.msg);
                        logException('stripeResponseHandler', data);
                    }
                }
            });
        }
    };

    /////////////////////

    $('#customInputNumberSelect').change(function () {
        var val = $(this).val();
        var $c2 = $('.ci2');
        var $c3 = $('.ci3');
        var $c4 = $('.ci4');
        var $c5 = $('.ci5');
        if (val == 1) {
            $c2.hide();
            $c3.hide();
            $c4.hide();
            $c5.hide();
        }
        else if (val == 2) {
            $c2.show();
            $c3.hide();
            $c4.hide();
            $c5.hide();
        }
        else if (val == 3) {
            $c2.show();
            $c3.show();
            $c4.hide();
            $c5.hide();
        }
        else if (val == 4) {
            $c2.show();
            $c3.show();
            $c4.show();
            $c5.hide();
        }
        else if (val == 5) {
            $c2.show();
            $c3.show();
            $c4.show();
            $c5.show();
        }
    }).change();

    //payment type toggle
    $('#set_custom_amount').click(function () {
        $('#form_amount').prop('disabled', true);
    });
    $('#set_specific_amount').click(function () {
        $('#form_amount').prop('disabled', false);
    });

    $('#form_redirect_to_page_or_post').change(function () {
        if ($(this).prop('checked')) {
            enable_combobox();
            $('#redirect_to_page_or_post_section').show();
            $('#redirect_to_url_section').hide();
        } else {
            disable_combobox();
            $('#redirect_to_page_or_post_section').hide();
        }
    });
    $('#form_redirect_to_url').change(function () {
        if ($(this).prop('checked')) {
            $('#redirect_to_page_or_post_section').hide();
            $('#redirect_to_url_section').show();
        } else {
            $('#redirect_to_url_section').hide();
        }
    });
    $('#form_redirect_to_page_or_post_ck').change(function () {
        if ($(this).prop('checked')) {
            enable_combobox_ck();
            $('#redirect_to_page_or_post_ck_section').show();
            $('#redirect_to_url_ck_section').hide();
        } else {
            disable_combobox_ck();
            $('#redirect_to_page_or_post_ck_section').hide();
        }
    });
    $('#form_redirect_to_url_ck').change(function () {
        if ($(this).prop('checked')) {
            $('#redirect_to_page_or_post_ck_section').hide();
            $('#redirect_to_url_ck_section').show();
        } else {
            $('#redirect_to_url_ck_section').hide();
        }
    });
    $('#do_redirect_no').click(function () {
        $('#form_redirect_page_or_post_id').val($('#form_redirect_page_or_post_id').prop('defaultSelected'));
        $('#form_redirect_url').val('');

        $('#form_redirect_to_page_or_post').prop('disabled', true);
        $('#form_redirect_to_url').prop('disabled', true);
        disable_combobox();
        $('#form_redirect_page_or_post_id').prop('disabled', true);
        $('#form_redirect_url').prop('disabled', true);
    });

    $('#do_redirect_yes').click(function () {
        $('#redirect_to_url_section').hide();
        init_page_or_post_redirect();
        $('#redirect_to_page_or_post_section').show();
    });
    $('#do_redirect_no_ck').click(function () {
        $('#form_redirect_page_or_post_id_ck').val($('#form_redirect_page_or_post_id_ck').prop('defaultSelected'));
        $('#form_redirect_url_ck').val('');
        $('#form_redirect_to_page_or_post_ck').prop('disabled', true);
        $('#form_redirect_to_url_ck').prop('disabled', true);
        disable_combobox_ck();
        $('#form_redirect_page_or_post_id_ck').prop('disabled', true);
        $('#form_redirect_url_ck').prop('disabled', true);
    });
    $('#do_redirect_yes_ck').click(function () {
        $('#redirect_to_url_ck_section').hide();
        init_page_or_post_redirect_ck();
        $('#redirect_to_page_or_post_ck_section').show();
    });
    //form type toggle
    $('#set_payment_form_type_payment').click(function () {
        $("#createCheckoutFormSection").hide();
        $("#createPaymentFormSection").show();
    });
    $('#set_payment_form_type_checkout').click(function () {
        $("#createCheckoutFormSection").show();
        $("#createPaymentFormSection").hide();
    });

    $('#set_recipient_bank_account').click(function () {
        $("#createRecipientCard").hide();
        $("#createRecipientBank").show();
    });
    $('#set_recipient_debit_card').click(function () {
        $("#createRecipientCard").show();
        $("#createRecipientBank").hide();
    });
    // custom inputs
    $('#noinclude_custom_input').click(function () {
        $('#customInputSection').hide();
    });
    $('#include_custom_input').click(function () {
        $('#customInputSection').show();
    });
    // page or post combobox
    $.widget("custom.page_or_post_combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                .addClass("page_or_post-combobox")
                .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .prop("disabled", true)
                .attr("title", "")
                .attr("placeholder", "Select from the list or start typing")
                .addClass("ui-widget")
                .addClass("ui-widget-content")
                .addClass("ui-corner-left")
                .addClass("page_or_post-combobox-input")
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source")
                })
                .tooltip({
                    tooltipClass: "ui-state-highlight"
                });
            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },

                autocompletechange: "_removeIfInvalid"
            });
        },

        _createShowAllButton: function () {
            var input = this.input,
                wasOpen = false;

            $("<a>")
                .attr("tabIndex", -1)
                .attr("title", "Show all page and post")
                .tooltip()
                .appendTo(this.wrapper)
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false,
                    disabled: true
                })
                .removeClass("ui-corner-all")
                .addClass("page_or_post-combobox-toggle ui-corner-right")
                .mousedown(function () {
                    wasOpen = input.autocomplete("widget").is(":visible");
                })
                .click(function () {
                    input.focus();

                    // Close if already visible
                    if (wasOpen) {
                        return;
                    }

                    // Pass empty string as value to search for, displaying all results
                    input.autocomplete("search", "");
                });
        },

        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && ( !request.term || matcher.test(text) ))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },

        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                .val("")
                .attr("title", value + " didn't match any item")
                .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });

    $("#form_redirect_page_or_post_id").page_or_post_combobox();
    $("#form_redirect_page_or_post_id_ck").page_or_post_combobox();

    // currency combobox
    $.widget("custom.currency_combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                .addClass("currency-combobox")
                .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .attr("title", "")
                .attr("placeholder", "Select from the list or start typing")
                .addClass("ui-widget")
                .addClass("ui-widget-content")
                .addClass("ui-corner-left")
                .addClass("currency-combobox-input")
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source")
                })
                .tooltip({
                    tooltipClass: "ui-state-highlight"
                });
            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },

                autocompletechange: "_removeIfInvalid"
            });
        },

        _createShowAllButton: function () {
            var input = this.input,
                wasOpen = false;

            $("<a>")
                .attr("tabIndex", -1)
                .attr("title", "Show all currencies")
                .tooltip()
                .appendTo(this.wrapper)
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false,
                    disabled: false
                })
                .removeClass("ui-corner-all")
                .addClass("currency-combobox-toggle ui-corner-right")
                .mousedown(function () {
                    wasOpen = input.autocomplete("widget").is(":visible");
                })
                .click(function () {
                    input.focus();

                    // Close if already visible
                    if (wasOpen) {
                        return;
                    }

                    // Pass empty string as value to search for, displaying all results
                    input.autocomplete("search", "");
                });
        },

        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && ( !request.term || matcher.test(text) ))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },

        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                .val("")
                .attr("title", value + " didn't match any item")
                .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });

    $("#currency").currency_combobox();
});