/**
 * AJAX Form
 * Usage: <form class="form">
 */
var forms = {

    ALERT_WARNING: 'warning',
    ALERT_DANGER:  'danger',
    ALERT_INFO:    'info',

    init: function() {
        $('.form').on('click', ':submit', function(e) {
            $(this).addClass('submitted');
        });

        $('.form').each(function() {
            var $form = $(this);

            $(this).ajaxForm({
                delegation: true,
                
                beforeSubmit: function(formData, form, options) {
                    $(form)
                        .find(':submit').prop('disabled', true).end()
                        .find('.submitted').button('loading').end()
                        .find('.alert').remove();

                    forms.clearErrors(form);

                    return true;
                },

                complete: function() {
                    $form.find('.submitted').button('reset');
                    $form.find(':submit').prop('disabled', false).removeClass('submitted');
                },

                error: function() {
                    forms.alert(
                        $form,
                        'Извините, попробуйте позже',
                        forms.ALERT_DANGER,
                        'Критическая ошибка');
                },

                success: function(data, statusText, xhr, $form) {
                    if (data.redirect) {
                        document.location.href = data.redirect;
                    }
                    
                    if (data.reload) {
                        location.reload(true);
                    }

                    if (data.errors) {
                        forms.showErrors($form, data.errors, data.prefix);
                    }
                },
            });
        });
    },
    
    showErrors: function($form, errors, prefix) {
        $.each(errors, function(fieldId, text) {
            forms.showError($form, prefix + fieldId, text);
        });
        
        forms.alert(
            $form,
            'Пожалуйста, исправьте ошибки!',
            forms.ALERT_DANGER,
            'Найдено ошибок: <span class="forms-error-count">' + _.size(errors) + '</span>');
    },
    
    showError: function($form, fieldId, text) {
        var $field = $form.find('#' + fieldId.toLowerCase()).closest('.form-group');
        $field.find('.help-block').text(text);
        $field.addClass('has-error');
    },

    clearErrors: function(form) {
        $(form).find('.has-error .help-block').empty();
        $(form).find('.has-error').removeClass('has-error');
    },
    
    clearError: function(fieldId) {
        var $field = $('#' + fieldId.toLowerCase()).closest('.form-group');
        $field.find('.help-block').empty();
        $field.removeClass('has-error');
    },

    alert: function($form, text, type, description) {
        $form.find('.forms-callout').remove();

        $('<div style="display:none" class="forms-callout" />')
            .addClass('callout animated fadeInUp callout-' + (type ? type : forms.ALERT_DANGER))
            .html('<h4>' + text + '</h4><p>' + description + '</p>')
            .insertBefore($form.find('.form-controls'))
            .fadeIn(100);
    }
};

$(function () {
    forms.init();
});
