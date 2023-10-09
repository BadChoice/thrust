/**
 * Simple AJAX Jquery searcher.
 * This loads an URL with the text to search at the end such as http://callbackurl/texttosearch
 * It acceptes a couple of options
 *  - resultsDiv the div where the results will be loaded // default '#results'
 *  - allDiv the div where all is shown so it can be hidden when showing results // default 'all'
 *  - minChars minimum chars to start searching // default 3
 */

(function($) {
    $.fn.searcher = function( callbackUrl , options ) {

        const settings = $.extend({
            resultsDiv   : 'results',
            allDiv       : 'all',
            minChars     : 3,
            onFound      : null,
            updateAddressBar : true
        }, options)

        let timeout = null
        this.on('keyup',function(){
            clearTimeout(timeout)
            const self = this
            timeout = setTimeout(function () {
                const searchString = self.value

                if (searchString.length >= settings.minChars) {
                    if (settings.updateAddressBar) {
                        window.history.pushState("", "", '?search=' + searchString)
                    }
                    document.getElementById(settings.resultsDiv).style.display = 'block'
                    document.getElementById(settings.allDiv).style.display = 'none'
                    fetch(callbackUrl + self.value.replace(new RegExp(' ', 'g'), '%20')).then(response => {
                        response.text().then(html => {
                            document.getElementById(settings.resultsDiv).innerHTML = html
                        })
                    })
                    window.dispatchEvent(new Event('thrust.searchStarted'))
                } else {
                    if (settings.updateAddressBar) {
                        window.history.pushState("", "", "?")
                    }

                    document.getElementById(settings.resultsDiv).style.display = 'none'
                    document.getElementById(settings.allDiv).style.display = 'block'
                    window.dispatchEvent(new Event('thrust.searchEnded'))
                }
            }, 300)
        })
    }
}(jQuery))

function RVAjaxSelect2(url, options) {
    this.options = {
        width: '300px',
        dropdownAutoWidth: true,
        ajax: {
            url: url,
            dataType: 'json',
            type: "GET",
            quietMillis: 300,
            delay: 250,
            cache: true,
            data: function (params) {
                return {
                    search: params.term
                }
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id:     item.id,
                            value:  item.id,
                            text:   item.name
                        }
                    })
                }
            }
        }
    }
    $.extend(this.options, options)

    this.show = function(itemId) {
        return $(itemId).select2(this.options)
    }
}
