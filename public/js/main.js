(function() {
    $('.form-delete').click(function($) {
        if( confirm('Do you want delete this item?') ) {
            return true;
        }
        return false;
    });
}(jQuery));