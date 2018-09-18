function setupVisibility(config){
    $.each(config, function(panel_id, visibility){
        setupFormFieldVisibility(panel_id, visibility);
    });
}

function setupFormFieldVisibility(panel_id, visibility){
    showOrHideFormVisibility(panel_id, visibility, false);
    $("#" + visibility["field"]).change(function(event){
        showOrHideFormVisibility(panel_id, visibility, true);
    });
}

function showOrHideFormVisibility(panel_id, visibility, animated){
    var animationSpeed = animated ? 'fast' : 0;
    //console.log($("#" + visibility["field"]).val(), visibility["value"]);
    if ($("#" + visibility["field"]).val() == visibility["value"]) {
        //console.log("Hide!" + "#panel_" + panel_id);
        $("#panel_" + panel_id).hide(animationSpeed);
    }
    else{
        //console.log("show");
        $("#panel_" + panel_id).show(animationSpeed);
    }
}