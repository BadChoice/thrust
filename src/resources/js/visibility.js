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

    var element = $("#" + id);
    if (!id.includes('panel')) {
        element = element.parent().parent();
    }

    if (visibility["values"].some(v => v == $("#" + visibility["field"]).val())) {
        if (type == 'show') {
            element.show(animationSpeed);
            $("#" + id + " :input").removeAttr("disabled");
        } else {
            element.hide(animationSpeed);
            $("#" + id + " :input").attr("disabled", "true");
        }
    } else {
        if (type == 'show') {
            element.hide(animationSpeed);
            $("#" + id + " :input").attr("disabled", "true");
        } else {
            element.show(animationSpeed);
            $("#" + id + " :input").removeAttr("disabled");
        }
    }
}