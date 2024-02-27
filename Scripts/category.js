$(document).ready(function(){
    $('#editCategoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var categoryId = button.data('category-id');
        var categoryName = button.data('category-name');

        $('#editCategoryName').val(categoryName);
        $('#editCategoryId').val(categoryId);
    });
    $('.toggle-category').on('click', function() {
        var categoryId = $(this).data('category-id');
        var isDisabled = $(this).data('is-disabled');
        var action = isDisabled ? "enable" : "disable";

        $('#toggleAction').text(action);
        $('#toggleCategoryModal').modal('show');

        $('#confirmToggle').off('click').on('click', function() {
            $.ajax({
                type: "POST",
                url: "../PHP/toggle_category.php", 
                data: {
                    category_id: categoryId,
                    is_disabled: !isDisabled 
                },
                success: function(response) {
                    window.location.reload(true);
                },
                error: function() {
                    alert('Error toggling category status.');
                }
            });
        });
    });
    $('.btn-danger[data-bs-target="#deleteCategoryModal"]').on('click', function() {
        var categoryId = $(this).data('category-id');

        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                type: "POST",
                url: "../PHP/delete_category.php",
                data: {
                    category_id: categoryId,
                },
                success: function(response) {
                    console.log(response)
                  //  window.location.reload(true);
                },
                error: function() {
                    alert('Error deleting category.');
                }
            });
        });
    });
});

$('#editCategoryForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize(); 
    console.log(formData)

    $.ajax({
        type: "POST",
        url: "../PHP/edit_category.php",
        data: formData,
        success: function(response) {
            console.log(response)
            var alertHtml = '';
            if(response.status === 'success') {
                window.location.reload(true);
            } else if(response.message === 'NameExists') {
                alertHtml = '<div class="alert alert-warning" role="alert">Category Name Exists!</div>';
            } else {
                alertHtml = '<div class="alert alert-danger" role="alert">Failed to update category.</div>';
            }
    
            $('#alertPlaceholder').html(alertHtml);
            setTimeout(function() {
                $('#alertPlaceholder').html('');
            }, 5000); // 5 seconds
        },
        error: function(xhr, status, error) {
            console.log("AJAX Error: " + status + " - " + error);
        }
    });
});
