//jquery script responsible for myaccount page
$(document).ready(function() {
    // if Edit button is clicked then we enable the inputs and save and delete account buttons
    $(".EditBtn").click(function () {
        $('#name').prop('disabled', false);
        $('#email').prop('disabled', false);
        $('#password').prop('disabled', false);
        $('#password-confirmation').prop('disabled', false);
        $('.SaveBtn').prop('disabled', false);
        $('.DeleteBtn').prop('disabled', false);
    });

    //if the delete account button is clicked we display the div of the myaccount page resposible
    // for a confirmation popup
    $('.DeleteBtn').click(function() {
        $('.DeleteUser').show();
        $('.MyAccountMain').hide();
    });

    //if user confirms the deletion of account we redirect him to the delete user page
    $('.ConfirmBtn').click(function() {
        window.location.href = './deleteuser.php?id=' + id;
    });

    //if user clicks the cancel button we hide the popup and display the edition div
    $('.CancelBtn').click(function() {
        $('.DeleteUser').hide();
        $('.MyAccountMain').show();
    });
});