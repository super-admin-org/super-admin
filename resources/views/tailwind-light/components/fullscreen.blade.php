<a href="javascript:void(0);" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100/60 hover:text-primary-600 transition-colors nav-fullscreen" title="Fullscreen">
    <i class="icon-expand-arrows-alt text-sm"></i><i class="icon-compress-arrows-alt text-sm hidden"></i>
</a>

<script>
    function launchFullscreen(element) {
        document.querySelector(".nav-fullscreen .icon-compress-arrows-alt").classList.remove("hidden");
        document.querySelector(".nav-fullscreen .icon-expand-arrows-alt").classList.add("hidden");

        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if(element.msRequestFullscreen){
            element.msRequestFullscreen();
        } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullScreen();
        }
    }

    function exitFullscreen() {
        document.querySelector(".nav-fullscreen .icon-compress-arrows-alt").classList.add("hidden");
        document.querySelector(".nav-fullscreen .icon-expand-arrows-alt").classList.remove("hidden");

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }

    document.querySelector('.nav-fullscreen').addEventListener("click",function () {
        if (document.fullscreenElement) {
            exitFullscreen();
        } else {
            launchFullscreen(document.body)
        }
    });
</script>
