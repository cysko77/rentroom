var redirect = function(url) {
    window.location.assign(url);
};
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT DECONNEXION
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$('#deconnexion').on("click", function() {
    $.ajax({
        url: $(this).attr('href'),
        type: "GET",
        dataType: 'json',
        async: true,
        success: function(json) {
            if (json.reponse == 'deconnexion') {
                alert("Vous êtes déconnecté!");
                var url = "index.php";
                redirect(url);
            }
        }
    });
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT_FORM_CONNEXION
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var session = $.cookie('USESSIONLOKI');
if(session !== undefined || session !== ""){
    $.ajax({
        url: "index.php?controller=membre&action=connexion",
        type: "GET",
        dataType: 'json',
        async: true,
        success: function(json) {
            if(json.reponse.mdp !== null && json.reponse.pseudo !== null){
                $("#inputPassword").val(json.reponse.mdp);
                $("#inputPseudo").val(json.reponse.pseudo);
            }
        }
    });
}

$("#linkConnexion").attr("href", "#");
$('#form_connexion').on("submit", function() {
    var btn = $('#connexion');
    btn.empty().append("Patientez...");
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            $("#myModalConnexion .alert").removeClass("alert-danger alert-dismissable form-group");
            $("#mesage").empty();
            btn.empty().append("Connexion");
            if (json.reponse == 'valide') {
                $("#myModalConnexion").empty();
                var windowHeight = $(window).height();
                var windowWidth = $(window).width();
                var marginTop = (windowHeight - 70) / 2;
                var marginLeft = (windowWidth - 310) / 2;
                $("#myModalConnexion").html('<div class="alert" style="margin-top:' + marginTop + 'px;width:310px;margin-left:' + marginLeft + 'px;height:100px;text-align: center;"><img align="center" src="../web/lokisalle/images/301.GIF"alt="loader" width="70" heigh="70"/>' +
                        '<h2 id="message" style="color:#fff"><strong>Veuillez patienter...</strong></h2>' +
                        '</div>');
                var url = "index.php?controller=membre&action=profil";
                setTimeout(function() {
                    redirect(url);
                }, 3000);
            } else {
                $("#message").html("<strong>Erreur de connexion! </strong> Votre pseudo ou votre mot de passe est incorrect!");
                $("#myModalConnexion .alert").addClass("alert-danger alert-dismissable form-group").slideDown();
                setTimeout(function() {
                    hideError("#alertConnexion", "#message", 2000)
                });
            }
        }
    });
    return false;
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT_FORM_INSCRIPTION
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function effacer(form) {
    $(':input', '#' + form)
            .not(':button, :submit, :reset, :hidden, :radio')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
}
$("#linkInscription").attr("href", "#");
$('#form_inscription').on("submit", function() {
    var btn = $('#inscription');
    btn.empty().append("Patientez...");
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            btn.empty().append("S'inscrire");
            if (json.alert[0] == 'success') {
                effacer("form_inscription");
                $("#messageInscription").html("<strong>Félicitation! </strong>" + json.alert[1]);
                $("#alertInscription").addClass("alert-success").slideDown();
                hideError("#alertInscription", "#messageInscription", 3000);
                
                $("div .has-error").removeClass("has-error");
                $("div .has-success").removeClass("has-success");
                $("div span .glyphicon-remove").removeClass("glyphicon-remove");
                $("div span .glyphicon-ok").removeClass("glyphicon-ok");
                //$("#cpVilleInscription").removeClass("has-error").removeClass("has-success");
                //$("#spanCpVilleInscription").removeClass("glyphicon-remove").removeClass("glyphicon-ok");
                //$("#ville_france_id_VilleInscription").removeClass("has-error").removeClass("has-success");

            } else {
                //On affiche le message d'erreur s'il existe (si pseudo et/ou email déjà existants)
                if (json.alert[1] !== undefined) {
                    $("#messageInscription").html("<strong>Erreur! </strong>" + json.alert[1]);
                    $("#alertInscription").addClass("alert-danger").slideDown();
                }
                else {
                    $("#alertInscription").hide();
                }
                ;
                //On affiche les imputs valides et invalides
                for (key in json.reponse) {
                    if (json.reponse[key] === false) {
                        if (key !== "sexe" && key !== "adresse" && key !== "ville_france_id") {
                            key = (key === "cp") ? "cpVilleInscription" : key;
                            $("#" + key).removeClass("has-success has-feedback");
                            $("#" + key).addClass("has-error has-feedback");
                        }
                        else {
                            key = (key === "ville_france_id") ? "ville_france_id_VilleInscription" : key;
                            $("#" + key).removeClass("has-success");
                            $("#" + key).addClass("has-error");
                        }
                        ;
                        $("#" + key + " span.glyphicon").removeClass("glyphicon-ok form-control-feedback");
                        $("#" + key + " span.glyphicon").addClass("glyphicon-remove form-control-feedback");
                    }
                    else {
                        if (key !== "sexe" && key !== "adresse" && key !== "ville_france_id") {
                            key = (key === "cp") ? "cpVilleInscription" : key;
                            $("#" + key).removeClass("has-error has-feedback");
                            $("#" + key).addClass("has-success has-feedback");
                        }
                        else {
                            key = (key === "ville_france_id") ? "ville_france_id_VilleInscription" : key;
                            $("#" + key).removeClass("has-error");
                            $("#" + key).addClass("has-success");
                        }
                        ;
                        $("#" + key + " span.glyphicon").removeClass("glyphicon-remove form-control-feedback");
                        $("#" + key + " span.glyphicon").addClass("glyphicon-ok form-control-feedback");
                    }

                }

            }
        }
    });

    return false;
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT SEARCH "Code postal"
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$.fn.ZipToCity = function(Geolocalisation) {
    $selector = $(this).selector;
    $selector = $selector.substring(1, $selector.length);
    var elementExist = document.getElementById($selector);
    if (elementExist !== null) {
        var valInputVilleFranceId = $(this).val();
        var oldValue = "";
        var ville = "";
        var elementId = this.attr("id");
        // On remplace input[ville_france_id] par un select[ville_france_id]
        if (valInputVilleFranceId) {
            var option = '', hidden = "", disabled = "";
        }
        else {
            var option = '<option value="">Selectionnez la ville</option>';
            var hidden = '<input type="hidden" id="' + elementId + 'Hidden" name="ville_france_id" value="">';
            var disabled = "disabled";
        }
        var ElementVilleFranceId = $(this).parent().attr("id");
        $(this).remove();
        $("#" + ElementVilleFranceId).removeClass("has-feedback").append('<select name="ville_france_id" class="form-control" id="' + elementId + 'Select" ' + disabled + '>' + option + '</select>' + hidden);
        SspanErrorVilleInput = $("#" + elementId + "Select").siblings('span.glyphicon');
        SspanErrorVilleInput.remove();
        var $selectVille = $("#" + elementId + "Select");
        var inputHiddenVille = elementId + "Hidden";
        var firstWhilegetVilleId = true;
        var newValueVille = "";
        var $loaderImg = $("#" + ElementVilleFranceId).find('img');
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //  Function getVilleId  pour recupèrer l'id de la ville en fct du code postal===============================================================
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var getVilleId = function() {
            var newValue = $("#inputCp" + elementId).val();
            if (newValue !== oldValue && (newValueVille.length || firstWhilegetVilleId)) {
                oldValue = newValue;
                if (newValue.length === 5) {
                    $("#" + inputHiddenVille).remove();
                    $loaderImg.css("display", "inline");
                    $.ajax({
                        url: "index.php?controller=ville_france&action=rechercher",
                        type: "GET",
                        dataType: 'json',
                        data: {cp: newValue},
                        async: true,
                        success: function(json) {
                            if (json.reponse == 'ok') {
                                var option = '<option value="">Selectionnez la ville</option>';
                                var selected = "";
                                for (key in json.ville) {
                                    if (!firstWhilegetVilleId)
                                        valInputVilleFranceId = $selectVille.val().trim();
                                    if (firstWhilegetVilleId || valInputVilleFranceId) {

                                        selected = (json.ville[key].id == valInputVilleFranceId) ? "selected" : "";
                                        option += '<option id="' + json.ville[key].id + '" value="' + json.ville[key].id + '" ' + selected + '>' + json.ville[key].nom_reel + '</option>';
                                        if (valInputVilleFranceId) {
                                            firstWhilegetVilleId = true;
                                            $("#cp" + elementId).removeClass("has-error has-feedback").removeClass("has-success has-feedback");
                                            $("#spanCp" + elementId).removeClass("glyphicon-remove form-control-feedback").removeClass("glyphicon-ok form-control-feedback");
                                        }
                                        else {
                                            firstWhilegetVilleId = true;
                                            $("#cp" + elementId).removeClass("has-error has-feedback").addClass("has-success has-feedback");
                                            $("#spanCp" + elementId).removeClass("glyphicon-remove form-control-feedback").addClass("glyphicon-ok form-control-feedback");
                                        }
                                    }
                                    else {
                                        firstWhilegetVilleId = true;
                                        option += '<option id="' + json.ville[key].id + '" value="' + json.ville[key].id + '">' + json.ville[key].nom_reel + '</option>';
                                        $("#cp" + elementId).removeClass("has-error has-feedback").addClass("has-success has-feedback");
                                        $("#spanCp" + elementId).removeClass("glyphicon-remove form-control-feedback").addClass("glyphicon-ok form-control-feedback");
                                    }
                                }
                                $selectVille.html(option).removeAttr("disabled");
                                $loaderImg.css("display", "none");
                            }
                            else {

                                var option = '<option value="">Selectionnez la ville</option>';
                                $selectVille.html(option);
                                $loaderImg.css("display", "none");
                            }
                        }
                    });
                }
                else {
                    $("#cp" + elementId).removeClass("has-success has-feedback").addClass("has-error has-feedback");
                    $("#spanCp" + elementId).removeClass("glyphicon-ok form-control-feedback").addClass("glyphicon-remove form-control-feedback");
                    $("#" + ElementVilleFranceId).removeClass("has-success").addClass("has-error");
                    var option = '<option value="">Selectionnez la ville</option>';
                    $selectVille.html(option).attr('disabled', 'disabled');
                    if (document.getElementById(inputHiddenVille) === null || document.getElementById(inputHiddenVille) === undefined) {
                        $("#" + ElementVilleFranceId).append('<input type="hidden" id="' + inputHiddenVille + '" name="' + ElementVilleFranceId + '" value="">');
                    }
                }
                ;
            }
        };

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Fonction getLatLngGmap pour recupèrer les coordonnées (longitude et latitude) gmap =======================================================
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var getLatLngGmap = function() {
            $("#" + inputHiddenVille).remove();
            valueVilleId = $selectVille.val();
            if (valueVilleId) {
                $("#" + ElementVilleFranceId).removeClass("has-error").addClass("has-success");
                ville = $("#" + valueVilleId).text();
                var adresse = $('#textareaAdresse').text().trim() + ', ' + $('#cp' + elementId).val().trim() + ', ' + ville;
                $.ajax({
                    url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + adresse + "&sensor=true&key=AIzaSyCUZLpYBA-G6cRa8paiDm07qlkyhRzC05c",
                    type: "GET",
                    dataType: 'json',
                    success: function(json) {
                        if (json.status == 'OK') {
                            var latlng = json.results[0].geometry.location;
                            var lat = latlng.lat;
                            var lng = latlng.lng;
                            $("#lat").val(lat);
                            $("#lng").val(lng);
                        }
                    }
                });
            }
            else {
                $("#" + ElementVilleFranceId).removeClass("has-success").addClass("has-error");
                $("#lat").val("");
                $("#lng").val("");
            }
            ;
        };

        // Au chargement de la page on va chercher l'id de la ville, s'il existe
        if (document.getElementById("inputCp" + elementId) !== null) {
            getVilleId();
        }

        // Au changement de valeur du select[ville] au va recupèrer la latitude et longitude  

        $selectVille.change(function() {

            if ($selectVille.val()) {
                $("#" + ElementVilleFranceId).removeClass("has-error").addClass("has-success");
            }
            else {

                $("#" + ElementVilleFranceId).removeClass("has-success").addClass("has-error");
            }
            if (Geolocalisation) {
                getLatLngGmap();
            }
        });

        // Si on clique dans input[code postal], on recupère la ville en fonction du code postal renseigné
        $("#inputCp" + elementId).on("focus", function() {
            var selectVilleExist = document.getElementById(elementId + "Select");
            if (selectVilleExist !== undefined && selectVilleExist.value) {
                newValueVille = $selectVille.val().trim();
            }
            var inte = setInterval(function() {
                getVilleId();
            }, 100);
        });
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT_FORM_MDP
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var hideError = function(element1, element2, time) {
    setTimeout(function() {
        $(element1).removeClass("alert-danger").removeClass("alert-success").slideUp();
        $(element2).empty();
    }, time);
};
$('#form_mdp').on("submit", function() {
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        dataType: 'json',
        async: true,
        success: function(json) {
            if (json.reponse[0] == 'success') {
                $("#reponsePass").removeClass("alert-danger").addClass("alert-success").slideDown();
                $("#messagePass").text(json.reponse[1]);
                effacer("form_mdp");
                hideError("#reponsePass", "#messagePass", 5000);

            } else {
                if (json.reponse[0] == 'error') {
                    $("#reponsePass").removeClass("alert-success").addClass("alert-danger").slideDown();
                    $("#messagePass").text(json.reponse[1]);
                    effacer("form_mdp");
                    hideError("#reponsePass", "#messagePass", 3000);
                }
                ;
                if (json.reponse[0] == 'danger') {
                    $("#reponsePass").removeClass("alert-success").addClass("alert-danger").slideDown();
                    $("#messagePass").text(json.reponse[1]);
                    hideError("#reponsePass", "#messagePass", 3000);

                }
                ;

            }
            ;
        }
    });

    return false;
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT_TRI_AFFICHAGE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$('#affiche th a').on("click", function() {
    $.ajax({
        url: $(this).attr('href'),
        type: "GET",
        dataType: 'json',
        success: function(json) {
            if (json.reponse == "success") {
                $("#contenuProduit").empty().hide();
                $("#contenuProduit").html(json.result).show();
                var pag = $(json.pagination).filter("#pagination");
                $("#pagination").html(pag);
                del();
                initLightbox();
                init();
            }

        }
    });

    return false;
});
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// JAVASCRIPT_SEARCH_SALLE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var oldValue = "";
var salleFound = "";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Fonction getSalleId pour recupèrer l'id de la salle en fct de critére ville, cp, categorie, etc... ========================================================
var getSalleId = function(newVal) {
    if (newVal) {
        var newValue = "France";
        salleFound = true;
    }
    else {
        var newValue = $.trim($("#salle").val());
        salleFound = false;
    }

    if (newValue !== oldValue) {
        oldValue = newValue;
        if (newValue.length !== 0) {
            $("#loader_cp").css("display", "inline");
            $.ajax({
                url: "index.php?controller=produit&action=ajouter",
                type: "GET",
                dataType: 'json',
                data: {search: newValue},
                async: true,
                success: function(json) {
                    if (json.reponse == 'success') {

                        if (!salleFound) {
                            $("#contenu").css({display: "none"});
                            var data = '<label class="label label-warning" style="position:absolute; right:5px; top:5px">Nombre de résultats: ' + json.result.length + '</label>';
                            data += '<ul class="list-unstyled" style="margin:20px 0">';
                            console.info(json.result.length);
                            for (key in json.result) {
                                data += '<li><a onclick="return false;" id="' + json.result[key].id + '"class="link-grey col-xs-12 pad0" href="#"><span id="del" class="glyphicon glyphicon-star-empty">&nbsp;</span>' + json.result[key].titre + ' - ' + json.result[key].adresse + ' ' + json.result[key].cp + ' ' + json.result[key].nom_reel + ' - Capacité: ' + json.result[key].capacite + ' - ' + json.result[key].categorie + '</a></li>';
                            }
                            data += '</ul>';
                            $("#contenu").html(data).css({display: "block"});
                            $("#contenu a").on("click", function() {
                                var salle_id = $(this).attr("id");
                                $("#salle_id").val(salle_id);
                                var cont = $(this).html();
                                cont = cont.split("</span>");
                                $("#salle").val(cont[1]);
                                oldValue = newValue = cont[1];
                                $("#contenu").empty().hide();
                            });

                        }
                        else {
                            for (key in json.result) {
                                if (json.result[key].id === newVal) {
                                    data = json.result[key].titre + ': ' + json.result[key].adresse + ' ' + json.result[key].cp + ' ' + json.result[key].nom_reel + ' - Capacité de la salle: ' + json.result[key].capacite + ' - Catégorie de la salle: ' + json.result[key].categorie;
                                }
                            }
                            $("#contenu").empty().hide();
                            $("#salle").val(data);
                            oldValue = newValue = data;
                        }
                        $("#loader_cp").css("display", "none");
                        $("#inputSalle").removeClass("has-error has-feedback").addClass("has-success has-feedback");
                        $("#spanCp").removeClass("glyphicon-remove form-control-feedback").addClass("glyphicon-ok form-control-feedback");
                    }
                    else {
                        $("#contenu").empty().hide();
                        $("#salle_id").val("");
                        $("#inputSalle").removeClass("has-success has-feedback").addClass("has-error has-feedback");
                        $("#spanCp").removeClass("glyphicon-ok form-control-feedback").addClass("glyphicon-remove  form-control-feedback");
                        $("#loader_cp").css("display", "none");

                    }
                    ;
                }
            });
        }
        else {
            $("#contenu").empty().hide();
            $("#inputSalle").removeClass("has-success has-feedback").addClass("has-error has-feedback");
            $("#spanCp").removeClass("glyphicon-ok form-control-feedback").addClass("glyphicon-remove form-control-feedback");
        }
        ;
    }

};
// Fin function getSalleId ===================================================================================================================================


var valueSalle = $("#salle_id").val();
if (valueSalle) {
    getSalleId(valueSalle);
}
$("#salle_id").remove();
$("#inputSalle").append('<input  autocomplete="off" class="form-control" type="text" name="salle_id_search" id="salle" value="" placeholder="Rechercher une salle: Ex: Ville , nom de la salle, code postal, ...">' +
        '<span id="spanCp" class="glyphicon"></span>' +
        '<input id="salle_id" type="hidden" name="salle_id" value="' + valueSalle + '">' +
        '<div class="pad0" id="contenu"></div>'
        );

$("#salle").on("focus", function() {
    var a = setInterval(function() {
        getSalleId(null)
    }, 100);
});


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CHECK VERSION INTERNET EXPLORER
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function browserVersSup(internet) {
    var userAgentWeb = navigator.userAgent;
    if (userAgentWeb.indexOf('MSIE') !== -1) {
        var browser = userAgentWeb.substring(userAgentWeb.indexOf("MSIE") + 5, userAgentWeb.indexOf("MSIE") + 10);
        var version = browser.split("\.");
        version = version[0];
        return (version > internet) ? true : false;
    }
    else {
        return true
    }

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// UPLOAD IMG AJAX
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (browserVersSup(9)) {
    $("#qqfile").remove();
    if (document.getElementById('file-uploader') !== null) {
        var data = $("#file-uploader").attr("data")
        if (data !== null) {
            var uploader = new qq.FileUploader({
                element: document.getElementById('file-uploader'),
                action: 'index.php?controller=' + data + '&action=uploadImg',
                data: data,
                debug: true,
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'GIF', 'PNG', 'JPEG', 'JPG']
            });
            if (data === "membre") {
                $(".qq-upload-button").css({margin: "50px 120px"});
            }
        }
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ANIMATION MENU
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("ul#topnav li ").hover(function() {

    $(this).find("div").slideDown(100);
}, function() {
    $(this).css({'background': 'none'});
    $(this).find("div").slideUp(100);
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CALENDRIER
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$('#date_timepicker_arrivee').datetimepicker({
    lang: 'fr',
    timepicker: true,
    format: 'd/m/Y H:i',
    minDate: 0,
    onShow: function(ct) {
        var dateMax = $('#date_timepicker_depart').val();
        if (dateMax) {
            dateMax = dateMax.split(' ');
            dateMax = dateMax[0].split('/').reverse();
            dateMax = dateMax.join('/');
        } else {
            dateMax = false;
        }

        this.setOptions({
            maxDate: dateMax
        })
    }
});

$('#date_timepicker_depart').datetimepicker({
    lang: 'fr',
    timepicker: true,
    format: 'd/m/Y H:i',
    onShow: function(ct) {
        var dateMin = $('#date_timepicker_arrivee').val();
        if (dateMin) {
            dateMin = dateMin.split(' ');
            dateMin = dateMin[0].split('/').reverse();
            dateMin = dateMin.join('/');
        } else {
            dateMin = false;
        }
        this.setOptions({
            minDate: dateMin
        })
    }
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ALERT SUPPRESSION 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var del = function() {
    $(".delete").on("click", true, function() {
        var message = $(this).attr("data");
        return confirm('Vous êtes sur le point ' + message + '! \nVoulez-vous continuer?');
    });
};
del();
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// COOKIE POUR savoir si javascript est activé
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$("html").attr("class", "js");
if ($("html").attr("class") == "js") {
    $.cookie('js', 'js', {
        expire: 1
    });
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Changer valeur statut commande  (Onglet gestion commande)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var valueOldSelect="";
var init = function(){
var selectStatut = $(".statut_commande");

var hasClicked = [];
selectStatut.parent().on("click", function(e) {

    selectStatut.each(function(i) {
        $(this).attr("disabled", "disabled");
    });
    $(this).children().removeAttr("disabled");
    var commandeId = $(this).children().attr("id");

    var selectData = $("#" + commandeId).attr("data");
    // Créer les inputs hidden pour envoi du formulaire
    var valueDate = $("#date-" + selectData).attr("data");
    var valueMontant = $("#montant-" + selectData).attr("data");
    var valueMembre = $("#membre_id-" + selectData).attr("data");
    var inputs = '<input type="hidden" name="id" value="' + selectData + '">' +
            '<input type="hidden" name="date" value="' + valueDate + '">' +
            '<input type="hidden" name="montant" value="' + valueMontant + '">' +
            '<input type="hidden" name="membre_id" value="' + valueMembre + '">';
    // On intégre les inputs dans le document
    $(".inputs").each(function(e) {
        $(this).empty();
    });
    $("#inputs-" + selectData).html(inputs);
    var exit = $.inArray(commandeId, hasClicked);
    if (exit === -1) {
        valueOldSelect = $("#" + commandeId).val();
        changeStatut(commandeId, selectData, valueMontant);
        hasClicked.push(commandeId);
    }

});
}

init();


var changeStatut = function(commandeId, selectData, valueMontant) {
    $("#" + commandeId).change(function(e) {
        var valueSelect = $("#" + commandeId).val();
        var validCommande = (valueSelect === "Payée") ? true : false;
        var selectorImg = $("#load-" + selectData);
        selectorImg.show();
        $.ajax({
            url: "index.php?controller=commande&action=modifier",
            type: "POST",
            data: $("#form").serialize(),
            dataType: 'json',
            success: function(json) {
                selectorImg.hide();
                if (json.alert[0] === "success") {
                    
                    $(".inputs").each(function(e) {
                        $(this).empty();
                    });
                    $("#" + commandeId).attr("disabled", "disabled");
                    var ca = $("#ca").text();
                    ca = ca.substring(0, ca.length - 1);
                    ca = parseFloat(ca);
                    valueMontant = parseFloat(valueMontant);
                    if (ca || (!ca && validCommande)) {
                        if (validCommande) {
                            ca = ca + valueMontant;
                        }
                        else {
                            if(valueOldSelect !== "En attente de paiement" && valueOldSelect !== "Annulée"){
                                ca = ca - valueMontant;
                            }
                        }
                        
                    }
                    else {
                        ca = 0;
                    }
                    $("#ca").text(ca + "€");
                    valueOldSelect = valueSelect;
                }
                var message = '<div class="alert alert-' + json.alert[0] + ' alert-dismissable">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        json.alert[1] + '</div>';
                $("#message").empty().hide();
                $("#message").html(message).slideDown();
            }
        });
    });
};




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// API GOOGLE MAP (DETAILS PRODUIT)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

var long, lat;
var isset = function(arg) {
    if (arg !== undefined) {
        return (arg !== null && arg.length !== 0 && arg != 0) ? true : false;
    }
    else {
        return false;
    }
}

if (isset(long) && isset(lat)) {
    function initialize() {
        // Create an array of styles.
        var styles =
                [{}];
        // Create a new StyledMapType object, passing it the array of styles,
        // as well as the name to be displayed on the map type control.

        var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
        var mapOptions = {
            disableDefaultUI: true,
            center: new google.maps.LatLng(lat, long),
            zoom: 14,
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
        };

        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            title: 'Click to zoom'
        });

        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');
    }
    google.maps.event.addDomListener(window, 'load', initialize);
}
else {
    if (document.getElementById("map-canvas") !== undefined) {
        $("#map-canvas").css({height: "0px"});
        $("#relLeft").css({marginTop: 0});
        $("#absLeft").css({top: 0});
    }
}
;