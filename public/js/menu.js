$(document).ready(function () {

    var cnt;

    $('.changeMenuItem').click(function () {

        cnt = $('#changeOrder').val();
        redraw(cnt);
    });

    $('.order_menu').click(function () {

        var menuId = $(this).attr('value');
        if (cnt) {

            $.ajax({
                type: "POST",
                url: "/menu_crud/create",
                cache: false,
                data: {"menu": menuId, "order": cnt},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (html) {
                    redraw(cnt);
                }
            });
        }
    });
/*
    setInterval(function () {
        redraw(cnt);
    }, 5000);
*/
});

/**
 * build menu list for current order
 *
 * @param json
 * @returns {string}
 */
function buildMenuList(json) {

    var obj = JSON.parse(json);

    console.log(obj);
    str = '';

    for (var i = 0; i < obj.length; i++) {
        str += '<li>' + obj[i].name + ' --- ' + obj[i].price.toFixed(2) + '<button class="del_menu" val="' + obj[i].id + '">Delete</button></li>';
    }

    return str;
}

/**
 * redraw right side current menu
 * @param cnt
 */
function redraw(cnt) {

    $.ajax({
        type: "POST",
        url: "/menu_crud/read/" + cnt,
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (html) {
            var ul = buildMenuList(html);
            $('#curMenu').html(ul);

            $('.del_menu').click(function () {
                delMenu($(this).attr('val'), cnt)
            });
        }
    });
}

/**
 * delete menu from order
 *
 * @param menuId
 * @param cnt
 */
function delMenu(menuId, cnt) {
    $.ajax({
        type: "POST",
        url: "/menu_crud/delete/" + menuId,
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (html) {
            redraw(cnt);
        }
    });
}

