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
    }
}

jQuery(document).ready(function ($) {

    function scrollToError($err) {
        if ($err && $err.offset() && $err.offset().top) {
            if (!isInViewport($err)) {
                $('html, body').animate({
                    scrollTop: $err.offset().top - 100
                }, 1000);
            }
        }
        if ($err) {
            $err.fadeIn(500).fadeOut(500).fadeIn(500);
        }
    }

    function isInViewport($elem) {
        var $window = $(window);

        var docViewTop = $window.scrollTop();
        var docViewBottom = docViewTop + $window.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    function createResponseHandlerByFormId(formId) {
        return function (status, response) {

            var $form = $('form[data-form-id=' + formId + ']');
            var $err = $(".payment-errors__" + formId);

            if (response.error) {
                // Show the errors
                $err.addClass('alert alert-error');
                if (response.error.code && wpfs_L10n.hasOwnProperty(response.error.code)) {
                    $err.html(wpfs_L10n[response.error.code]);
                } else {
                    $err.html(response.error.message);
                }
                scrollToError($err);
                $form.find('button').prop('disabled', false);
                $('#show-loading__' + formId).hide();
            } else {
                // token contains id, last4, and card type
                var token = response.id;
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

                //post payment via ajax
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: $form.serialize(),
                    cache: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            //clear form fields
                            $form.find('input:text, input:password').val('');
                            $('#fullstripe-custom-amount__' + formId).prop('selectedIndex', 0);
                            $('#fullstripe-plan__' + formId).prop('selectedIndex', 0);
                            $('#fullstripe-address-country__' + formId).prop('selectedIndex', 0);
                            //inform user of success
                            $err.addClass('alert alert-success');
                            $err.html(data.msg);
                            $form.find('button').prop('disabled', false);
                            scrollToError($err);
                            if (data.redirect) {
                                setTimeout(function () {
                                    window.location = data.redirectURL;
                                }, 1500);
                            }
                        } else {
                            // show the errors on the form
                            $err.addClass('alert alert-error');
                            $err.html(data.msg);
                            scrollToError($err);
                            logException('Stripe form ' + formId, data);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $err.addClass('alert alert-error');
                        $err.html(wpfs_L10n.internal_error);
                        scrollToError($err);
                    },
                    complete: function () {
                        $form.find('button').prop('disabled', false);
                        $("#show-loading__" + formId).hide();
                    }
                });
            }
        };
    }

    $(".loading-animation").hide();

    $('.fullstripe-custom-amount').change(function () {
        var formId = $(this).data('form-id');
        var showAmount = $(this).data('show-amount');
        var buttonTitle = $(this).data('button-title');
        var currencySymbol = $(this).data('currency-symbol');
        var amount = parseFloat($(this).val());
        var buttonTitleParams = [];
        buttonTitleParams.push(buttonTitle);
        buttonTitleParams.push(currencySymbol);
        buttonTitleParams.push(amount);
        if (showAmount == '1') {
            $('#payment-form-submit__' + formId).html(vsprintf("%s %s %0.2f", buttonTitleParams));
        }
    });

    $('.payment-form').submit(function (e) {
        var formId = $(this).data('form-id');
        $("#show-loading__" + formId).show();

        var $err = $(".payment-errors__" + formId);
        $err.removeClass('alert alert-error');
        $err.html("");

        var $form = $(this);

        // Disable the submit button
        $form.find('button').prop('disabled', true);

        var responseHandler = createResponseHandlerByFormId(formId);
        Stripe.createToken($form, responseHandler);
        return false;
    });

    $('.payment-form-compact').submit(function (e) {
        var formId = $(this).data('form-id');
        $("#show-loading__" + formId).show();
        var $err = $(".payment-errors__" + formId);
        $err.removeClass('alert alert-error');
        $err.html("");

        var $form = $(this);

        // Disable the submit button
        $form.find('button').prop('disabled', true);

        var responseHandler = createResponseHandlerByFormId(formId);
        Stripe.createToken($form, responseHandler);
        return false;
    });

    var coupon = false;
    $('.fullstripe-plan').change(function () {
        var formId = $(this).data('form-id');
        var plan = $("#fullstripe-plan__" + formId).val();
        var planSelector = "option[value='" + plan + "']";
        var setupFee = parseInt($("#fullstripe-setup-fee__" + formId).val());
        var option = $("#fullstripe-plan__" + formId).find($('<div/>').html(planSelector).text());
        var interval = option.attr('data-interval');
        var intervalCount = parseInt(option.attr("data-interval-count"));
        var amount = parseFloat(option.attr('data-amount') / 100);
        var currencySymbol = option.attr("data-currency");

        var planDetailsPattern = wpfs_L10n.plan_details_with_singular_interval;
        var planDetailsParams = [];
        planDetailsParams.push(currencySymbol);
        planDetailsParams.push(amount);

        if (intervalCount > 1) {
            planDetailsPattern = wpfs_L10n.plan_details_with_plural_interval;
            planDetailsParams.push(intervalCount);
            planDetailsParams.push(interval);
        } else {
            planDetailsParams.push(interval);
        }

        if (coupon != false) {
            planDetailsPattern = intervalCount > 1 ? wpfs_L10n.plan_details_with_plural_interval_with_coupon : wpfs_L10n.plan_details_with_singular_interval_with_coupon;
            var total;
            if (coupon.percent_off != null) {
                total = amount * (1 - ( parseInt(coupon.percent_off) / 100 ));
            } else {
                total = amount - parseFloat(coupon.amount_off) / 100;
            }
            total = total.toFixed(2);
            planDetailsParams.push(total);
            $(this).parents('form:first').append($('<input type="hidden" name="amount_with_coupon_applied">').val(total * 100));
        }

        if (setupFee > 0) {
            planDetailsPattern = intervalCount > 1 ? (coupon != false ? wpfs_L10n.plan_details_with_plural_interval_with_coupon_with_setupfee : wpfs_L10n.plan_details_with_plural_interval_with_setupfee) : (coupon != false ? wpfs_L10n.plan_details_with_singular_interval_with_coupon_with_setupfee : wpfs_L10n.plan_details_with_singular_interval_with_setupfee);
            var sf = (setupFee / 100).toFixed(2);
            planDetailsParams.push(currencySymbol);
            planDetailsParams.push(sf);
        }

        var planDetailsMessage = vsprintf(planDetailsPattern, planDetailsParams);
        $("#fullstripe-plan-details__" + formId).text(planDetailsMessage);

    }).change();

    $('.payment-form-coupon').click(function (e) {
        var formId = $(this).data('form-id');
        e.preventDefault();
        var cc = $('#fullstripe-coupon-input__' + formId).val();
        if (cc.length > 0) {
            $(this).prop('disabled', true);
            var $err = $(".payment-errors__" + formId);
            $err.removeClass('alert alert-success');
            $err.removeClass('alert alert-error');
            $err.html("");
            $('#show-loading-coupon__' + formId).show();

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {action: 'wp_full_stripe_check_coupon', code: cc},
                cache: false,
                dataType: "json",
                success: function (data) {
                    if (data.valid) {
                        coupon = data.coupon;
                        $('#fullstripe-plan__' + formId).change();
                        $err.addClass('alert alert-success');
                        $err.html(data.msg);
                        scrollToError($err);
                    } else {
                        $err.addClass('alert alert-error');
                        $err.html(data.msg);
                        scrollToError($err);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $err.addClass('alert alert-error');
                    $err.html(wpfs_L10n.internal_error);
                    scrollToError($err);
                },
                complete: function () {
                    $('#fullstripe-check-coupon-code__' + formId).prop('disabled', false);
                    $('#show-loading-coupon__' + formId).hide();
                }
            });
        }
        return false;
    });

});