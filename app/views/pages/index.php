<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('post_message'); ?>
<div class="jumbotron jumbotrom-flud text-center text-white" style="background-image: url(https://mdbcdn.b-cdn.net/img/new/slides/003.jpg)">
    <div class="container">
        <h1 class="display-3"><?php echo $data['title']; ?></h1>
        <p class="lead"><?php echo $data['description']; ?></p>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
