function setDate() {
    document.getElementById('date').valueAsDate = new Date();
}

function openModal(name) {
    $('.' + name).addClass('active')
}

function closeModal() {
    $('.modalWrapper').removeClass('active')
}

function editNote(title,id,note) {
    $('#editNoteForm').prepend(
        '<div class="form-floating textarea">\n' +
        '<textarea class="form-control" placeholder="Note goes here" name="note" id="taskNote" maxlength="125" rows="5"></textarea>\n' +
        '<label for="floatingTextarea">Task Note</label>\n' +
        '</div>')
    $('.editNote #taskTitle').text(title)
    $('.editNote #id').attr('value',id)
    $('.editNote #taskNote').text(note)
    $('.editNote').addClass('active')
}

function closeNote() {
    $('.modalWrapper').removeClass('active')
    $('#editNoteForm .textarea').remove()
}

function toggleSidebar() {
    $('.sidebar').toggleClass('open')
    $('main header .wrapper .btn').toggleClass('open')
}

$('.theme').click(function () {
    $(this).siblings().removeClass('selected')
    $(this).addClass('selected')
})
