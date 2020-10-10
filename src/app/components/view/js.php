<script src="/js/mdb/mdb.min.js"></script>
<script src="/js/cleditor.min.js"></script>

<!-- Sidenav -->
<script type="text/javascript">
    const sidenav = document.getElementById("bmt-sidenav");
    const instance = mdb.Sidenav.getInstance(sidenav);

    let innerWidth = null;

    const setMode = (e) => {
        // Check necessary for Android devices
        if (window.innerWidth === innerWidth) {
        return;
        }

        innerWidth = window.innerWidth;

        if (window.innerWidth < 1400) {
            // instance.changeMode("over");
            instance.changeMode("side");
            instance.hide();
            var icon = document.getElementById('slim-toggler-icon');
            icon.classList.toggle("fa-angle-left");
            icon.classList.toggle("fa-angle-right");
        } else {
            instance.changeMode("side");
            instance.show();
        }
    };

    setMode();

    // Event listeners
    window.addEventListener("resize", setMode);
    
    document.getElementById('slim-toggler').addEventListener('click', () => {

        if (window.innerWidth < 1400) {
            instance.toggle();
        } else {
            instance.toggleSlim();
        }
        var icon = document.getElementById('slim-toggler-icon');
        icon.classList.toggle("fa-angle-left");
        icon.classList.toggle("fa-angle-right");
    });

    //debounce
    function debounce(func, wait, immediate=false) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };
</script>
