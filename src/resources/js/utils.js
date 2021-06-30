let popupUrl;
let didPopupReload  = false;

$(document).ready(function(){

    setupFormLoadingImage();

    $('#popup').popup({
        scrolllock:true,
        blur:true,
        transition:'all 0.3s',
    });

    $(".sortable").sortable({
        axis: "y",
    });
    // $(".sortable td").each(function () {
    //     $(this).css("width", $(this).width());
    // });

    $('.dropdown').click(function() {
        $(this).next('.dropdown-container-inverse').toggle();
        $(this).next('.dropdown-container').toggle();
    });

    addListeners();
});

function addActionsDropdownListener() {
    $(document).on('click.actionsDropdown', function onClickDropdownContainer(mouseClickEvent) {
        let clickOutDropdownContainer = !$(mouseClickEvent.target).parents(".dropdown-container").length;
        let clickOutActionButtons = !$(mouseClickEvent.target).parents(".dropdown.inline").length;
        let dropdownContainer = $('.dropdown-container');
        if (dropdownContainer.is(":visible") && clickOutDropdownContainer &&clickOutActionButtons) {
            dropdownContainer.hide();
            $(this).off('click.actionsDropdown');
        }
    });
}

function addListeners(){
    $('.dropdown.inline').on('click', function () {
        addActionsDropdownListener()
    });

    $('[data-post]').off('click').on('click', function (e) {
        e.preventDefault();
        return $('<form action="' + $(this).attr('href') + `" method="POST"><input type="hidden" name="_token" value="${csrf_token}"></form>`).appendTo('body').submit();
    });

    $('[data-delete]').off('click').on('click', function (e) {
        let dataDelete = $(this).attr('data-delete');
        if (dataDelete.indexOf("confirm") !== -1 && ! confirm(confirmDelete)) {
            return e.preventDefault();
        }
        if (dataDelete.indexOf("ajax") !== -1) {
            e.preventDefault();
            return callAjax($(this).attr('href'), {"_method": "delete"});
        }
        if (dataDelete.indexOf("resource") !== -1) {
            e.preventDefault();
            return $('<form action="' + $(this).attr('href') + '" method="POST"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' + csrf_token + '"></form>').appendTo('body').submit();
        }
        if (! confirm(confirmDelete)) {
            e.preventDefault();
        }
    });

    // ajax and delete used together to do a post to some endpoint in order to delete or detach a resource
    $(".ajax").off('click').on('click', function(e){
        e.preventDefault();
        callAjax($(this).attr('href'));
    });

    // ajax and toggle
    $(".ajax-get").off('click').on('click', function(e){
        e.preventDefault();
        ajaxGet($(this).attr('href'));
    });

    $(".showPopup").off('click').on('click',function(e) {
        e.preventDefault();
        showPopup($(this).attr('href'));
    });
}

function showPopup(popupUrl) {
    $('#popup').popup('show');
    $("#popupContent").html("Loading... " + '<i class="fa fa-circle-o-notch fa-spin"></i>');   //loading image defined at master.blade
    loadPopup(popupUrl);
}
//----
// Displays the spinning image when
// "save" button of form is pressed
//----
function setupFormLoadingImage(){
    $('form').submit(function(event){
        $('.loadingImage').show();
        return true;
    });
};

//========================================
// POPUPS
//========================================
function loadPopup(url){
    $("#popupContent").load(url,function(response,status,xhr) {
        console.log('ok', url, status);
        $(".loadingImage").hide();
        if(status == "error")
            $("#popupContent").html("Error while loading: " + xhr.status + " " + xhr.statusText);
        else
            setupFormLoadingImage();
    });
}

function loadPostPopup(url, data) {
    $.post(url, data, function(response) {
        $("#popupContent").show().html(response);
    });

}

function hidePopup(){
    if(didPopupReload)
        return location.reload();
    $('#popup').popup('hide');
}

function reloadPopup(){
    didPopupReload = true;
    loadPopup(popupUrl);
}

//========================================
// AJAX
//========================================
function callAjax(url, data){

    if(window.location.href.toString().search("public") != - 1){
        url =  "/revo-retail/public" + url;
    }

    if( ! data )    { data =                    {_token : csrf_token}   }
    else            { data = $.extend({}, data, {_token : csrf_token}); }

    $.post(url, data, function(){})
        .done(function(data) {
            if(data) {
                $(".loadingImage").hide();
                showMessage("done");
                reloadPopup();
            }
        })
        .fail(function(result) {
            console.log(result);
            $(".loadingImage").hide();
            showMessage(result.responseText);
        });
}

function ajaxGet(url){

    if(window.location.href.toString().search("public") != - 1){
        url =  "/revo-retail/public" + url;
    }

    $.get(url, function(){})
        .done(function(data) {
            if(data) {
                $(".loadingImage").hide();
                showMessage("done");
                reloadPopup();
            }
        })
        .fail(function(result) {
            console.log(result);
            $(".loadingImage").hide();
            showMessage(result.responseText);
        });
}

//========================================
// SORT
//========================================
function saveOrder(model, startAt){
    $(".loadingImage").show();
    postSaveOrder(window.location.origin + '/thrust/' + model + '/updateOrder?startAt=' + startAt,
                 ".sortable");
}

function saveChildOrder(model, id, field){
    $(".loadingImage").show();
    postSaveOrder(window.location.origin + '/thrust/' + model + '/' + id + '/belongsToMany/' + field + '/updateOrder',
                 '.sortableChild');
}

function postSaveOrder(url, classToSerialize){
    let serialized  =  $(classToSerialize).sortable('serialize');
    serialized = serialized + "&_token="+csrf_token;
    console.log("Url:"   + url);
    console.log("Serialized:"   + serialized);

    $.post(url, serialized, function(){})
        .done(function(data) {
            if(data) {
                $(".loadingImage").hide();
                console.log(data);
                showMessage("orderSaved");
            }
        })
        .fail(function(result) {
            $(".loadingImage").hide();
            showMessage("orderNotSaved");
        });
}

function showMessage(message, values){
    $('#popupMessage').fadeIn('slow',function(){
        $('#popupMessage').delay( 1200 ).fadeOut( 400 );
    }).html(parseMessage(message, values));
}

function parseMessage(message, values) {
    return replaceValuesOnMessage(getTranslatedMessage(message), values);
}

function getTranslatedMessage(message) {
    if (typeof lang === "undefined") {
        return message;
    }
    return lang[message] ? lang[message] : message;
}

function replaceValuesOnMessage(message, values) {
    if ( ! values ) return message;

    for (let i = 0; i < values.length; i++) {
        let reg = new RegExp("\\{" + i + "\\}", "gm");
        message = message.replace(reg, values[i]);
    }
    return message;
}

function hideMessage(){
    $("#message").stop().fadeOut();
}

function drawIcon(icon) {
    return $("<i>", {class: "fa fa-"+ icon + " revo-awesome", style: "font-size: 16px;", "aria-hidden": "true"});
}
