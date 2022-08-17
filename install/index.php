<?php
    if (is_dir("api")) {
        include("api/form.php");
    } else {
        header('Location: ../');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>owler - Installation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body class="text-dark">
    <div class="row g-0" id="top-page">
        <div class="col-auto" id="logo">
            <img src="../assets/img/logo/full_size_owler_logo_no_shadow_flat_icon.png" width="30">
        </div>
        <div class="col align-self-center">
            <h4><strong><span class="owler-color">owler</span></strong> - Installation</h4>
        </div>
    </div>
    <div class="row g-0" id="secondRowInstall">
        <div class="col-6" data-aos="fade-right" data-aos-duration="1000" id="leftColInstallPage">
            <div class="row">
                <div class="col-auto offset-2">
                    <img src="../assets/img/logo/full_size_owler_logo.png" width="auto" height="auto" style="width: 157px;">
                </div>
                <div class="col">
                    <div>
                        <h4>Merci d'utiliser <strong><span class="owler-color">owler</span></strong> !</h4>
                        <p class="text-black-50">Merci de vous laisser guider tout au long de l'installation.<br>Cela ne devrait pas prendre plus de dix minutes pour pouvoir déployer votre plateforme de formation.<br><br>Pour commencer, merci de renseigner vos identifiants de base de données. Ils se trouvent soit dans dans votre compte utilisateur de votre hébergeur web, soit dans un des mails que vous avez reçu lors de votre abonnement à ce même hébergeur.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 col-xxl-4 offset-1 text-start" id="form-col">
            <h5 class="text-primary form-heading" id="title-form-heading">Connexion à la base de données</h5>

            <?= print_form($form); ?>

            <div class="row g-0">

                <div class="col-6 text-start">
                    <button class="btn btn-outline-dark btn-off" id="prev" type="button" disabled>
                        <i class="fas fa-arrow-left btnIcon" id="prev-button-icon"></i>
                    </button>
                </div>

                <div class="col-6 text-end">
                    <button class="btn btn-outline-primary" id="next" type="button">
                        <span class="spinner-border spinner-border-sm d-none" role="status" id="loading"></span>
                        <i class="fas fa-arrow-right btnIcon" id="next-button-icon"></i>
                        <span id="NextBtnMessage"></span>
                    </button>
                </div>

            </div>

            <div class="row justify-content-center" style="font-size: 10px;">
                <div class="col-auto">
                    <i class="fas fa-circle text-primary" id="step-1"></i>
                </div>
                <div class="col-auto">
                    <i class="fas fa-circle text-black-50" id="step-2"></i>
                </div>
                <div class="col-auto">
                    <i class="fas fa-circle text-black-50" id="step-3"></i>
                </div>
                <div class="col-auto">
                    <i class="fas fa-circle text-black-50" id="step-4"></i>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="./assets/js/form.js"></script>

    <script src="../assets/js/bs-init.js"></script>
</body>

</html>