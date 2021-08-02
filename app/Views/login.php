<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title; ?></title>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css?ver=5.6">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/style.css'); ?>">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.js"></script>
</head>

<body>
    <div class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="login-form">
                        <h2>Log In</h2>
                        <?php if(session("msg")){ ?>

                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <?php echo session("msg"); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php } ?>
                        <form action="<?= $form_action; ?>" method="POST">
                            <?= csrf_field() ?>
                            <p class="email">
                                <label>Email:</label>
                                <input class="form-control" type="email" placeholder="johnsmith@mail.com" name="email"
                                    required="" />
                            </p>
                            <p class="pass">
                                <label>Password:</label>
                                <input class="form-control" type="password" placeholder="********" name="password"
                                    required="" />
                            </p>
                            <!-- <p class="forgot">
                            <input type="checkbox" id="" name="" value="">
                            Remember My Password
                            </p> -->
                            <p>
                                <input class="form-control" type="submit" value="LogIn" />
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>