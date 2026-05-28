<script>
    window.onload = function() {
        let script = document.createElement('script');

        script.src = '<?php echo e($clientEndPoint); ?>?render=<?php echo e($siteKey); ?>';

        script.onload = function() {
            grecaptcha.enterprise.ready(function() {
                const forms = document.querySelectorAll('form');
                
                forms.forEach(function(form) {
                    const tokenField = form.querySelector('#recaptcha-token');
                    
                    if (tokenField) {
                        form.addEventListener('submit', function(e) {
                            if (tokenField.value) {
                                return true;
                            }
                            
                            e.preventDefault();
                            
                            grecaptcha.enterprise.execute('<?php echo e($siteKey); ?>', { action: 'submit' })
                                .then(function(token) {
                                    tokenField.value = token;
                                    form.submit();
                                });
                        });
                        
                        grecaptcha.enterprise.execute('<?php echo e($siteKey); ?>', { action: 'submit' })
                            .then(function(token) {
                                tokenField.value = token;
                            });
                    }
                });
            });
        };

        document.body.appendChild(script);
    };
</script>
<?php /**PATH D:\aven\packages\Webkul\Customer\src/resources/views/captcha/scripts.blade.php ENDPATH**/ ?>