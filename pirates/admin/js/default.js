jQuery(document).ready(function() {

    var editorParams = {
        height: 200
    }

    jQuery('.editor').summernote(editorParams);

    jQuery('.js-newUTM-Add').on('click', function() {
        jQuery('.js-newUTM-Fields').find('.editor').destroy();
        var fields = jQuery('.js-newUTM-Fields').clone().removeClass('js-newUTM-Fields');
        jQuery('.js-newUTM-Fields').find('.editor').summernote(editorParams);
        fields.find('textarea').val('');
        fields.find('[type="radio"]').removeAttr('checked');
        jQuery(this).parents('form').find('fieldset:last').after(fields);

        fields.find('.editor').summernote(editorParams);
    });

    jQuery('.modal').on('click', '.nav-tabs > li > a', function(e) {
        e.preventDefault()
        var li = jQuery(this).parent();
        var hash = jQuery(this).prop('hash').substr(1);

        jQuery(this).parent().addClass('active');
        jQuery(this).parents('.nav-tabs').children('li').removeClass('active');

        var tab_content = jQuery(this).parents('.nav-tabs').parent().find('.tab-content');

        tab_content.children('div').removeClass('active');
        tab_content.find('.' + hash).addClass('active');
    });

    jQuery('.js-availableTraffic-change~').on('change', function() {
        var value = parseInt(jQuery(this).val());
        if (value > 0) {
            var percentBlock = jQuery(this).parents('.form-group').find('.js-availableTraffic-value');
            var commonPercent = parseInt(percentBlock.text());
            var AVPrecent = commonPercent - value;
            percentBlock.text(AVPrecent + '%');
        }
    });

    jQuery('.js-ABTest-remvoe').on('click', function() {
        var a = jQuery(this);
        var id = a.data().id;
        jQuery.ajax({
            url: '?task=removeABTest&id=' + id,
            method: 'POST',
            success: function(response) {
                a.parents('fieldset').remove();
            }
        });
    });

    jQuery('.js-updateUTMLink-changeblock textarea').on('change', function() {
        var textareaVal = jQuery(this).val();
        if (textareaVal) {
            var a = jQuery('.js-updateUTMLink-preview a');
            a.attr('href', updateURLParameter(a.attr('href'), jQuery(this).data().name, textareaVal));
        }
    });

    jQuery('.js-ToolTip').tooltip();
	
	jQuery('table.cat td.del a').on('click', function(){
		if (!confirm('Подтвердите удаление.')){
			return false;
		}
	});
	
	jQuery('.module ul .actions a.delete').on('click', function(){
		if (!confirm('Подтвердите удаление.')){
			return false;
		}
	});	
});

function updateURLParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}