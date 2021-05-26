window.transitionToPage = function(href) {
    document.querySelector('body').style.opacity = 0
    setTimeout(function() {
        window.location.href = href
    }, 500)
}

document.addEventListener('DOMContentLoaded', function(event) {
    document.querySelector('body').style.opacity = 1
})

window.addEventListener( "pageshow", function ( event ) {
    var historyTraversal = event.persisted ||
        ( typeof window.performance != "undefined" &&
            window.performance.getEntriesByType("navigation")[0].type === "back_forward" );
    if ( historyTraversal ) {
        //window.location.reload();
    }
});