export default {
    alert: function(type, msg) {
        $('#alert').addClass('alert-' + type).text(msg).fadeTo(1000, 1).slideUp(2000, function() {
            $("#alert").slideUp(500);
        });
    }
}
