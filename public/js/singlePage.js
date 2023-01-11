// Select Current List
function setSelected() {
    var path = window.location.href;
    var id = path.split('list/')[1];
    $('.selectOption').removeAttr('seleted')
    $('.selectOption[value=' + id + ']').attr('selected', 'true')
}

// Set Navigation Indicator
function checkUrl() {
    var path = window.location.href;
    $('.navLink').removeClass('active');
    $('.navLink').each(function() {
        if (this.href === path) {
            $(this).addClass('active');
        }
    });
}

// Set Background Image
function setImage(id) {
    $('.backgruond').removeClass('active');
    $('.backgruond').each(function () {
        if (this.id == id) {
            $(this).addClass('active')
        }
    });
}

// Load List Items
function loadItems() {
    $('.content').removeClass('loading')
}

//Change Content
$('.navLink').click(function(e){
    e.preventDefault()
    $('.content').addClass('loading')
    var url = $(this).attr('href')
    var theme = $(this).attr('theme')
    var title = $(this).children('.navTitle').text()
    history.pushState(null,null,url)
    document.title = 'To-Do | ' + title
    $('.listTitle').load(document.URL + ' h4')
    $('.content').load(document.URL + ' .todoList',)
    checkUrl()
    setSelected()
    setImage(theme)
    setTimeout(loadItems, 1750)
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
                var item = '#item'+ id
                $(item).slideUp(250)
                setTimeout($('.content').load(document.URL + ' .todoList'), 250)
            }
        },
        error: function () {
            //
        },
    });
    if (type = 'List') {
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

// Check Task Without Reload
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

// Edit Task Note
$("#editNoteForm").on( "submit", function(e) {
    e.preventDefault();
    var dataString = $(this).serialize();
    $.ajax({
        type: "POST",
        url: route('editNote'),
        data: dataString,
        success: function () {
            closeNote()
        },
        error: function () {
            //
        },
    });
});

// Onload Functions
window.onload = checkUrl(),setDate(),$('.content').removeClass('loading')
