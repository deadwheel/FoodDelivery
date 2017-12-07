        jQuery(document).ready(function($) {
            $('select').on('change', function() {
                var x = $(this).parents("tr");
                x.addClass("row-selected");
                x.find("input").prop('checked', true);
            });


            $('.checkx').change(function() {
                var x = $(this).parents("tr");

                if(this.checked) {
                    x.addClass("row-selected");
                }
                else {
                    x.removeClass("row-selected");
                }
            });


        });