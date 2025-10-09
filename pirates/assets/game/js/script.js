var motionNumber = 3;
var phrases = {
    2: '<p>Фигня, не убедительно <span>стреляй еще</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    3: '<p>ОПА! СОВСЕМ РЯДОМ, <span>ДОБИВАЙ ЕГО!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    4: '<p>Он однопалубный <span>найди его!!!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    6: '<p>мимо! он где-то РЯДОМ, <span>стреляй в неГО!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    7: '<p>ОПА! СОВСЕМ РЯДОМ, <span>ДОБИВАЙ ЕГО!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    8: '<p>По теории вероятности <span>осталось чуть чуть</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    10: '<p>ОПА! СОВСЕМ РЯДОМ, <span>ДОБИВАЙ ЕГО!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    11: '<p>Соберись! Последний выстрел, <span>Стреляй!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    12: '<p>Энштейн был прав <span>Осталось немного</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    13: '<p>Энштейн был прав <span>Осталось немного</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    14: '<p>ОПА! СОВСЕМ РЯДОМ, <span>ДОБИВАЙ ЕГО!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
    15: '<p>Последний шанс! нельзя промазать, <span>просто попади!</span>  &nbsp;<i class="arrow-yellow"></i></p>',
};
var phrasesMini = {
    2: 'Он рядом с центром...',
    3: 'Он рядом с центром...',
    4: 'Он рядом с центром...',
    6: 'Он рядом с центром...',
    7: 'Он рядом с центром...',
    8: 'Надвигается туман...',
    10: 'он опять скрылся...',
    11: 'КАЖЕТСЯ ОН СКРАЮ...',
    12: 'Надвигается туман...',
    13: 'Надвигается туман...',
    14: 'он опять скрылся...',
    15: 'КАЖЕТСЯ ОН СКРАЮ...',
};
var currentStep = 1;
var stage = 1;

$(document).ready(function () {

    jQuery('.send-from').on('submit', function (e) {
        e.preventDefault();
        var form = jQuery(this);
        var data = {
            name: form.find('input[name="name"]').val(),
            email: form.find('input[name="email"]').val(),
            phone: form.find('input[name="phone"]').val(),
            note: form.find('input[name="note"]').val(),
            subscribe_id: form.find('input[name="subscribe_id"]').val(),
            template_id: template_id,
            task: 'save_order'
        };

        jQuery.ajax({
            url: 'ajax.php', method: 'get', data: data, async: false, success: function () {
                form.find('input[name="name"]').val('');
                form.find('input[name="email"]').val('');
                form.find('input[name="phone"]').val('');
                form.find('input[name="note"]').val('');
                jQuery('#modal-form').modal('hide');

                currentStep++;

                jQuery('#gameThanks').show();
                jQuery('#gamePrizes').hide();
                jQuery('.root').addClass('secret');

                jQuery('.aside-step').hide();
                jQuery('#step' + currentStep).show();
                yaCounter57516634.reachGoal('Otpravilzayavku');
            }
        });
    });

    jQuery('#field_comp').on('click', '.battleship-cell.clickable', function (e) {
        e.preventDefault();

        jQuery(this).addClass('miss');

        motionNumber--;
        currentStep++;

        jQuery('#battleshipMotionNumber').text(motionNumber + ' ' + plural(['ход', 'хода', 'ходов'], motionNumber));
        jQuery('#dudeBaloon').html(phrases[currentStep]).addClass('big');
        jQuery('.lookout-baloon').html(phrasesMini[currentStep]);
        jQuery('.content-left__text').hide();

        jQuery('.aside-step').hide();
        jQuery('#step' + currentStep).show();

        if (currentStep === 4 || currentStep === 8  || currentStep === 12 || currentStep === 16) {
            stage++;
        }

       /* let percent = 100 - Math.floor(70 / 7 * (currentStep + 3));
        let rwidth = jQuery('.root').width();
        if (rwidth * (100 - percent) / 100 > 1460) {
            percent = (rwidth - 1460) / rwidth * 100;
        }
        jQuery('.smoke').css('left', percent + '%');*/

        jQuery('.root').addClass('smokest');

        if (currentStep === 15) {
            jQuery('#dudeBaloon').addClass('biggest');
        }

        if (stage === 2) {
            jQuery('.game-content').hide();
            jQuery('#gameRepost').show();
            jQuery('.maingame').addClass('repost')
            jQuery('.root').removeClass('smokest');
        }

        if (stage === 4) {
            jQuery('.game-content').hide();
            jQuery('#gameSubscribe').show();
            jQuery('.maingame').addClass('repost')
            jQuery('.root').removeClass('smokest');
        }

        if (stage === 6) {
            jQuery('.game-content').hide();
            jQuery('#gameInstruction').show();
            jQuery('.maingame').addClass('repost')
            jQuery('.root').addClass('instruction')
            jQuery('.root').removeClass('smokest');
        }

        if (stage === 8) {
            jQuery('.game-content').hide();
            jQuery('#gamePrizes').show();
            jQuery('.maingame').addClass('repost')
            jQuery('.root').removeClass('smokest');
        }
    });

    jQuery('#setSubscribe').on('click', function (e) {
        setTimeout(setSubscribe, 2000);
    });

    jQuery('#getInstruction').on('click', function (e) {
        setTimeout(getInstruction, 2000);
    });

    jQuery('.js-more').on('click', function(e){
        e.preventDefault();

        jQuery('.prize').show();
        jQuery(this).hide();
    });

    jQuery('.js-surrender').on('click', function (e) {
        e.preventDefault();

        motionNumber = 0;
        stage = 9;
        currentStep = 16;

        jQuery('#battleshipMotionNumber').text(motionNumber + ' ' + plural(['ход', 'хода', 'ходов'], motionNumber));
        jQuery('.game-content, #gameRepost, #gameSubscribe, #gameInstruction').hide();
        jQuery('#gamePrizes').show();
        jQuery('.maingame').addClass('repost');
        jQuery('.instruction').removeClass('instruction');
        jQuery('.root').removeClass('smokest');

        jQuery('.aside-step').hide();
        jQuery('#step' + currentStep).show();
    });

    jQuery('.btn--play').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalIgrat');
    });

    jQuery('.header-phone__num').on('click', function(e){
        yaCounter57516634.reachGoal('Nazhalnanomertelefona');
    });

    jQuery('.battleship-bottom .js-surrender').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalYAsdayussrazu');
    });

    jQuery('.game-repost .js-surrender').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalSdatsyaposleboya');
    });

    jQuery('.info-right .js-modal').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalNaznachitvstrechu');
    });

    jQuery('.js-tarrif-1').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalHochudarom');
    });

    jQuery('.js-tarrif-2').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalHochusumom');
    });

    jQuery('.js-tarrif-3').on('click', function(e){
        yaCounter57516634.reachGoal('NazhalHochukaksebe');
    });
});

window.onload = function () {
    (function () {
        'use strict';


    })();

    if (jQuery('.viking-ship').length > 0) {
        jQuery('.viking-ship').animate({
            left: '104px',
            top: '71px'
        }, 2000).animate({
            left: '160px',
            top: '250px'
        }, 2000).animate({
            left: '300px',
            top: '250px',
            opacity: 0.5
        }, 2000).animate({
            left: '260px',
            top: '50px',
            opacity: 0
        }, 2000, function (e) {
            jQuery(this).hide();
            jQuery('.dude-baloon').css('display', 'flex');
            jQuery('.battleship-cell').animate({'opacity': 1}, 2000).addClass('clickable');
        });
        jQuery('.smoke').delay(5000).css('right', '-20px');
    }
};

function plural(forms, n) {
    let idx;

    if (n % 10 === 1 && n % 100 !== 11) {
        idx = 0; // many
    } else if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) {
        idx = 1; // few
    } else {
        idx = 2; // one
    }
    return forms[idx] || '';
}


/*var VK = {};
VK.Share = {};
VK.Share.count = function(index, count) {
    console.log(count);
}*/

Share = {
    vkontakte: function(purl, ptitle, pimg, text) {
        url  = 'http://vkontakte.ru/share.php?';
        url += 'url='          + encodeURIComponent(purl);
        url += '&title='       + encodeURIComponent(ptitle);
        //url += '&description=' + encodeURIComponent(text);
        url += '&image='       + encodeURIComponent(pimg);
        url += '&noparse=true';

        yaCounter57516634.reachGoal('NazhalnarepostVKshag4');
        Share.popup(url);

        setTimeout(setRepost, 4000);

        return false;
    },
    odnoklassniki: function(purl, text) {
        url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
        url += '&st.comments=' + encodeURIComponent(text);
        url += '&st._surl='    + encodeURIComponent(purl);
        Share.popup(url);
    },
    facebook: function(purl, ptitle, pimg, text) {
        url  = 'http://www.facebook.com/sharer.php?s=100';
        url += '&p[title]='     + encodeURIComponent(ptitle);
        //url += '&p[summary]='   + encodeURIComponent(text);
        url += '&p[url]='       + encodeURIComponent(purl);
        url += '&p[images][0]=' + encodeURIComponent(pimg);
        yaCounter57516634.reachGoal('NazhalnarepostFBshag4');
        Share.popup(url);

        setTimeout(setRepost, 4000);

        return false;
    },
    twitter: function(purl, ptitle) {
        url  = 'http://twitter.com/share?';
        url += 'text='      + encodeURIComponent(ptitle);
        url += '&url='      + encodeURIComponent(purl);
        url += '&counturl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    mailru: function(purl, ptitle, pimg, text) {
        url  = 'http://connect.mail.ru/share?';
        url += 'url='          + encodeURIComponent(purl);
        url += '&title='       + encodeURIComponent(ptitle);
        url += '&description=' + encodeURIComponent(text);
        url += '&imageurl='    + encodeURIComponent(pimg);
        Share.popup(url)
    },

    popup: function(url) {
        window.open(url,'','toolbar=0,status=0,width=626,height=436');
    }
};

function setRepost() {
    motionNumber = 3;
    stage++;
    currentStep++;

    jQuery('#battleshipMotionNumber').text(motionNumber + ' ' + plural(['ход', 'хода', 'ходов'], motionNumber));
    jQuery('.game-content').show();
    jQuery('#gameRepost').hide();
    jQuery('.maingame').removeClass('repost');
    jQuery('.root').addClass('smokest');

    jQuery('.aside-step').hide();
    jQuery('#step' + currentStep).show();
}

function setSubscribe() {
    motionNumber = 3;
    stage++;
    currentStep++;

    jQuery('#battleshipMotionNumber').text(motionNumber + ' ' + plural(['ход', 'хода', 'ходов'], motionNumber));
    jQuery('.game-content').show();
    jQuery('#gameSubscribe').hide();
    jQuery('.maingame').removeClass('repost')
    jQuery('.root').addClass('smokest');

    jQuery('.aside-step').hide();
    jQuery('#step' + currentStep).show();
    yaCounter57516634.reachGoal('Nazhalnapodpisatsyashag8');
}

function getInstruction() {
    motionNumber = 3;
    stage++;
    currentStep++;

    jQuery('#battleshipMotionNumber').text(motionNumber + ' ' + plural(['ход', 'хода', 'ходов'], motionNumber));
    jQuery('.game-content').show();
    jQuery('#gameInstruction').hide();
    jQuery('.maingame').removeClass('repost');
    jQuery('.root').removeClass('instruction');
    jQuery('.root').addClass('smokest');

    jQuery('.aside-step').hide();
    jQuery('#step' + currentStep).show();
    yaCounter57516634.reachGoal('Nazhalpoluchitrukovodstvoshag12');
}

// var index = "1";
// var url = "http://pirats.studio/?tmpl=game";
// jQuery("body").append("<script src='https://vk.com/share.php?act=count&index=" + index + "&url=" + url + "'></script>");

function subscribe() {
    VK.Auth.login(function(response) {
        if (response.session) {
            //showAuthData(response.session);
            console.log(response);

            var uid = response.session.user.id;

            VK.Api.call('groups.getMembers', {group_id: 190654119, v: '5.103'}, function(r) {
                console.log(r);

                console.log(uid);
                console.log(r.response.items);
                console.log(r.response.items.indexOf(uid));
                if (r.response.items.indexOf(uid) != -1) {

                }
            });



            //VK.Groups.join();

            if (response.settings) {
                //console.log(response.settings);
                // Выбранные настройки доступа пользователя если они были запрошены
            }
        } else {
            // Пользователь нажал кнопку Отмена в окне авторизации
        }
    }, VK.access.GROUPS);
}



