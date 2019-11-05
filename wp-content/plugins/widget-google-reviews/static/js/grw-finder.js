/*
 * Init Google Places finder for Widget or Shortcode
 */
function grw_init(data) {

    var el = data.el;
    if (!el) return;

    var searchBtn = el.querySelector('.grw-search-btn');
    WPacFastjs.on(searchBtn, 'click', function() {
        var queryEl = el.querySelector('.grw-place-search');

        if (!queryEl.value) {
            queryEl.focus();
            return false;
        }

        searchBtnText = searchBtn.innerHTML;
        searchBtn.disabled = true;
        searchBtn.innerHTML = 'Please wait...';

        if (/^ChIJ.*$/.test(queryEl.value)) {
            jQuery.get(grw_request_url('reviews'), {
                placeid: queryEl.value,
                grw_wpnonce: jQuery('#grw_nonce').val()
            }, function(place) {
                searchBtn.disabled = false;
                searchBtn.innerHTML = searchBtnText;
                grw_addPlaces(el, [place], data.cb);
            }, 'json');
        } else {
            grw_textsearch(el, queryEl.value, data.cb, function() {
                searchBtn.disabled = false;
                searchBtn.innerHTML = searchBtnText;
            });
        }
        return false;
    });

    var connectBtn = el.querySelector('.grw-connect-btn');
    WPacFastjs.on(connectBtn, 'click', function() {

        var placeIdEl = el.querySelector('.grw-place-id'),
            placeId = placeIdEl.value;
        if (!placeId) {
            placeIdEl.focus();
            return false;
        }

        var errorEl = el.querySelector('.grw-error');
        if (/^ChIJ.*$/.test(placeId)) {
            errorEl.innerHTML = '';
        } else {
            errorEl.innerHTML = 'Place ID is incorrect, it should like ChIJ...';
            return false;
        }

        var langEl = el.querySelector('.grw-lang'),
            optionEl = langEl.options[langEl.selectedIndex],
            lang = optionEl ? optionEl.value : '';

        connectBtn.innerHTML = 'Please wait...';
        connectBtn.disabled = true;

        jQuery.post(grw_request_url('save'), {
            placeid     : placeId,
            lang        : lang,
            grw_wpnonce : jQuery('#grw_nonce').val()
        }, function(res) {

            console.log('grw_debug:', res);

            connectBtn.innerHTML = 'Connect Google';
            connectBtn.disabled = false;

            if (res.status == 'success') {

                errorEl.innerHTML = '';

                var nameEl    = el.querySelector('.grw-google-place-name'),
                    placeIdEl = el.querySelector('.grw-google-place-id'),
                    langEl    = el.querySelector('.grw-place-lang'),
                    photoEl   = el.querySelector('.grw-place-photo');

                nameEl.value    = res.result.name;
                placeIdEl.value = res.result.place_id;
                langEl.value    = lang;
                photoEl.value   = res.result.business_photo || res.result.icon;

                var img = el.querySelector('.grw-place-photo-img');
                img.src = res.result.business_photo || res.result.icon;
                img.style.display = '';

                var controlEl = el.parentNode.parentNode.querySelector('.widget-control-actions');
                if (controlEl) {
                    grw_show_tooltip(controlEl, 'Please don\'t forget to <b>Save</b> the widget.');
                }

                jQuery(nameEl).change();

            } else {

                errorEl.innerHTML = '<b>Google error</b>: ' + res.result.error_message;
                if (res.result.status == 'OVER_QUERY_LIMIT') {
                    errorEl.innerHTML += '<br><br>More recently, Google has limited the API to 1 request per day for new users, try to create new <a href="https://developers.google.com/places/web-service/get-api-key#get_an_api_key" target="_blank">Google API key</a>, save in the setting and Connect Google again.';
                }

            }

        }, 'json');
        return false;
    });

    grw_jquery_init(el, data.cb);
}

/*
 * Init Google Places moderation
 */
function grw_mod_init(data) {

    var el = document.querySelector('#' + data.widgetId);
    if (!el) return;

    jQuery.get(grw_request_url('places'), {
        grw_wpnonce: jQuery('#grw_nonce').val()
    }, function(res) {

        var placesEl = el.querySelector('.wp-places');
        if (res.places.length < 1) {
            placesEl.innerHTML = 'There are no Places yet';
            return;
        } else {
            placesEl.innerHTML = '';
        }

        WPacFastjs.each(res.places, function(place) {
            var placeEl = document.createElement('div');
            placeEl.className = 'grw-place media';
            placeEl.innerHTML = grw_renderPlace(place);
            placeEl.title = place.formatted_address;
            placesEl.appendChild(placeEl);

            var stars = placeEl.querySelector('.grw-gstars');
            stars.innerHTML = WPacStars.rating_render(place.rating, 14, 'e7711b');

            WPacFastjs.on(placeEl, 'click', function() {
                var activeEl = placeEl.parentNode.querySelector('.grw-active');
                WPacFastjs.remcl(activeEl, 'grw-active');
                WPacFastjs.addcl(placeEl, 'grw-active');

                jQuery.get(grw_request_url('place_reviews'), {
                    google_place_id: place.id,
                    grw_wpnonce: jQuery('#grw_nonce').val()
                }, function(res) {

                    var reviewsEl = el.querySelector('.wp-reviews');
                    if (res.reviews.length < 1) {
                        reviewsEl.innerHTML = 'There are no reviews yet for selected Place';
                        return;
                    } else {
                        reviewsEl.innerHTML = '';
                    }

                    el.querySelector('.grw-google-place-name').value = place.name;
                    el.querySelector('.grw-google-place-id').value = place.place_id;

                    WPacFastjs.show2(el.querySelector('#mod-reviews'));
                    WPacFastjs.show2(el.querySelector('#mod-shortcode-builder'));

                    data.cb && data.cb();

                    WPacFastjs.each(res.reviews, function(review) {
                        var reviewEl = document.createElement('div');
                        reviewEl.className = 'grw-place media';
                        reviewEl.innerHTML = grw_renderReview(review, true);
                        reviewsEl.appendChild(reviewEl);

                        var stars = reviewEl.querySelector('.grw-gstars');
                        stars.innerHTML = WPacStars.rating_render(review.rating, 14, 'e7711b');

                        WPacFastjs.on2(reviewEl, '.wp-google-delete', 'click', function() {
                            if (confirm('Are you sure to delete this review?')) {
                                jQuery.post(grw_request_url('delete_review'), {
                                    google_review_id: review.id,
                                    grw_wpnonce: jQuery('#grw_nonce').val()
                                }, function(res) {
                                    if (res.status == 'success') {
                                        WPacFastjs.rm(reviewEl);
                                    }
                                }, 'json');
                            }
                            return false;
                        });
                    });

                }, 'json');

                return false;
            });
        });
    }, 'json');

    grw_jquery_init(el, data.cb);
}

/*
 * Init photo upload link and options toggles
 */
function grw_jquery_init(el, cb) {

    jQuery(document).ready(function($) {

        var file_frame;
        $('.grw-place-photo-btn', el).on('click', function(e) {
            e.preventDefault();
            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: $(this).data('uploader_title'),
                button: {text: $(this).data('uploader_button_text')},
                multiple: false
            });

            file_frame.on('select', function() {
                var place_photo_hidden = $('.grw-place-photo', el),
                    place_photo_img = $('.grw-place-photo-img', el);
                attachment = file_frame.state().get('selection').first().toJSON();
                place_photo_hidden.val(attachment.url);
                place_photo_img.attr('src', attachment.url);
                place_photo_img.show();

                // To make 'Save' button enable in the widget
                jQuery(place_photo_hidden).change();

                cb && cb();
            });
            file_frame.open();
            return false;
        });

        $('.rplg-toggle', el).unbind('click').click(function () {
            $(this).toggleClass('toggled');
            $(this).next().slideToggle();
            return false;
        });
    });
}

function grw_textsearch(el, query, dataCb, cb) {
    jQuery.get(grw_request_url('search'), {
        query: query,
        grw_wpnonce: jQuery('#grw_nonce').val()
    }, function(places) {
        cb && cb();
        grw_addPlaces(el, places, dataCb);
    }, 'json');
}

function grw_addPlaces(el, places, cb) {
    var placesEl = el.querySelector('.grw-places');
    if (places && places.length) {
        placesEl.innerHTML = '';
        WPacFastjs.each(places, function(place) {
            var placeEl = document.createElement('div');
            placeEl.className = 'grw-place media';
            placeEl.innerHTML = grw_renderPlace(place);
            placeEl.title = place.formatted_address;
            placesEl.appendChild(placeEl);

            var stars = placeEl.querySelector('.grw-gstars');
            stars.innerHTML = WPacStars.rating_render(place.rating, 14, 'e7711b');

            grw_selectPlace(el, place.place_id, placeEl, cb);

            if (places.length == 1) {
                placeEl.className = 'grw-place grw-active media';
                jQuery.get(grw_request_url('reviews'), {
                    placeid: place.place_id,
                    grw_wpnonce: jQuery('#grw_nonce').val()
                }, function(place) {
                    grw_addReviews(el, place, cb);
                }, 'json');
            } else {
                el.querySelector('.grw-reviews').innerHTML = '';
            }
        });
    } else {
        placesEl.innerHTML = '' +
            '<div class="wp-place-info">' +
                'Google Place not found.<br><br>' +
                'Please check that this place can be found in ' +
                '<a href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank">Google PlaceID Finder</a>, ' +
                'if so, then copy <b>Place ID</b> to a search field and try to find again.' +
            '</div>';
    }
}

function grw_selectPlace(el, place_id, placeEl, cb) {
    WPacFastjs.on(placeEl, 'click', function() {
        var activeEl = placeEl.parentNode.querySelector('.grw-active');
        WPacFastjs.remcl(activeEl, 'grw-active');
        WPacFastjs.addcl(placeEl, 'grw-active');

        jQuery.get(grw_request_url('reviews'), {
            placeid: place_id,
            grw_wpnonce: jQuery('#grw_nonce').val()
        }, function(place) {
            grw_addReviews(el, place, cb);
        }, 'json');

        return false;
    });
}

function grw_addReviews(el, place, cb) {
    var reviewsEl = el.querySelector('.grw-reviews');
    if (place && place.reviews && place.reviews.length) {
        reviewsEl.innerHTML = '';
        for (var i = 0; i < place.reviews.length; i++) {
            var reviewEl = document.createElement('div');
            reviewEl.className = 'grw-place media';
            reviewEl.innerHTML = grw_renderReview(place.reviews[i]);
            reviewsEl.appendChild(reviewEl);

            var stars = reviewEl.querySelector('.grw-gstars');
            stars.innerHTML = WPacStars.rating_render(place.reviews[i].rating, 14, 'e7711b');
        }
        WPacFastjs.show2(el.querySelector('.grw-five-reviews-note'));
        grw_saveReviews(el, place, cb);
    } else {
        reviewsEl.innerHTML = 'There are no reviews yet.';
    }
}

function grw_saveReviews(el, place, cb) {

    var saveBtnContainer = el.querySelector('.grw-save-reviews-container');
    saveBtnContainer.innerHTML = '';

    var saveBtn = document.createElement('button');
    saveBtn.innerHTML = 'Save Place and Reviews';
    saveBtn.className = 'grw-save-reviews btn btn-primary btn-block';
    saveBtnContainer.appendChild(saveBtn);

    var placeTooltip = grw_show_tooltip(saveBtnContainer, 'Please click by \'Save Place and Reviews\' button.');

    WPacFastjs.on(saveBtn, 'click', function() {
        saveBtn.disabled = true;
        jQuery.post(grw_request_url('save'), {
            placeid: place.place_id,
            grw_wpnonce: jQuery('#grw_nonce').val()
        }, function(res) {
            saveBtn.disabled = false;
            WPacFastjs.rm(placeTooltip);

            el.querySelector('.grw-google-place-name').value = place.name;
            el.querySelector('.grw-google-place-id').value = place.place_id;

            var controlEl = el.parentNode.parentNode.querySelector('.widget-control-actions');
            if (controlEl) {
                grw_show_tooltip(controlEl, 'Please don\'t forget to <b>Save</b> the widget.');
            }

            cb && cb(el, place);
        }, 'json');
        return false;
    });
}

function grw_renderPlace(place) {
    return '' +
        '<div class="media-left">' +
            '<img class="media-object" src="' + (place.business_photo || place.icon) + '" alt="' + place.name + '" style="width:32px;height:32px;">' +
        '</div>' +
        '<div class="media-body">' +
            '<h5 class="media-heading">' + place.name + '</h5>' +
            '<div>' +
                '<span class="grw-grating">' + place.rating + '</span>' +
                '<span class="grw-gstars"></span>' +
            '</div>' +
            '<small class="text-muted">' + (place.formatted_address || place.address) + '</small>' +
        '</div>';
}

function grw_renderReview(review, withDeleteBtn) {
    return '' +
        '<div class="media-left">' +
            '<img class="media-object" src="' + review.profile_photo_url + '" alt="' + review.author_name + '" ' +
            'onerror="if(this.src!=\'' + grwVars.GOOGLE_AVATAR + '\')this.src=\'' + grwVars.GOOGLE_AVATAR + '\';">' +
        '</div>' +
        '<div class="media-body">' +
            '<div class="media-heading">' +
                '<a href="' + review.author_url + '" target="_blank">' + review.author_name + '</a>' +
            '</div>' +
            '<div class="grw-gtime">' + WPacTime.getTime(parseInt(review.time) * 1000, 'en', 'ago') + '</div>' +
            '<div class="grw-gtext">' +
                '<span class="grw-gstars"></span> ' + grw_small_text(review.text) +
            '</div>' +
            (withDeleteBtn ?
            '<a class="wp-google-delete" href="#">' +
                '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="14" height="14" viewBox="0 0 1792 1792">' +
                    '<path d="M704 1376v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm-544-992h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z" fill="#666"></path>' +
                '</svg> ' +
                'Delete' +
            '</a>' : '' ) +
        '</div>';
}

function grw_show_tooltip(el, msg) {
    var tooltip = WPacFastjs.create('div', 'grw-tooltip');
    tooltip.innerHTML = '<div class="grw-corn1"></div>' +
                        '<div class="grw-corn2"></div>' +
                        '<div class="grw-close">×</div>' +
                        '<div class="grw-text">' + msg + '</div>';
    el.appendChild(tooltip);
    setTimeout(function() {
        WPacFastjs.addcl(tooltip, 'grw-tooltip-visible');
    }, 100);
    WPacFastjs.on2(tooltip, '.grw-close', 'click', function() {
        WPacFastjs.rm(tooltip);
    });
    return tooltip;
}

function grw_small_text(text) {
    var size = 100, t = text, h = !1;
    if (text && text.length > size) {
        var idx = text.lastIndexOf(' ', size);
        idx = idx > 0 ? idx : size;
        if (idx > 0) {
            t = text.substring(0, idx);
            h = text.substring(idx, text.length);
        }
    }
    var params = {t: t, h: h};
    return (doT.template(
        '{{!it.t}} ' +
        '{{?it.h}}' +
            '<span class="wp-more" style="display:none">{{!it.h}}</span>' +
            '<span class="wp-more-toggle" ' +
                'onclick="this.previousSibling.style=\'\';this.textContent=\'\';" ' +
                'style="color:#136aaf;cursor:pointer;text-decoration:underline">read more</span>' +
        '{{?}}'))(params);
}

function grw_request_url(action) {
    return grwVars.handlerUrl + '&cf_action=' + grwVars.actionPrefix + '_' + action + '&v=' + new Date().getTime();
}