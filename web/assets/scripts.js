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

        let loading = false;
        let applicantForm = $("#applicant-form");
        applicantForm.on("submit", function (e) {
            e.preventDefault();
            if (loading) {
                return;
            }
            loading = true;
            let not_dirty = applicantForm.serializeArray().filter(i => i.value === '' || i.length > 0);
            if (not_dirty.length !== 0) {
                let inputs = not_dirty.map(i => i.name.replace('_', ' ').toUpperCase());
                notyf.error("Please fill the following " + inputs.join(", "));
                loading = false;
            } else {
                $.ajax({
                    url: wpja.ajaxurl,
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: new FormData(this),
                    success: function (response) {
                        loading = false;
                        if (response.success === true) {
                            notyf.success(response.data);
                            applicantForm.trigger("reset");
                        } else {
                            notyf.error(response.data);
                            loading = false;
                        }
                    },
                });
            }


        });
    });


})(jQuery);
