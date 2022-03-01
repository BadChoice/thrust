function setupVisibility(hideConfig, showConfig) {
    $.each(hideConfig, function (panel_id, visibility) {
        setupFormFieldVisibility(panel_id, visibility, 'hide');
    });
    $.each(showConfig, function (panel_id, visibility) {
        setupFormFieldVisibility(panel_id, visibility, 'show');
    });
}

function setupFormFieldVisibility(panel_id, visibility, type) {
    showOrHideFormVisibility(panel_id, visibility, false, type);
    $("#" + visibility["field"]).change(function (event) {
        showOrHideFormVisibility(panel_id, visibility, true, type);
    });
}

function showOrHideFormVisibility(id, visibility, animated, type) {
    var animationSpeed = animated ? 'fast' : 0;

    // console.log("Panel id: " + panel_id);
    // console.log(visibility["values"]);
    // console.log($("#" + visibility["field"]).val());

    if (visibility["values"].includes(parseInt($("#" + visibility["field"]).val()))) {
        if (type == 'show') {
            console.log("show");
            $("#" + id).show(animationSpeed);
            $("#" + id + " :input").removeAttr("disabled");
        } else {
            console.log("hide");
            $("#" + id).hide(animationSpeed);
            $("#" + id + " :input").attr("disabled", "true");
        }
    } else {
        if (type == 'show') {
            console.log("hide in else");
            $("#" + id).hide(animationSpeed);
            $("#" + id + " :input").attr("disabled", "true");
        } else {
            console.log("show in else");
            $("#" + id).show(animationSpeed);
            $("#" + id + " :input").removeAttr("disabled");
        }
    }
}