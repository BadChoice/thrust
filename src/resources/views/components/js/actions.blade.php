<script>
    const onClickHandler = function(e) {
        e.preventDefault()
        var selected = getSelectedRowsIds()
        if (selected.length == 0){
            return alert("{!! __("thrust::messages.noRowsSelected") !!}")
        }

        this.setAttribute('href', this.getAttribute('href') + "&ids=" + selected + "&search=" + searching)
        showPopup(this.getAttribute('href'))
    }

    function registerActionPopupListeners() {
        const elems = [...document.getElementsByClassName('actionPopup')]
        elems.forEach(elem => {
            elem.removeEventListener('click', onClickHandler)
            elem.addEventListener('click', onClickHandler)
        })
    }

    function runAction(actionClass, needsConfirmation, needsSelection, confirmationMessage){
        var selected = getSelectedRowsIds()

        if (needsSelection == 1 && selected.length == 0){
            return alert("{!! __("thrust::messages.noRowsSelected") !!}")
        }

        if (! needsConfirmation || confirm(confirmationMessage)){
            doAction(actionClass, selected)
        }
    }

    function doAction(actionClass, selected){
        document.getElementById('actions-loading').style.display = 'block'
        $.post("{{ route('thrust.actions.perform', [$resourceName]) }}", {
            "_token": "{{ csrf_token() }}",
            "action" : actionClass,
            "ids" : selected,
            "search": searching,
        }).done(function(data){
            document.getElementById('actions-loading').style.display = 'none'
            if (data["responseAsPopup"]){
                $('#popup').popup('show')
                $("#popupContent").html(data["message"])
            } else {
                showMessage(data["message"])
            }
            if (data["shouldReload"]) {
                location.reload()
            }
        }).fail(function(){
            showMessage("Something went wrong")
        })
    }

    function getSelectedRowsIds(){
        return [...document.querySelectorAll('input[name^=selected]:checked')]
            .map(elem => elem.getAttribute("meta:id"))
    }

    function toggleSelectAll(checkbox){
        [...document.querySelectorAll('input[name^=selected]')]
            .forEach(elem => checkbox.checked
                ? elem.checked = true
                : elem.checked = false
            )
    }

    registerActionPopupListeners()

    let searching = 0
    @if($resource::$searchResource)
        window.addEventListener('thrust.searchStarted', () => {
            searching = 1
            fetch("{{ route('thrust.actions.index', ['resourceName' => $resourceName, 'search' => true]) }}").then(response => {
                response.text().then(html => {
                    document.getElementById('thrust-resource-actions').innerHTML = html
                    registerActionPopupListeners()
                })
            })
        })

        window.addEventListener('thrust.searchEnded', () => {
            searching = 0
            fetch("{{ route('thrust.actions.index', ['resourceName' => $resourceName]) }}").then(response => {
                response.text().then(html => {
                    document.getElementById('thrust-resource-actions').innerHTML = html
                    registerActionPopupListeners()
                })
            })
        })
    @endif
</script>
