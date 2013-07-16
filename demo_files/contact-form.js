/**
* for our demo HTML5 contact form via FormTemplateProcessorMailer
* @author Codename: Steeve Knight | cookbook.DharmiWeb.net
* 
*/
(function($){
        $(function(){
                var bm = $('#bodyMask');
                if (! bm.length) {
                    $(document.body).append('<div id="bodyMask" style="position: absolute; display: none;"></div>');
                    bm = $('#bodyMask');
                }
                
                var co = $('#contactOverlay');
                if (! co.length) {
                    $(document.body).append('<div id="contactOverlay" style="position: absolute; display: none;"></div>');
                    co = $('#contactOverlay');
                }
                
                co.on('click', '.closer', function() {
                        co.hide(500, function() {
                                co.css({top: 0, left: 0, display: 'none'});
                        });
                        bm.hide();
                });
                
                bm.on('click', function() {
                        co.hide(500, function() {
                                co.css({top: 0, left: 0, display: 'none'});
                        });
                        $(this).hide();
                });
                $(document.body).on('click', 'a.contact.overlay', function(e) {
                        e.preventDefault();
                        bm.show();
                        co.load($(this).attr('href'), function(responseText, textStatus, XMLHttpRequest) {
                                window.scrollTo(0, 0);
                                co.position({my: 'center top', at: 'center top', of: window});
                                co.show({duration: 1000});
                                return;
                        });
                });
                $(document.body).on('submit', 'form.contact-form', function(e) {
                        e.preventDefault();
                        var form = $(this);
                        $.post(form.attr('action'), form.serialize(), function(response, textStatus, xhr) {
                            if (textStatus === 'success') {
                                if (typeof response == 'object' && response.mailerMsg) {
                                    $('.msgBloc', form).html(response.mailerMsg);
                                    // currently strictly the success ie no errors, so
                                    $('button[type=submit]').html('<span class="icon">Ã</span> Done')
                                    .attr('disabled', 'disabled').addClass('disabled');
                                    return;
                                } else {
                                    form.replaceWith(response);
                                }
                            }
                        });
                });
        });
})(jQuery);