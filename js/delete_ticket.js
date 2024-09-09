
function deleteTicket(ticketId) {
    if (confirm('确定要删除此票务吗？')) {
        var form = document.createElement('form');
        form.method = 'post';
        form.style.display = 'none';

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_ticket';
        input.value = ticketId;

        form.appendChild(input);
        document.body.appendChild(form);

        form.submit();
    }
}