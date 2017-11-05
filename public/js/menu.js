$(document).ready(function () {

    var cnt;

    $('.changeMenuItem').click(function () {
        cnt = $('#changeOrder').val();
        redraw(cnt);
        ownGroupTrigger();
    });

    $('.chooseUser').click(function () {
       var user = $(this).val();
        $.ajax({
            type: "POST",
            url: "/menu_crud/useradd",
            cache: false,
            data: {"user": user, "order": cnt},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (html) {

                redraw(cnt);
            }
        });
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

    /**
     * first choice active order
     */
    try {
        cnt = $('#changeOrder').find('option')[0].value;
        redraw(cnt);
        ownGroupTrigger();
    }
    catch (e) {}

    setInterval(function () {
        redraw(cnt);
    }, 5000);
});

/**
 * build menu list for current order
 *
 * @param json
 * @returns {string}
 */
function buildMenuList(json) {

    var obj = JSON.parse(json);
    var str = '';
    var strUsers = '';

    for (var i = 0; i < obj[0].length; i++) {
        str += '<li>' + obj[0][i].name +
            ' --- ' +
            obj[0][i].price.toFixed(2) + '<button class="del_menu" val="' +
            obj[0][i].id + '">Delete</button></li>';
    }

    for (var i = 0; i < obj[1].length; i++) {
        strUsers += ' | ' + obj[1][i].login + '<button class="del_user" val="' +
            obj[1][i].id + '">X</button>';
    }

    return [str, strUsers];
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

            $('#curMenu').html(buildMenuList(html)[0]);

            $('#orderGroup').html(buildMenuList(html)[1]);

            $('.del_menu').click(function () {
                delMenu($(this).attr('val'), cnt)
            });

            $('.del_user').click(function () {
                delUser($(this).attr('val'), cnt)
            });
            ownGroupTrigger();
        }
    });

    $('#menuFormId').attr('value', cnt);

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

/**
 * delete user from group
 * @param userId
 * @param cnt
 */
function delUser(userId, cnt) {
    $.ajax({
        type: "POST",
        url: "/menu_crud/userdel",
        data: {"user": userId, "order": cnt},
        dataType: 'json',
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (html) {
            redraw(cnt);
        }
    });
}


/**
 * owner group trigger visible/invisible
 */
function ownGroupTrigger() {

    $( "#changeOrder option:selected" ).each(function() {
        if ($( this ).attr('class') == 'changeMenuItem for') {
            $('.menuFormSubmit').css('display', 'none');
            $('.adduser').css('display', 'none');
            $('.del_user').css('display', 'none');
            $('.exitgroup').css('display', 'inline');
        }
        else {
            $('.menuFormSubmit').css('display', 'inline');
            $('.adduser').css('display', 'inline');
            $('.del_user').css('display', 'inline');
            $('.exitgroup').css('display', 'none');
        }
    });
}

