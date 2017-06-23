jQuery(document).ready(function ($) {

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

    function createHandler(formId) {
        return StripeCheckout.configure({
            key: stripekey,
            token: function (token, args) {

                var $form = $(document).data('liveForm');
                var name = $("input[name='name']", $form).val();
                var redirectOnSuccess = $("input[name='redirectOnSuccess']", $form).val();
                var redirectPostID = $("input[name='redirectPostID']", $form).val();
                var redirectUrl = $("input[name='redirectUrl']", $form).val();
                var redirectToPageOrPost = $("input[name='redirectToPageOrPost']", $form).val();
                var showBillingAddress = parseInt($("input[name='showBillingAddress']", $form).val());

                $form.append("<input type='hidden' name='stripeToken' value='" + token.id + "' />");
                $form.append("<input type='hidden' name='stripeEmail' value='" + token.email + "' />");
                $form.append("<input type='hidden' name='form' value='" + name + "' />");
                $form.append("<input type='hidden' name='doRedirect' value='" + redirectOnSuccess + "' />");
                $form.append("<input type='hidden' name='redirectId' value='" + redirectPostID + "' />");
                $form.append("<input type='hidden' name='redirectUrl' value='" + redirectUrl + "' />");
                $form.append("<input type='hidden' name='redirectToPageOrPost' value='" + redirectToPageOrPost + "' />");

                //if billing address
                if (showBillingAddress == 1) {
                    $form.append("<input type='hidden' name='billing_name' value='" + args.billing_name + "' />");
                    $form.append("<input type='hidden' name='billing_address_country' value='" + args.billing_address_country + "' />");
                    $form.append("<input type='hidden' name='billing_address_zip' value='" + args.billing_address_zip + "' />");
                    $form.append("<input type='hidden' name='billing_address_state' value='" + args.billing_address_state + "' />");
                    $form.append("<input type='hidden' name='billing_address_line1' value='" + args.billing_address_line1 + "' />");
                    $form.append("<input type='hidden' name='billing_address_city' value='" + args.billing_address_city + "' />");
                }

                $form.append("<input type='hidden' name='closed_by' value='token_callback' />");

                var $err = $(".payment-errors__" + formId);
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: $form.serialize(),
                    cache: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            //inform user of success
                            $err.addClass('alert alert-success');
                            $err.html(data.msg);
                            $(document).removeData('liveForm');

                            //server tells us if redirect is required
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
                            logException('handler__' + formId, data);
                        }
                    },
                    error: function () {
                        $err.addClass('alert alert-error');
                        $err.html(wpfs_L10n.internal_error);
                        scrollToError($err);
                    },
                    complete: function () {
                        $('#show-loading__' + formId).hide();
                        $('.loading-animation').hide();
                    }

                });
            },
            closed: function () {
                var $form = $(document).data('liveForm');
                var closedBy = $("input[name='closed_by']", $form).val();
                if ('token_callback' != closedBy) {
                    $('#show-loading__' + formId).hide();
                    $('.loading-animation').hide();
                }
            }
        });
    }

    $('.fullstripe_checkout_form').submit(function (e) {
        e.preventDefault();
        var formId = $(this).data('form-id');
        var companyName = $("input[name='companyName']", this).val();
        var productDesc = $("input[name='productDesc']", this).val();
        var amount = $("input[name='amount']", this).val();
        var buttonTitle = $("input[name='buttonTitle']", this).val();
        var showBillingAddress = $("input[name='showBillingAddress']", this).val();
        var showRememberMe = $("input[name='showRememberMe']", this).val();
        var image = $("input[name='image']", this).val();
        var currency = $("input[name='currency']", this).val();
        var name = $("input[name='name']", this).val();
        var useBitcoin = $("input[name='useBitcoin']", this).val();
        var useAlipay = $("input[name='useAlipay']", this).val();

        $(document).data('liveForm', $(this));

        $('#show-loading__' + formId).show();
        var $err = $(".payment-errors__" + formId);
        $err.removeClass('alert alert-error alert-success');
        $err.html('');

        var handler = createHandler(formId);
        handler.open({
            name: companyName,
            description: productDesc,
            amount: amount,
            panelLabel: buttonTitle,
            billingAddress: (showBillingAddress == 1),
            allowRememberMe: (showRememberMe == 1),
            image: image,
            currency: currency,
            bitcoin: (useBitcoin == 1),
            alipay: (useAlipay == 1)
        });

        return false;
    });

});