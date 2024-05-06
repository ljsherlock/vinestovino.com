<script>
    (function ($) {
        $( document ).ready(function() {
            fnRadioChecked($('input[name=payment_method]:checked'));
            
            const payment_method = $('input[name=payment_method]');

            payment_method.on('change', function(){
                fnRadioChecked($(this))
            })

            function fnRadioChecked(element){
                $("input[name=chillpay-offsite]").prop("checked", false);
                let closest = element.closest('li.wc_payment_method');
                let fieldset = $(closest).find('fieldset');
                if(fieldset.length > 0){
                    let chillpay_offsite = $(fieldset).find('ul li input[type=radio][name=chillpay-offsite]:not(:disabled)');
                    if(chillpay_offsite.length == 1){
                        $(chillpay_offsite[0]).prop("checked", true)
                    }
                }
            }
        });
	}(jQuery));
</script>