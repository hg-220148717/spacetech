$('#viewOrderModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var orderId = button.data('order-id');
    var custName = button.data('user-name');
    var custEmail = button.data('user-email');
    var custAddr = button.data('user-addr');
    var orderTotal = button.data('order-total');
    var orderNotes = button.data('order-notes');

    $('#updateCustomerNameEmail').val(custName + " (" + custEmail + ")");
    $('#updateCustomerAddr').val(custAddr);
    $('#updateOrderTotal').val(orderTotal);
    $('#updateOrderNotes').val(orderNotes);
    $('#viewOrderModalLabel').html("View Order #" + orderId);

    var orderContents = JSON.parse(atob(button.data('order-contents')));
    console.log(orderContents);

    for(var orderItem of orderContents) {

        var tableRowHTML = "<tr><td>" + orderItem["product_name"] + "</td><td>" + orderItem["line_quantity"] + "</td><td>£" + orderItem["line_subtotal"] + "</td></tr>";

        $("#modalOrderItemsTbl > tbody:last-child").append(tableRowHTML);
    }

});