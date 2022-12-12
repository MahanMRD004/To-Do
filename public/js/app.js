function setDate() {
    document.getElementById('date').valueAsDate = new Date();
}

function setSelected() {
    var path = window.location.href;
    var id = path.split('list/')[1];
    $('.selectOption').removeAttr('seleted')
    $('.selectOption[value=' + id + ']').attr('selected', 'true')
}

function checkUrl() {
    var path = window.location.href;
    $('.navLink').removeClass('active');
    $('.navLink').each(function() {
        if (this.href === path) {
            $(this).addClass('active');
        }
    });
}

function setImage(id) {
    $('.backgruond').removeClass('active');
    $('.backgruond').each(function () {
        if (this.id == id) {
            $(this).addClass('active')
        }
    });
}

function openModal(e) {
    $('.modalWrapper').addClass('active')
}

function closeModal() {
    $('.modalWrapper').removeClass('active')
}

function toggleSidebar() {
    $('.sidebar').toggleClass('open')
    $('main header .wrapper .btn').toggleClass('open')
}

$('.theme').click(function () {
    $(this).siblings().removeClass('selected')
    $(this).addClass('selected')
})

window.onload = checkUrl(),setDate()

//Change Content

$('.navLink').click(function(e){
    e.preventDefault()
    var url = $(this).attr('href')
    var theme = $(this).attr('theme')
    history.pushState(null,null,url)
    $('.listTitle').load(document.URL + ' h4')
    $('.content').load(document.URL + ' .todoList')
    checkUrl()
    setSelected()
    setImage(theme)
});

// Add List Without Reload

$("#listForm").on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize();
    $.ajax({
        type: "POST",
        url: route('addList'),
        data: dataString,
        success: function () {
            $('#listForm input').val("");
            $('.userListsWrapper').load(document.URL + ' .userLists')
            $('.selectWrapper').load(document.URL + ' #listSelect')
        },
        error: function () {
            //
        },
    });
});

// Add & Remove Task Without Reload

$("#taskForm").on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize();
    $.ajax({
        type: "POST",
        url: route('addTask'),
        data: dataString,
        success: function () {
            $('#taskForm input[type="text"]').val("");
            $('.content').load(document.URL + ' .todoList')
            $('.selectWrapper').load(document.URL + ' #listSelect')
            $('.userListsWrapper').load(document.URL + ' .userLists')
            checkUrl()
        },
        error: function () {
            //
        }
    })
});

function remove(id,type) {
    $.ajax({
        type: "POST",
        url: route('remove' + type),
        data: { id: id,},
        success: function () {
            if (type = 'Task') {
                $('.content').load(document.URL + ' .todoList')
            }
        },
        error: function () {
            //
        },
    });

    if (type = 'List') {
        var path = window.location.href;
        var path = window.location.href;
        var active = path.split('list/')[1];

        if (id == active) {
              location.replace(route('index'))
        } else {
            $('.selectWrapper').load(document.URL + ' #listSelect')
            $('.userListsWrapper').load(document.URL + ' .userLists')
            checkUrl()
        }
    }
}

// Check Without Reload

function check(id) {
    var selecetd = '#item'+id;
    if($(selecetd).hasClass('done')) {
        $.ajax({
            type: "POST",
            url: route('check'),
            data: { id: id, value: 0},
            success: function () {
                $('.content').load(document.URL + ' .todoList')
                $(selecetd).removeClass('done')
            },
            error: function () {
                //
            },
        });
    } else {
        $.ajax({
            type: "POST",
            url: route('check'),
            data: { id: id, value: 1},
            success: function () {
                $('.content').load(document.URL + ' .todoList')
                $(selecetd).addClass('done')
            },
            error: function () {
                //
            },
        });
    }
}
// Set Priority Without Reload

function setPriority(id) {
    if ($('#item'+id).hasClass("stared")) {
        $.ajax({
            type: "POST",
            url: route('setPriority'),
            data: { id: id, value: 0},
            success: function () {
                $('.content').load(document.URL + ' .todoList')
            },
            error: function () {
                //
            },
        });
    } else {
        $.ajax({
            type: "POST",
            url: route('setPriority'),
            data: { id: id, value: 1},
            success: function () {
                $('.content').load(document.URL + ' .todoList')
            },
            error: function () {
                //
            },
        });
    }
}
