<?php
    $dbFileConfigExists = file_exists('../config/db.php');
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

            <div id="step-1-form">

                <div class="fieldGroupInstallForm" id="host_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="host">Hôte MySQL*</label>
                    <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="host" title="Champ obligatoire" required>
                </div>

                <div class="fieldGroupInstallForm" id="mysql-username_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="mysql-username">Nom d'utilisateur MySQL*</label>
                    <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="mysql-username" title="Champ obligatoire" required>
                </div>
                <div class="fieldGroupInstallForm" id="mysql-password_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="mysql-password">Mot de passe MySQL</label>
                    <input class="form-control" type="password" data-bs-toggle="tooltip" data-bss-tooltip="" id="mysql-password" title="Champ obligatoire">
                </div>
                <div class="fieldGroupInstallForm" id="port_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="port">Port MySQL</label>
                    <input class="form-control" type="text" id="port" placeholder="Par défaut: 3306">
                </div>
            </div>

            <div id="step-2-form" class="d-none">
                <div class="fieldGroupInstallForm" id="platform_name_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="platform_name">Nom de votre plateforme de formation(s)*</label>
                    <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="platform_name" title="Champ obligatoire" required>
                </div>
                <div class="fieldGroupInstallForm" id="_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="platform-description">Description de votre plateforme de formation(s)</label>
                    <textarea class="form-control" id="platform-description"></textarea>
                </div>
                <p class="fw-light text-black-50" style="font-size: 12px;">N'ayez crainte, ces informations pourront êtres changées par la suite.</p>
            </div>

            <div id="step-3-form" class="d-none">

                <div class="row fieldGroupInstallForm">
                    <div class="col" id="admin_lastname_group">
                        <label class="form-label text-black-50 fieldLabelInstallForm" for="admin_lastname">Nom*</label>
                        <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="admin_lastname" title="Champ obligatoire" required>
                    </div>
                    <div class="col" id="admin_firstname_group">
                        <label class="form-label text-black-50 fieldLabelInstallForm" for="admin_firstname">Prénom*</label>
                        <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="admin_firstname" title="Champ obligatoire" required>
                    </div>
                </div>

                <div class="fieldGroupInstallForm" id="admin_email_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="admin_email">Adresse e-mail*</label>
                    <input class="form-control" type="text" data-bs-toggle="tooltip" data-bss-tooltip="" id="admin_email" required inputmode="email" title="Champ obligatoire">
                </div>
                <div class="fieldGroupInstallForm" id="admin_password_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="admin_password">Mot de passe*</label>
                    <input class="form-control" type="password" data-bs-toggle="tooltip" data-bss-tooltip="" data-bs-placement="left" id="admin_password" required title="Votre mot de passe doit contenir au moins 8 caractères, avec au moins un chiffre, une minuscule, une majuscule et un symbole">
                </div>
                <div class="fieldGroupInstallForm" id="admin_confirm_password_group">
                    <label class="form-label text-black-50 fieldLabelInstallForm" for="admin_confirm_password">Confirmer le mot de passe*</label>
                    <input class="form-control" type="password" id="admin_confirm_password" required title="Votre mot de passe doit contenir au moins 8 caractères, avec au moins un chiffre, une minuscule, une majuscule et un symbole">
                </div>
            </div>

            <div id="step-4-form" class="d-none">
                <p class="text-black-50 tos">En installant ce logiciel et complétant ce formulaire, je reconnais avoir lu et accepté que :</p>
                <div class="fieldGroupInstallForm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="formCheck-1" required>
                        <label class="form-check-label fw-bold text-black-50" for="formCheck-1">owler est un logiciel gratuit, libre et opensource. Il ne saurait être vendu, et aucun service de paiement ne pourrait être installé à l'intérieur de ce logiciel.</label>
                    </div>
                </div>
                <div class="fieldGroupInstallForm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="formCheck-2" required>
                        <label class="form-check-label fw-bold text-black-50" for="formCheck-2">owler est sous license GPLv3. Vous ne pouvez donc vous approprier la propriété intellectuelle du logiciel, ni même retiré la mention "<span class="owler-color">Propulsé par owler</span>".</label>
                    </div>
                </div>
                <div class="fieldGroupInstallForm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="formCheck-3" required>
                        <label class="form-check-label fw-bold text-black-50" for="formCheck-3">owler étant sous license GPLv3, je reconnais comprendre que sa tivoïsation est strictement interdite.</label>
                    </div>
                </div>
                <div class="fieldGroupInstallForm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="formCheck-4" required>
                        <label class="form-check-label fw-bold text-black-50" for="formCheck-4">Je suis conscient des conditions de la license GPLv3 : je peux bricoler le logiciel mais pas sans limite.</label>
                    </div>
                </div>
            </div>

            <div class="row g-0">

                <div class="col-6 text-start">
                    <button class="btn btn-outline-dark btn-lg btn-off" id="prev" type="button" disabled>
                        <i class="fas fa-arrow-left btnIcon" id="prev-button-icon"></i>
                    </button>
                </div>

                <div class="col-6 text-end">
                    <button class="btn btn-outline-primary btn-lg" id="next" type="button">
                        <span class="spinner-border spinner-border-sm d-none" role="status" id="loading"></span>
                        <i class="fas fa-arrow-right btnIcon" id="next-button-icon"></i>
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