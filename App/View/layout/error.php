<div class="alert-error" style="background-color: #ffdddd;
    color: #b30000;
    width: 60%;
    margin: auto;
    border: 1px solid #b30000;
    padding: 100px 15px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-weight: bold;
    text-align: center">
    <?= htmlspecialchars($_SESSION['errors']); ?>
</div>

<?php if (isset($_SESSION['errors'])) unset($_SESSION['errors']); ?>