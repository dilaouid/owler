<?php

    $form = [
        [
            "heading" => [],
            "rows" => [],
            "data" => [
                [ "name" => "host",
                "required" => true,
                "label" => "Hôte MySQL*",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "mysql-username",
                "required" => true,
                "label" => "Nom d'utilisateur MySQL*",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "mysql-password",
                "required" => false,
                "label" => "Mot de passe MySQL",
                "type" => "password",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "port",
                "required" => false,
                "label" => "Port MySQL",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => "Par défaut: 3306" ]
            ]
        ],
        [
            "heading" => [
                "title" => "N'ayez crainte, ces informations pourront êtres changées par la suite.",
                "class" => "fw-light text-black-50 fieldLabelInstallForm",
                "position" => "bottom"
            ],
            "rows" => [],
            "data" => [
                [ "name" => "platform_name",
                "required" => true,
                "label" => "Nom de votre plateforme de formation(s)*",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "platform_description",
                "required" => false,
                "label" => "Description de votre plateforme de formation(s)",
                "type" => "textarea",
                "extra" => null,
                "title" => null,
                "placeholder" => null ]
            ]
        ],
        [
            "heading" => null,
            "rows" => [0,1],
            "data" => [
                [ "name" => "admin_lastname",
                "required" => true,
                "label" => "Nom*",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "admin_firstname",
                "required" => true,
                "label" => "Prénom*",
                "type" => "input",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "admin_email",
                "required" => true,
                "label" => "Adresse e-mail*",
                "type" => "input",
                "extra" => "inputmode=\"email\"",
                "title" => null,
                "placeholder" => null ],

                [ "name" => "admin_password",
                "required" => true,
                "label" => "Mot de passe*",
                "type" => "password",
                "extra" => "autocomplete=\"new-password\"",
                "title" => "Votre mot de passe doit contenir au moins 8 caractères, avec au moins un chiffre, une minuscule, une majuscule et un symbole",
                "placeholder" => null ],

                [ "name" => "confirm_admin_password",
                "required" => true,
                "label" => "Confirmer le mot de passe*",
                "type" => "password",
                "extra" => "autocomplete=\"new-password\"",
                "title" => "Votre mot de passe doit contenir au moins 8 caractères, avec au moins un chiffre, une minuscule, une majuscule et un symbole",
                "placeholder" => null ],
            ]
        ],
        [
            "heading" => [
                "title" => "En installant ce logiciel et complétant ce formulaire, je reconnais avoir lu et accepté que :",
                "class" => "text-black-50 tos",
                "position" => "top"
            ],
            "rows" => [],
            "data" => [
                [ "name" => "condition_1",
                "required" => true,
                "label" => "owler est un logiciel gratuit, libre et opensource. Il ne saurait être vendu, et aucun service de paiement ne pourrait être installé à l'intérieur de ce logiciel.",
                "type" => "checkbox",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "condition_2",
                "required" => true,
                "label" => "owler est sous license GPLv3. Vous ne pouvez donc vous approprier la propriété intellectuelle du logiciel, ni même retiré la mention \"<span class=\"owler-color\">Propulsé par owler</span>\".",
                "type" => "checkbox",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "condition_3",
                "required" => true,
                "label" => "owler étant sous license GPLv3, je reconnais comprendre que sa tivoïsation est strictement interdite.",
                "type" => "checkbox",
                "extra" => null,
                "title" => null,
                "placeholder" => null ],

                [ "name" => "condition_4",
                "required" => true,
                "label" => "Je suis conscient des conditions de la license GPLv3 : je peux bricoler le logiciel mais pas sans limite.",
                "type" => "checkbox",
                "extra" => null,
                "title" => null,
                "placeholder" => null ]
            ]
        ]
    ];

    function is_checkbox($data) {
        return $data["type"] === "checkbox";
    }

    function filter_required($data) {
        return ($data["required"] == true && key($data) == "name");
    }

    function get_required_input($arr) {
        $new = [];
        for ($i=0; $i < count($arr); $i++)
            if ($arr[$i]["required"]) array_push($new, $arr[$i]["name"]);
        return $new;
    };

    function add_class($className) {
        $attr = "class=\"".$className."\"";
        return $attr;
    }

    function print_heading($heading, $pos) {
        if (!$heading || $heading["position"] != $pos) return;
        return "<p ".add_class($heading["class"]).">".$heading["title"]."</p>";
    }

    function print_col_input($name, $checkbox, $isRow) {
        if ($checkbox) return "<div class=\"fieldGroupInstallForm\"><div class=\"form-check\" id=\"".$name."_group\" >";
        return "<div class=\"". ($isRow ? "col" : "fieldGroupInstallForm") ."\" id=\"".$name."_group\" >";
    }

    function print_label($data, $className) {
        return "<label class=\"".$className."\" for=\"". $data["name"]."\">".$data["label"]."</label>";
    }

    function get_title($data) {
        return $data["title"] ? $data["title"] : "Champ obligatoire";
    }

    function print_input($data, $extra) {
        $attr = "";
        if ($data["required"]) {
            $attr = "required";
            if (!is_checkbox($data))
                $attr .= " data-bs-toggle=\"tooltip\" data-bss-tooltip=\"\" title=\" " . get_title($data) . " \"";
        }
        $class = null;
        if ($data["type"] != "checkbox") $class = "form-control";
        else $class = "form-check-input";

        $type = $data["type"];
        if ($type == "input") $type = "text";
        
        $baliseName = $data["type"] == "checkbox" ||  $data["type"] == "password" ? "input" : $data["type"];
        $endBalise = $data["type"] == "textarea" ? "></textarea>" : "/>";

        return " <" . $baliseName . " placeholder=\"".$data["placeholder"]."\" ".$data["extra"]." type=\"".$type."\" class=\"".$class."\" id=\"".$data["name"]."\" ".$attr." ".$endBalise."";
    }

    function print_form($form) {
        $dom = '';
        for ($i=0; $i < count($form); $i++) { 
            $formEl = $form[$i];
            $dom .= "<div id=\"step-". ($i + 1) ."-form\" ". ($i == 0 ? null : add_class("d-none")) ." >";
            $dom .= print_heading($formEl["heading"], "top");
            $isRow = false;
            for ($j=0; $j < count($formEl["data"]); $j++) {
                $formData = $formEl["data"][$j];
                if (array_key_exists($j, $formEl["rows"]) && !array_key_exists($j - 1, $formEl["rows"])) {
                    $isRow = true;
                    $dom .= "<div class=\"row fieldGroupInstallForm\">";
                }
                $dom .= print_col_input($formData["name"], is_checkbox($formData), $isRow);
                $dom .= is_checkbox($formData) ? print_input($formData, null) : print_label($formData, "form-label text-black-50 fieldLabelInstallForm");
                $dom .= is_checkbox($formData) ? print_label($formData, "form-check-label fw-bold text-black-50") : print_input($formData, null);
                $dom .= "</div>";
                if (array_key_exists($j, $formEl["rows"]) && !array_key_exists($j + 1, $formEl["rows"])) {
                    $dom .= "</div>";
                    $isRow = false;
                }
                if (is_checkbox($formData)) $dom .= '</div>';
            }
            $dom .= print_heading($formEl["heading"], "bottom");
            $dom .= '</div>';
        }
        echo $dom;
    }
?>