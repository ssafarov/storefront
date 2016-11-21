"use strict";

// We need to wrap everything in an jquery IIFE
(function($) {
    var app = app || {};
    var page = 1;
    app.utils = {
        spinners: function(element) {
            $(element).spinner();
        },
        scrollToElement: function(target, offset, speed) {
            $('html, body').animate({
                scrollTop: $(target).offset().top + offset
            }, speed);
        }
    };

    app.global = {
        init: function() {
            this.carousel();
            this.mobileMenu();
            this.video();
            this.externalVideo();
            this.contactWidget();

            // Scroll to element
            $('.js-scroll-to').on('click', function(event) {
                event.preventDefault();

                var target = $(this).attr('href');
                app.utils.scrollToElement(target, -940, 200);
            });
        },

        carousel: function() {
            var owl = $('.carousel');
            owl.owlCarousel({
                loop: true,
                nav: true,
                margin: 30,
                responsiveClass: true,
                dots: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 6
                    }
                }
            });
        },

        mobileMenu: function() {
            // mobile menu toggle
            $('.mobile-menu-switch').on('click', function() {
                if ($('.mobile-menu-open').length > 0) {
                    $('.mobile-menu .opened')
                        .removeClass('opened')
                        .find('.sub-menu')
                        .slideUp();
                }
                $('.mobile-menu').toggleClass('mobile-menu-open');
            });


            // menu item
            var $item = $('.mobile-menu .menu-item-has-children');

            // prevent following link if item has children
            $item.find(' > a').on('click', function(e) {
                e.preventDefault();
            });

            // sub-menus slide opening
            $item.on('click', function(e) {
                var $this = $(this);
                var $opened = $('.mobile-menu .opened');

                function toggle() {
                    $this
                        .toggleClass('opened')
                        .find('.sub-menu')
                        .slideToggle();
                }

                if ($this.hasClass('opened')) { // if item clicked is opened
                    toggle();
                } else if ($opened.length > 0) { // if item clicked is closed, but other opened
                    $opened
                        .removeClass('opened')
                        .find('.sub-menu')
                        .slideUp(function() {
                            toggle();
                        });
                } else { // if nothing is opened
                    toggle();
                }
            });
        },

        video: function() {

            $('.js-video-trigger').on('click', function(event) {
                event.preventDefault();
                // mejs.players.mep_0.remove();

                new MediaElement('home_player', {
                    success: function(mediaElement, domObject) {
                        // call the play method
                        mediaElement.play();

                        if ($(domObject).is(':visible')) {
                            mediaElement.stop();
                        };
                    },
                });

                $(this).parents('.hero').find('.mejs-container').fadeToggle(200);
                $(this).parents('.hero').find('.close-video').fadeToggle(200);
            });
        },

        externalVideo: function() {
            // Fix z-index youtube video embedding
            $('iframe').each(function() {
                //Miss form Zoho
                if(this.name == 'captchaFrame') {
                    return;
                }

                var url = $(this).attr("src");

                // check if url is from youtube and doesn't already have the string we want to concat
                if (url.indexOf('youtube.com') > -1 && url.indexOf('?wmode=transparent') == -1) {
                    $(this).attr("src", url + "?wmode=transparent");
                }
            });
        },

        contactWidget: function() {
            var widget = $('.widget-contact');
            var widgetTrigger = widget.find('.widget-contact__header');
            var widgetTriggerXS = widget.find('.btn-xs-close');

            widgetTrigger.click(function() {
                if (widget.hasClass('widget-contact--up')) {
                    widget.addClass('widget-contact--down').removeClass('widget-contact--up');
                    widgetTrigger.removeClass('widget-contact__header--full').find('.icon-minus').removeClass('icon-minus').addClass('icon-plus');
                } else {
                    widget.removeClass('widget-contact--down').addClass('widget-contact--up');
                    widgetTrigger.addClass('widget-contact__header--full').find('.icon-plus').removeClass('icon-plus').addClass('icon-minus');
                }
            });
            widgetTriggerXS.click(function() {
                if (widget.hasClass('widget-contact--up')) {
                    widget.addClass('widget-contact--down').removeClass('widget-contact--up');
                    widgetTrigger.removeClass('widget-contact__header--full').find('.icon-minus').removeClass('icon-minus').addClass('icon-plus');
                } else {
                    widget.removeClass('widget-contact--down').addClass('widget-contact--up');
                    widgetTrigger.addClass('widget-contact__header--full').find('.icon-plus').removeClass('icon-plus').addClass('icon-minus');
                }
                return false;
            });

        }
    };

    app.distributors = {
        init: function() {
            app.utils.spinners('.distributor-home .product-info input[type="number"]');
            this.collapse();
            this.helpSelects();
        },

        collapse: function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(event) {
                var accordion = $('.accordion');

                accordion.collapse();
            });
        },

        helpSelects: function() {
            var $inputHelpSerialNumber = $('#help-serial-number').find('input');
            $inputHelpSerialNumber.val('N/A');
            $('#help-issue-type').find('select').val('General enquiry');
            $('#help-feedback-nature').on('change', function() {
                var $issueType = $('#help-issue-type');
                var $this = $(this);

                switch ($this.find('select').val()) {
                    case 'Technical':
                    case 'Sales':
                    case 'Complaint':
                        $issueType.fadeIn();
                        $this.removeClass('col-md-12');
                        $this.addClass('col-md-6');
                        break;
                    default:
                        $issueType.find('select').val('General enquiry');
                        $issueType.fadeOut();
                        $this.removeClass('col-md-6');
                        $this.addClass('col-md-12');
                        $('#help-serial-number').slideUp();
                        $inputHelpSerialNumber.val('N/A');
                        break;
                }
            });
            $('#help-issue-type').on('change', function() {
                if ($(this).find('select').val() === 'Specific issue') {
                    $('#help-serial-number').slideDown();
                    $inputHelpSerialNumber.val('');
                } else {
                    $('#help-serial-number').slideUp();
                    $inputHelpSerialNumber.val('N/A');
                }
            });
        }

    };

    //set the index counter
    var i = 0;

    //convert all .nav-tabs, except those with the class .keep-tabs
    $('.nav-tabs_custom:not(.keep-tabs)').each(function() {

        //init variables
        var $select,
            c = 0,
            $select = $('<select class="mobile-nav-tabs-' + i + ' mobile-tab-headings form-control"></select>');

        //add unique class to nav-tabs, so each select works independently
        $(this).addClass('nav-tabs-index-' + i);

        //loop though each <li>, building each into an <option>, getting the text from the a href
        $(this).children('li').each(function() {
            $select.append('<option value="' + c + '">' + $(this).text() + '</option>');
            c++;
        });

        //insert new <select> above <ul nav-tabs>
        $(this).before($select);

        //increase index counter
        i++
    });

    //on changing <select> element
    $('[class^=mobile-nav-tabs]').on('change', function(e) {

        //get index from selected option
        var classArray = $(this).attr('class').split(" ");
        var tabIndexArray = classArray[0].split("mobile-nav-tabs-")
        var tabIndex = tabIndexArray[1];

        //using boostrap tabs, show the selected option tab
        $('.nav-tabs-index-' + tabIndex + ' li a').eq($(this).val()).tab('show');
    });

    function format_price(value, ts, ds, round, currency) {
        var ns = String(value.toFixed(round)), ps=ns, ss="";
        var i = ns.indexOf(ds);
        if (i!=-1) {
            ps = ns.substring(0,i);
            ss = ns.substring(i+1);
        }
        return currency + ps.replace(/(\d)(?=(\d{3})+([.]|$))/g,"$1"+ts)+ds+ss;
    }

    function showCartPopup (newItemAdded){
        var newItemAdded = newItemAdded || false;

        var $cartPopup = $('#cart-popup');
        var $cartHeader = $('#cart-popup-header');
        var $cartListItems = $('#new-cart-items-list');

        var cartHeaderClass = 'item-added';
        var cartClass = 'new-cart';
        var cartMobileClass = cartClass+'-mobile';

        var cartHeader = newItemAdded? "Item added to cart":"Your cart";
        var cartHeaderFinalClass = (newItemAdded) ? cartHeaderClass:"";

        if (($cartPopup.length > 0)) {
            if ($(window).width() <= 768) {
                $cartPopup.removeClass(cartClass);
                $cartPopup.addClass(cartMobileClass);
                $cartPopup.css('width', $(window).width());
            } else {
                $cartPopup.removeClass(cartMobileClass);
                $cartPopup.addClass(cartClass);
                $cartPopup.css('width','');
            }

            if ($cartPopup.hasClass(cartMobileClass)&&(!newItemAdded)&&($("a.view_cart_button").length>0)){
                $("a.view_cart_button")[0].click();
                return false;
            }

            if ($cartHeader.length > 0) {
                $cartHeader.removeClass(cartHeaderClass);
                $cartHeader.addClass(cartHeaderFinalClass);
                $cartHeader.text(cartHeader);
            }
            if ($cartPopup.hasClass('hidden')){
                $cartPopup.removeClass('hidden');
                if ($cartListItems.length > 0){
                    $cartListItems.perfectScrollbar();
                }
            } else {
                $cartPopup.addClass('hidden');
            }
        }
    }

    function redirect (to){
        to = typeof to !== 'undefined' ? to : 1;
        var current = window.location.href,
            productPos = current.indexOf ('product'),
            shopPos = current.indexOf ('shop');

        if (to == 1) { // Going to ROW
            window.location.href = productPos > -1 ? '/shop/' : '/shop/'+current.substr(shopPos + 5);
        } else if (to == 4) { // Going to USA
            window.location.href = productPos > -1 ? '/usa/shop/' : '/usa/shop/'+current.substr(shopPos + 5);
        }

        return false;

    }

    // Load on DOM ready
    $(document).ready(function() {

        var boxHeight = $( '.clarify-region-popup' ).height(),
            windowHeight = $(window).height(),
            offset = ( windowHeight - boxHeight ) / 2;

        $( '.clarify-region-popup' ).css( { 'position' : 'fixed', 'top' : offset + 'px' } );

        $('.close-btn').click(function () {
            $('.clarify-region-popup').hide();
            $('.clarify-region-overlay').hide();
        });

        $('.region-selector').click(function() {
            var regionControl = $("select[name='region']"),
                region = regionControl.val(),
                to = region == 'us'? 4:1;

            if (region.length == 0) {
                regionControl.addClass ('error');
            } else {
                regionControl.removeClass ('error');
                redirect(to);
            }
            return false;
        });
        $('.hover-region, .hover-region-row').click(function() {
            var to = $(this).hasClass('us')? 4:1;
            redirect(to);
        });

        $('.hover-region, .hover-region-row').hover(function() {
            var row = $(this).hasClass('hover-region-row');
            if (row) {
                $('.hover-region-row').find('img').show();
            }else {
                $(this).find('img').show();
            }
        }, function() {
            var row = $(this).hasClass('hover-region-row');
            if (row) {
                $('.hover-region-row').find('img').hide();
            }else {
                $(this).find('img').hide();
            }
        });

        $('.img-zoom').hover(function() {
            $(this).addClass('transition');

        }, function() {
            $(this).removeClass('transition');
        });

        $.validate({
            form: $('form'),
            language: {
                requiredFields: 'Field cannot be blank',
                notConfirmed: 'Passwords do not match'
                    //lengthTooShortStart: 'Password should have at least 8 characters'
            }
        });

        $('.tooltip').tooltipster();

        // TODO: Refactor this
        $('.fancy-radios input').click(function(event) {
            $('input:not(:checked)').parent().removeClass("active");
            $('input:checked').parent().addClass("active");
        });

        // Image right click disabled fix
        if (Element.NativeEvents && Element.NativeEvents.hasOwnProperty('contextmenu') && Element.NativeEvents['contextmenu'] === 2){
            Element.NativeEvents['contextmenu'] = 3;
        }

        if (($('#woochimp_widget_subscription_submit').length > 0)&&(!$('#woochimp_widget_subscription_submit').hasClass('btn btn-tertiary'))) {
            $('#woochimp_widget_subscription_submit').addClass('btn btn-tertiary');
        }

        if ($('#new-cart-items-list').length > 0){
            $('#new-cart-items-list').perfectScrollbar();
        }

        $(document).bind('added_to_cart', function(event, fragments, cart_hash, button) {
            showCartPopup(true);
        });

        $(document).on( 'touchstart click mouseenter', "a#cart-link", function(event) {
            showCartPopup();
            event.preventDefault();
            event.stopPropagation();
            return false;
        });

        $( document ).on( 'click', "a.remove-product", function(event) {
            var $topCartCountLine = jQuery('.buy-counter'),
                $topCartLabelItems = jQuery('.buy-items'),
                $removeProductId = jQuery(this).attr("data-product_id"),
                $removeProductKey = jQuery(this).attr("data-product_key"),
                $removeProductCount = jQuery(this).attr("data-product_count"),
                $removeProductPrice = jQuery(this).attr("data-product_price"),
                $cartTotalPriceLine = jQuery("#cart-total"),
                $removeProductLine = jQuery(this).closest('.new-cart-item'),
                $cartListItems = $('#new-cart-items-list');

            var topCartlabel, cartTotalPriceFormatted;

            var cartTotalPrice = ($cartTotalPriceLine.length > 0) ? $cartTotalPriceLine.attr("data-cart_total"): 0;
            var topCartTotalQty = ($topCartCountLine.length > 0) ? $topCartCountLine.attr("data-cart_total_count"):0;

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: globalFuel.ajaxUrl,
                data: { action: "product_remove",
                        product_key: $removeProductKey,
                        product_id: $removeProductId
                },
                success: function(data){
                    if ($removeProductLine.length > 0) {
                        $removeProductLine.remove();
                        topCartTotalQty = (topCartTotalQty > $removeProductCount)? topCartTotalQty - $removeProductCount : 0;
                        if ($cartTotalPriceLine.length > 0) {
                            cartTotalPrice = $cartTotalPriceLine.attr("data-cart_total")-$removeProductPrice;
                            cartTotalPriceFormatted = format_price(cartTotalPrice, $cartTotalPriceLine.attr("data-cart_price_th_separator"), $cartTotalPriceLine.attr("data-cart_price_dc_separator"), $cartTotalPriceLine.attr("data-cart_price_decimals"), $cartTotalPriceLine.attr("data-cart_currency"));
                            $cartTotalPriceLine.attr("data-cart_total", cartTotalPrice);
                            $cartTotalPriceLine.find ("span.amount").text(cartTotalPriceFormatted);
                        }

                        if (topCartTotalQty > 0) {
                            topCartlabel = (topCartTotalQty > 1) ? 'items':'item';
                            $topCartCountLine.attr("data-cart_total_count", topCartTotalQty);
                            $cartTotalPriceLine.attr("data-cart_total", cartTotalPrice);
                            $topCartCountLine.text (topCartTotalQty);
                            $topCartLabelItems.text (topCartlabel);
                            $cartListItems.perfectScrollbar('update');
                        } else {
                            $topCartCountLine.closest('a').remove();
                        }
                    }
                },
                error: function(data){
                    console.log('Product removal error');
                    console.log(data);
                    console.log('***');
                }
            });
            event.stopPropagation;
            event.preventDefault;
            return false;
        });

        $('html').click(function() {
            var $cartPopup = $('#cart-popup');
            if ($cartPopup.length > 0) {
                $cartPopup.addClass('hidden');
            }
        });

        $(document).on( 'click', '.show_more_posts-fetching, .show_more_posts-nomoreleft', function( event ) {
            event.preventDefault();
            return false;
        });

        $(document).on( 'click', '.show_more_posts-button', function( event ) {
            event.preventDefault();

            var $getPostsButton = $('#get_older_posts'),
                $paginationContainer = $('.pagination'),
                $origPostButton = $getPostsButton.html(),
                fetchingText = 'Fetching...';

            $getPostsButton.html (fetchingText);

            $getPostsButton.removeClass('show_more_posts-button');
            $getPostsButton.addClass('show_more_posts-fetching');

            $.ajax({
                url: globalFuel.ajaxUrl,
                type: 'post',
                dataType: 'json',
                data: {
                    action: "get_older_posts",
                    page: page
                },
                success: function( result ) {

                    if (result["success"] == true) {
                        console.log (result);
                        $paginationContainer.before(result["posts"]);

                        page++;
                    }

                    $getPostsButton.html ($origPostButton);
                    if (result["last"] == true) {
                        $getPostsButton.html ('No more posts left');
                        $getPostsButton.addClass('show_more_posts-nomoreleft');
                    } else {
                        $getPostsButton.addClass('show_more_posts-button');
                        $getPostsButton.removeClass('show_more_posts-fetching');
                    }
                },
                error: function( result ){
                    console.log('Get older posts error');
                    console.log(result['status']);
                    console.log('***');

                    $getPostsButton.html ($origPostButton);
                    $getPostsButton.addClass('show_more_posts-button');
                    $getPostsButton.removeClass('show_more_posts-fetching');
                }
            })
        });

        app.global.init();
        app.distributors.init();

    });
})(jQuery);
