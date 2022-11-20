(function ($) {
    'use strict';
    $(document).ready(function () {
        const notyf = new Notyf({
            dismissible: true,
            duration: 4000,
            position: {
                x: "center",
                y: "bottom",
            }
        });
        $('#application-filter a.delete-submission').on('click', function (e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete this application ?')) {
                return;
            }

            const id = $(this).data('id');
            const nonce = $(this).data('nonce');
            const application = $(this).closest('tr');
            alert(window.wpja.url)
            $.ajax({
                url: window.wpja.url,
                type: 'post',
                data: {id: id, nonce: nonce, action: 'remove_application'},
                success: function (result) {
                    if (result.success) {
                        application.fadeOut(function () {
                            location.reload();
                        });
                    } else {
                        notyf.error("Server error");
                    }

                }
            });

        });
    });
})(jQuery);