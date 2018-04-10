/*!-----------------------------------------------------------------
  Name: Youplay - Game Template based on Bootstrap
  Version: 3.1.0
  Author: nK
  Website: https://nkdev.info
  Support: https://nk.ticksy.com
  Purchase: http://themeforest.net/item/youplay-game-template-based-on-bootstrap/11306207?ref=_nK
  License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
  Copyright 2016.
-------------------------------------------------------------------*/
(function ($) {
    "use strict";

    /*------------------------------------------------------------------

      Youplay Instance

    -------------------------------------------------------------------*/
    var YP = function (options) {
        var _this = this;

        _this.options = options;

        _this.$window = $(window);
        _this.$document = $(document);

        // check if mobile
        _this.isMobile = /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/g.test(navigator.userAgent || navigator.vendor || window.opera);

        // navbar
        _this.navbarSmall = false;
        _this.navbarMaxTop = 100;
    };

    YP.DEFAULT = {
        // enable parallax
        parallax:         true,

        // set small navbar on load
        navbarSmall:      false,

        // enable fade effect between pages
        fadeBetweenPages: true,

        // twitter and instagram php paths
        php: {
            twitter: './php/twitter/tweet.php',
            instagram: './php/instagram/instagram.php'
        }
    };


    /*------------------------------------------------------------------

      Start Method

    -------------------------------------------------------------------*/
    YP.prototype.init = function (options) {
        var _this = this;

        // init options
        _this.options = $.extend({}, this.options, options);

        function initPlugins () {
            // navbar set to small
            if( !_this.options.navbarSmall ) {
                _this.options.navbarSmall = $('.navbar-youplay').hasClass('navbar-small');
            }

            // init tooltips and popovers
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });
            $('[data-toggle="popover"]').popover();

            // fade between pages
            _this.fadeBetweenPages();
            _this.initAnchors();
            _this.initFacebook();
            _this.initInstagram();
            _this.initTwitter();

            // Plugins
            _this.initOwlCarousel();
            _this.initMagnificPopup();
            _this.initSliderRevolution();
            _this.initIsotope();
            _this.initHexagonRating();

            // navbar collapse
            _this.navbarCollapse();

            // navbar set to small
            if(!_this.options.navbarSmall) {
                _this.$window.on('scroll', function () {
                    _this.navbarSize( _this.$window.scrollTop() );
                });
                _this.navbarSize( _this.$window.scrollTop() );
            }

            // navbar submenu fix
            // no close submenu if click on child submenu toggle
            _this.navbarSubmenuFix();

            // toggle search block
            _this.$document.on('click', '.search-toggle', function (e) {
                e.preventDefault();
                _this.searchToggle();
            });
            // close search on ESC press
            _this.$document.on('keyup', function (e) {
                if (e.keyCode === 27) {
                    _this.searchToggle('close');
                }
            });

            // active inputs
            _this.$document.on('focus', 'input, textarea', function () {
                _this.inputsActive( this, true );
            });
            _this.$document.on('blur', 'input, textarea', function () {
                _this.inputsActive( this );
            });
            // autofocus inputs
            $('input, textarea').filter('[autofocus]:eq(0)').focus();

            // ajax form
            _this.initAjaxForm();


            // accordions
            $('.youplay-accordion, .panel-group').find('.collapse').on('shown.bs.collapse', function () {
                $(this).parent().find('.icon-plus').removeClass('icon-plus').addClass('icon-minus');
                _this.refresh();
            }).on('hidden.bs.collapse', function () {
                $(this).parent().find('.icon-minus').removeClass('icon-minus').addClass('icon-plus');
                _this.refresh();
            });


            // jarallax init after all plugins
            _this.initJarallax();
        }

        if($('.page-preloader').length) {
            // after page load
            $(window).on('load', function () {
                initPlugins();

                // some timeout after plugins init
                setTimeout(function () {
                    // hide preloader
                    $('.page-preloader').fadeOut(function () {
                        $(this).find('> *').remove();
                    });
                }, 200);
            });
        } else {
            initPlugins();
            $(window).on('load', function () {
                _this.refresh();
            });
        }
    };

    // refresh method (refresh all plugins)
    YP.prototype.refresh = function () {
        window.dispatchEvent(new Event('resize'));
    };



    /*------------------------------------------------------------------

      Init Plugins

    -------------------------------------------------------------------*/
    // Owl Carousel
    YP.prototype.initOwlCarousel = function () {
        if( typeof $.fn.owlCarousel === 'undefined' ) {
            return;
        }

        var id = 0;

        $('.owl-carousel').each(function () {
            var className = 'youplay-carousel-' + id++;
            var autoplay = $(this).attr('data-autoplay');
            var stagePadding = parseFloat($(this).attr('data-stage-padding') || 0);
            var itemPadding = parseFloat($(this).attr('data-item-padding') || 0);
            $(this).owlCarousel({
                loop:true,
                stagePadding: stagePadding,
                dots:true,
                autoplay: !!autoplay,
                autoplayTimeout: autoplay,
                autoplaySpeed: 600,
                autoplayHoverPause: true,
                responsive:{
                    0:{
                        items:3
                    },
                    500:{
                        items:4
                    },
                    992:{
                        items:5
                    },
                    1200:{
                        items:6
                    }
                }
            }).addClass(className);
            if(itemPadding) {
                $('<style>.' + className + ' .owl-item { padding-left: ' + itemPadding + 'px; padding-right: ' + itemPadding + 'px; }</style>').appendTo('head');
            }
        });
        $('.youplay-carousel').each(function () {
            var className = 'youplay-carousel-' + id++;
            var autoplay = $(this).attr('data-autoplay');
            var stagePadding = parseFloat($(this).attr('data-stage-padding') || 70);
            var itemPadding = parseFloat($(this).attr('data-item-padding') || 0);
            $(this).addClass('owl-carousel').owlCarousel({
                loop:true,
                stagePadding: stagePadding,
                nav:true,
                dots: false,
                autoplay: !!autoplay,
                autoplayTimeout: autoplay,
                autoplaySpeed: 600,
                autoplayHoverPause: true,
                navText: ['', ''],
                responsive:{
                    0:{
                        items:1
                    },
                    500:{
                        items:2
                    },
                    992:{
                        items:3
                    },
                    1200:{
                        items:4
                    }
                }
            }).addClass(className);
            if(itemPadding) {
                $('<style>.' + className + ' .owl-item { padding-left: ' + itemPadding + 'px; padding-right: ' + itemPadding + 'px; }</style>').appendTo('head');
            }
        });
        $('.youplay-slider').each(function () {
            var className = 'youplay-carousel-' + id++;
            var autoplay = $(this).attr('data-autoplay');
            $(this).addClass('owl-carousel').owlCarousel({
                loop:true,
                nav:false,
                autoplay: autoplay?true:false,
                autoplayTimeout: autoplay,
                autoplaySpeed: 600,
                autoplayHoverPause: true,
                items: 1
            }).addClass(className);
        });
    };

    // Magnific Popup
    YP.prototype.initMagnificPopup = function () {
        if( typeof $.fn.magnificPopup === 'undefined' ) {
            return;
        }

        var mpOptions = {
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: false,
            mainClass: 'mfp-no-margins mfp-img-mobile mfp-with-fade',
            tLoading: '<div class="preloader"></div>',
            removalDelay: 300,
            image: {
                verticalFit: true,
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
            }
        };

        // image popup
        $('.image-popup').magnificPopup($.extend({
            type: 'image'
        }, mpOptions));

        // video popup
        $('.video-popup').magnificPopup($.extend({
            type: 'iframe'
        }, mpOptions));

        // gallery popup
        $('.gallery-popup').magnificPopup($.extend({
            delegate: '.owl-item:not(.cloned) a',
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
            },
            callbacks: {
                elementParse: function (item) {
                    // Function will fire for each target element
                    // "item.el" is a target DOM element (if present)
                    // "item.src" is a source that you may modify
                    var video = /youtube.com|youtu.be|vimeo.com/g.test( item.src );

                    if(video) {
                        item.type = 'iframe';
                    } else {
                        item.type = 'image';
                    }

                }
            }
        }, mpOptions));
    };

    // Slider Revolution
    YP.prototype.initSliderRevolution = function () {
        if( typeof $.fn.revolution === 'undefined' ) {
            return;
        }

        var _this = this;
        $('.rs-youplay').each(function () {
            var item = $(this);

            // options
            var rsOptions = {
                dottedOverlay:'none',
                // startwidth:1366,
                // startheight:768,
                navigationType:'bullet',
                navigationArrows:'solo',
                navigationStyle:'preview4',
                fullWidth: item.hasClass('rs-fullwidth')?'on':'off',
                fullScreen: item.hasClass('rs-fullscreen')?'on':'off',
                spinner:'spinner4'
            };

            // init
            var slider = item.find('.tp-banner').show().revolution(rsOptions);

            slider.on('revolution.slide.onloaded', function () {
                _this.refresh();
            });
        });
    };

    // Isotope
    YP.prototype.initIsotope = function () {
        if(typeof $.fn.isotope === 'undefined') {
            return;
        }

        var _this = this;

        $('.isotope').each(function () {
            var curIsotopeOptions = $(this).find('.isotope-options');

            // init items
            var $grid = $(this).find('.isotope-list').isotope({
                itemSelector: '.item'
            });

            // refresh for parallax images and isotope images position
            if($grid.imagesLoaded) {
                $grid.imagesLoaded().progress(function () {
                    $grid.isotope('layout');
                });
            }
            $grid.on('arrangeComplete', function () {
                self.refresh();
            });


            // click on filter button
            curIsotopeOptions.on('click', '> :not(.active)', function (e) {
                $(this).addClass('active').siblings().removeClass('active');
                var curFilter = $(this).attr('data-filter');

                e.preventDefault();

                $grid.isotope({
                    filter: function () {
                        if(curFilter === 'all') {
                            return true;
                        }

                        var itemFilters = $(this).attr('data-filters');

                        if(itemFilters) {
                            itemFilters = itemFilters.split(',');
                            for(var k in itemFilters) {
                                if(itemFilters[k].replace(/\s/g, '') === curFilter) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    }
                });
            });
        });
    };

    // Jarallax
    YP.prototype.initJarallax = function () {
        if(!this.options.parallax || this.isMobile) {
            return;
        }

        // in older versions used skrollr for parallax
        if(typeof skrollr !== 'undefined' && typeof this.skrollr === 'undefined') {
            this.skrollr = skrollr.init({
                smoothScrolling: false,
                forceHeight:false
            });
        }

        // in newest versions used Jarallax plugin
        if(typeof $.fn.jarallax === 'undefined') {
            return;
        }

        // banners
        $('.youplay-banner-parallax:not(.banner-top) .image').each(function () {
            var speed = parseFloat($(this).attr('data-speed'));
            $(this).jarallax({
                speed: isNaN(speed) ? 0.4 : speed
            });
        });

        // top banner
        $('.youplay-banner-parallax.banner-top').each(function () {
            var $image = $(this).children('.image');
            var $info = $(this).children('.info');
            $(this).jarallax({
                type: 'custom',
                imgSrc: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                imgWidth: 1,
                imgHeight: 1,
                onScroll: function (calc) {
                    var scrollImg = Math.min(250, 250 * (1 - calc.visiblePercent));
                    var scrollInfo = Math.min(150, 150 * (1 - calc.visiblePercent));
                    $image.css({
                        transform: 'translate3d(0, ' + scrollImg + 'px, 0)'
                    });
                    $info.css({
                        opacity: calc.visiblePercent < 0 ? 1 : calc.visiblePercent,
                        transform: 'translate3d(0, ' + scrollInfo + 'px, 0)'
                    });
                }
            });
        });

        // footer parallax
        $('.youplay-footer-parallax').each(function () {
            var $wrapper = $(this).children('.wrapper');
            var $social = $(this).find('.social > .container');
            $(this).jarallax({
                type: 'custom',
                imgSrc: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                imgWidth: 1,
                imgHeight: 1,
                onScroll: function (calc) {
                    var scroll = Math.max(-50, -50 * (1 - calc.visiblePercent));
                    $wrapper.css({
                        transform: 'translate3d(0, ' + scroll + '%, 0)'
                    });
                    $social.css({
                        transform: 'translate3d(0, 0, 0)',
                        opacity: calc.visiblePercent < 0 ? 1 : calc.visiblePercent
                    });
                }
            });
        });
    };


    /*------------------------------------------------------------------

      Init Facebook

    -------------------------------------------------------------------*/
    YP.prototype.initFacebook = function () {
        if(!$('.fb-page').length) {
            return;
        }
        var self = this;

        $('body').append('<div id="fb-root"></div>');

        (function (d, s, id) {
            if(location.protocol === 'file:') {
                return;
            }
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // resize on facebook widget loaded
        window.fbAsyncInit = function () {
            FB.Event.subscribe('xfbml.render', function () {
                self.refresh();
            });
        };
    };


    /*------------------------------------------------------------------

      Instagram

    -------------------------------------------------------------------*/
    YP.prototype.initInstagram = function () {
        var self = this;
        var $instagram = $('.youplay-instagram');
        if(!$instagram.length || !self.options.php.instagram) {
            return;
        }

        /**
         * Templating a instagram item using '{{ }}' braces
         * @param  {Object} data Instagram item details are passed
         * @return {String} Templated string
         */
        var templating = function (data, temp) {
            var temp_variables = ['link', 'image', 'caption'];

            for (var i = 0, len = temp_variables.length; i < len; i++) {
                temp = temp.replace(new RegExp('{{' + temp_variables[i] + '}}', 'gi'), data[temp_variables[i]]);
            }

            return temp;
        };

        $instagram.each(function () {
            var $this = $(this);
            var options = {
                userID: $this.attr('data-instagram-user-id') || null,
                count: $this.attr('data-instagram-count') || 6,
                template: $this.attr('data-instagram-template') || [
                    '<div class="col-xs-4">',
                    '    <a href="{{link}}" target="_blank">',
                    '        <img src="{{image}}" alt="{{caption}}" class="kh-img-stretch">',
                    '    </a>',
                    '</div>'
                ].join(' '),
                loadingText: 'Loading...',
                failText: 'Failed to load data',
                apiPath: self.options.php.instagram
            };

            if (!options.userID) {
                $.error('If you want to fetch instagram images, you must define the user ID. How to get it see here - http://jelled.com/instagram/lookup-user-id');
            }

            $this.html('<div class="col-xs-12">' + options.loadingText + '</div>');

            // Fetch instagram images
            $.getJSON(options.apiPath, {
                userID: options.userID,
                count: options.count
            }, function (response) {
                $this.html('');

                for (var i = 0; i < options.count; i++) {
                    var instaItem = false;
                    if(response[i]) {
                        instaItem = response[i];
                    } else if(response.statuses && response.statuses[i]) {
                        instaItem = response.statuses[i];
                    } else {
                        break;
                    }

                    var temp_data = {
                        link: instaItem.link,
                        image: instaItem.images.thumbnail.url,
                        caption: instaItem.caption
                    };

                    $this.append(templating(temp_data, options.template));
                }

                // resize window
                self.refresh();
            }).fail(function (a) {
                $this.html('<div class="col-xs-12">' + options.failText + '</div>');
                $.error(a.responseText);
            });

        });
    };


    /*------------------------------------------------------------------

      Twitter
      https://github.com/sonnyt/Tweetie

    -------------------------------------------------------------------*/
    YP.prototype.initTwitter = function () {
        var $twtFeeds = $('.youplay-twitter');
        var self = this;
        if(!$twtFeeds.length || !self.options.php.twitter) {
            return;
        }

        /**
         * Templating a tweet using '{{ }}' braces
         * @param  {Object} data Tweet details are passed
         * @return {String}      Templated string
         */
        var templating = function (data, temp) {
            var temp_variables = ['date', 'tweet', 'avatar', 'url', 'retweeted', 'screen_name', 'user_name'];

            for (var i = 0, len = temp_variables.length; i < len; i++) {
                temp = temp.replace(new RegExp('{{' + temp_variables[i] + '}}', 'gi'), data[temp_variables[i]]);
            }

            return temp;
        };

        $twtFeeds.each(function () {
            var $this = $(this);
            var options = {
                username: $this.attr('data-twitter-user-name') || null,
                list: null,
                hashtag: $this.attr('data-twitter-hashtag') || null,
                count: $this.attr('data-twitter-count') || 2,
                hideReplies: $this.attr('data-twitter-hide-replies') === 'true' ? true : false,
                template: $this.attr('data-twitter-template') || [
                    '<div>',
                    '    <div class="youplay-twitter-icon"><i class="fa fa-twitter"></i></div>',
                    '    <div class="youplay-twitter-date date">',
                    '        <span>{{date}}</span>',
                    '    </div>',
                    '    <div class="youplay-twitter-text">',
                    '       {{tweet}}',
                    '    </div>',
                    '</div>'
                ].join(' '),
                loadingText: 'Loading...',
                failText: 'Failed to load data',
                apiPath: self.options.php.twitter
            };

            if (options.list && !options.username) {
                $.error('If you want to fetch tweets from a list, you must define the username of the list owner.');
            }

            // Set loading
            $this.html('<span>' + options.loadingText + '</span>');

            // Fetch tweets
            $.getJSON(options.apiPath, {
                username: options.username,
                list: options.list,
                hashtag: options.hashtag,
                count: options.count,
                exclude_replies: options.hideReplies
            }, function (twt) {
                $this.html('');

                for (var i = 0; i < options.count; i++) {
                    var tweet = false;
                    if(twt[i]) {
                        tweet = twt[i];
                    } else if(twt.statuses && twt.statuses[i]) {
                        tweet = twt.statuses[i];
                    } else {
                        break;
                    }

                    var temp_data = {
                        user_name: tweet.user.name,
                        date: tweet.date_formatted,
                        tweet: tweet.text_entitled,
                        avatar: '<img src="'+ tweet.user.profile_image_url +'" />',
                        url: 'https://twitter.com/' + tweet.user.screen_name + '/status/' + tweet.id_str,
                        retweeted: tweet.retweeted,
                        screen_name: '@'+ tweet.user.screen_name
                    };

                    $this.append(templating(temp_data, options.template));
                }

                // resize window
                self.refresh();
            }).fail(function (a) {
                $this.html(options.failText);
                $.error(a.responseText);
            });
        });
    };


    /*------------------------------------------------------------------

      Fade Between Pages
      note: use .no-fade classname for links, that not need to fade

    -------------------------------------------------------------------*/
    YP.prototype.fadeBetweenPages = function () {
        var _this = this;

        // prevent fade between pages if there is no preloader
        if(!_this.options.fadeBetweenPages || !$('.page-preloader').length) {
            return;
        }

        _this.$document.on('click', 'a:not(.no-fade):not(.search-toggle):not(.image-popup):not(.video-popup):not([target="_blank"]):not(.btn):not(.button):not([href*="#"]):not([href^="mailto"]):not([href^="javascript:"])', function (e) {

            // prevent for gallery
            if($(this).parents('.gallery-popup:eq(0)').length) {
                return;
            }

            // stop if empty attribute
            if(!$(this).attr('href')) {
                return;
            }

            var href = this.href;

            if($(this).hasClass('dropdown-toggle')) {
                if($(this).next('.dropdown-menu').css('visibility') === 'hidden' || $(this).parent().hasClass('open')) {
                    return;
                }
            }

            if(href) {
                e.preventDefault();

                $('.page-preloader').fadeIn(400, function () {
                    window.location.href = href;
                });
            }
        });
    };

    /*------------------------------------------------------------------

      Anchors

    -------------------------------------------------------------------*/
    YP.prototype.initAnchors = function () {
        $(document).on('click', '.navbar a, a.btn', function (e) {
            var isHash = this.hash;
            var isURIsame = this.baseURI === window.location.href;

            if(isHash && isHash !== '#' && isHash !== '#!' && isURIsame) {
                var $hashBlock = $(isHash);
                var hash = isHash.replace(/^#/, '');
                if($hashBlock.length) {
                    // add hash to address bar
                    $hashBlock.attr('id', '');
                    document.location.hash = hash;
                    $hashBlock.attr('id', hash);

                    // scroll to block
                    $('html, body').stop().animate({
                        scrollTop: $hashBlock.offset().top - 80
                    }, 700);

                    e.preventDefault();
                }
            }
        });
    };

    /*------------------------------------------------------------------

      Navbar

    -------------------------------------------------------------------*/
    // navbar size
    YP.prototype.navbarSize = function (curTop) {
        if(curTop > this.navbarMaxTop && !this.navbarSmall) {
            this.navbarSmall = true;
            $('.navbar-youplay').addClass('navbar-small');
        }

        if(curTop <= this.navbarMaxTop && this.navbarSmall) {
            this.navbarSmall = false;
            $('.navbar-youplay').removeClass('navbar-small');
        }
    };

    // navbar collapse
    YP.prototype.navbarCollapse = function () {
        var _this = this;

        _this.$document.on('click', '.navbar-youplay [data-toggle=off-canvas]', function () {
            var $toggleTarget = $('.navbar-youplay').find($(this).attr('data-target'));
            var collapsed = $toggleTarget.hasClass('collapse');
            $toggleTarget[(collapsed ? 'remove' : 'add') + 'Class']('collapse');
            $('.navbar-youplay')[(collapsed ? 'add' : 'remove') + 'Class']('youplay-navbar-collapsed');
        });

        var resizeTimeout;
        _this.$window.on('resize', function () {
            $('.navbar-youplay').addClass('no-transition');

            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                $('.navbar-youplay').removeClass('no-transition');
            }, 50);
        });

        // close navbar if clicked on content
        _this.$document.on('click', '.youplay-navbar-collapsed ~ .content-wrap', function () {
            $('.navbar-youplay').find('[data-toggle=off-canvas]').click();
        });

        // prevent follow link when there is dropdown
        if(!_this.options.fadeBetweenPages || !$('.page-preloader').length) {
            _this.$document.on('click', '.navbar-youplay .dropdown-toggle', function () {
                if($(this).next('.dropdown-menu').css('visibility') === 'visible' && !$(this).parent().hasClass('open')) {
                    window.location.href = this.href;
                }
            });
        }
    };

    // navbar submenu fix
    // no close submenu if click on child submenu toggle
    YP.prototype.navbarSubmenuFix = function () {
        $('.navbar-youplay').on('click', '.dropdown-menu .dropdown-toggle', function (e) {
            $(this).parent('.dropdown').toggleClass('open');
            e.preventDefault();
            e.stopPropagation();
        });
    };

    /*------------------------------------------------------------------

      Ajax Contact Form

    -------------------------------------------------------------------*/
    YP.prototype.initAjaxForm = function () {
        this.$document.on('submit', '.youplay-form-ajax', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('[type="submit"]');

            // if disabled button - stop this action
            if($button.is('.disabled') || $button.is('[disabled]')) {
                return;
            }

            // post request
            $.post($(this).attr('action'), $(this).serialize(), function (data) {
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: data,
                        showConfirmButton: true,
                        confirmButtonClass: 'btn-default'
                    });
                    $form[0].reset();
                })
                .fail(function (data) {
                    swal({
                        type: 'error',
                        title: 'Error!',
                        text: data.responseText,
                        showConfirmButton: true,
                        confirmButtonClass: 'btn-default'
                    });
                });
        });
    };


    /*------------------------------------------------------------------

      Search Block

    -------------------------------------------------------------------*/
    // toggle search block
    YP.prototype.searchToggle = function (type) {
        var $searchBlock = $('.search-block');
        var opened = $searchBlock.hasClass('active');

        // no open when opened and no close when closed
        if( type === 'close' && !opened || type === 'open' && opened ) {
            return;
        }

        // hide
        if( opened ) {
            $searchBlock.removeClass('active');
        }

        // show
        else {
            $searchBlock.addClass('active');
            setTimeout(function () {
                $searchBlock.find('input').focus();
            }, 120);
        }
    };


    /*------------------------------------------------------------------

      Inputs

    -------------------------------------------------------------------*/
    // inputs set active
    YP.prototype.inputsActive = function (item, active) {
        // activate input
        if( active ) {
            $(item).parent().addClass('input-filled');
        }

        // deactivate input
        else {
            $(item).parent().removeClass('input-filled');
        }
    };


    /*------------------------------------------------------------------

      Init Hexagon Rating

    -------------------------------------------------------------------*/
    YP.prototype.initHexagonRating = function () {
        if($.fn.hexagonProgress === 'undefined') {
            return;
        }

        $('.youplay-hexagon-rating:not(.youplay-hexagon-rating-ready)').each(function () {
            var max = parseFloat($(this).attr('data-max')) || 10;
            var cur = parseFloat($(this).text()) || 0;
            var size = parseFloat($(this).attr('data-size')) || 120;
            var backColor = $(this).attr('data-back-color') || 'rgba(255,255,255,0.1)';
            var frontColor = $(this).attr('data-front-color') || '#fff';

            $(this).css({
                width: size,
                height: size
            }).hexagonProgress({
                value: cur / max,
                size: size,
                animation: false,
                // 60deg + fix (strange error in hexagon plugin)
                startAngle: (60 + 0.00000001) * Math.PI / 180,
                lineWidth: 2,
                clip: true,
                lineBackFill: { color: backColor },
                lineFrontFill: { color: frontColor }
            });

            $(this).addClass('youplay-hexagon-rating-ready');
        });
    };


    /*------------------------------------------------------------------

      Init Youplay

    -------------------------------------------------------------------*/
    window.youplay = new YP( YP.DEFAULT );

}(jQuery));
