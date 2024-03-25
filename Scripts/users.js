$('#createUserModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
});

$('#editUserModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 

    var userID = button.data("user-id");
    var userName = button.data("user-name");
    var userEmail = button.data("user-email");

    $('#editUserId').val(userID);
    $('#editUserName').val(userName);
    $('#editUserEmail').val(userEmail);

});

$('.promote-to-staff').on('click', function() {
    var userId = $(this).data('user-id');

    $('#promoteToStaffModal').modal('show');

    $('#confirmPromotionToStaff').off('click').on('click', function() {
        $.ajax({
            type: "POST",
            url: "../PHP/promote_to_staff.php", 
            data: {
                user_id: userId,
            },
            success: function(response) {
                window.location.reload(true);
            },
            error: function() {
                alert('Error promoting user to staff.');
            }
        });
    });
});

$('.demote-to-user').on('click', function() {
    var userId = $(this).data('user-id');

    $('#demoteToUserModal').modal('show');

    $('#confirmDemotionToUser').off('click').on('click', function() {
        $.ajax({
            type: "POST",
            url: "../PHP/demote_to_user.php", 
            data: {
                user_id: userId,
            },
            success: function(response) {
                window.location.reload(true);
            },
            error: function() {
                alert('Error demoting user.');
            }
        });
    });
});

$('.demote-to-staff').on('click', function() {
    var userId = $(this).data('user-id');

    $('#demoteToStaffModal').modal('show');

    $('#confirmDemotionToStaff').off('click').on('click', function() {
        $.ajax({
            type: "POST",
            url: "../PHP/demote_to_staff.php", 
            data: {
                user_id: userId,
            },
            success: function(response) {
                window.location.reload(true);
            },
            error: function() {
                alert('Error demoting user.');
            }
        });
    });
});

$('.promote-to-admin').on('click', function() {
    var userId = $(this).data('user-id');

    $('#promoteToAdminModal').modal('show');

    $('#confirmPromotionToAdmin').off('click').on('click', function() {
        $.ajax({
            type: "POST",
            url: "../PHP/promote_to_admin.php", 
            data: {
                user_id: userId,
            },
            success: function(response) {
                window.location.reload(true);
            },
            error: function() {
                alert('Error promoting user to admin.');
            }
        });
    });
});




$('.delete-user').on('click', function() {
    var userId = $(this).data('user-id');

    $('#deleteUserModal').modal('show');

    $('#confirmDelete').off('click').on('click', function() {
        $.ajax({
            type: "POST",
            url: "../PHP/delete_user.php", 
            data: {
                user_id: userId,
            },
            success: function(response) {
                window.location.reload(true);
            },
            error: function() {
                alert('Error deleting user.');
            }
        });
    });
});

