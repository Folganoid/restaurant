$(document).ready(function () {


    $('#changeOrder').change(function() {

        var cnt = $('#changeOrder').val();

        $.ajax({
            type: "POST",
            url: "/menuapi/" + cnt,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (html) {
                var ul = buildMenuList(html);
                $('#curMenu').html(ul);
            }
        });
    });
});

/**
 * build menu list for current order
 *
 * @param json
 * @returns {string}
 */
function buildMenuList(json) {

    var obj = JSON.parse(json);
    str='';

    for(var i=0 ; i < obj.length ; i++) {
        str += '<li>' + obj[i].name + ' --- ' + obj[i].price.toFixed(2) + '</li>';
    }

    return str;
}