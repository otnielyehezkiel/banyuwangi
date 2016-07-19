<html>
<head></head>
<body>
<div class="container">

    <ul>
        <?php
        foreach ($processedFiles as $file) {
            ?>
            <li><?php print_r($file['name']) ?></li>
            <?php
        }
        ?>
    </ul>
</div>
</body>
<script type="text/javascript" src="<?= base_url() ?>static/js/jquery-2.1.3.min.js"></script>
<script>
    $(document).ready(function () {
        parent.resetFormUpload1();
    });
</script>
</html>