// messageBox.js
$(document).ready(function() {
    function showMessage(message) {
        var messageBox = $("#message-box");
        messageBox.text(message);
        messageBox.show();
        setTimeout(function() {
            messageBox.hide();
        }, 5000);
    }

    // Check for session message
    <?php if(isset($_SESSION["mensaje"]) && $_SESSION["mensaje"] !== null): ?>
    showMessage("<?php echo addslashes($_SESSION["mensaje"]); ?>");
    <?php unset($_SESSION["mensaje"]); ?>
    <?php endif; ?>

    // Expose showMessage function globally
    window.showMessage = showMessage;
});