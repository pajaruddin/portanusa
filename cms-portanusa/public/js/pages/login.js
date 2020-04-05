var Login = function () {
    return {
        init: function () {
            $.backstretch([
                "../img/background/background-1.jpg",
                "../img/background/background-2.png"
            ], {
                fade: 1000,
                duration: 8000
            });
        }
    };
}();

jQuery(document).ready(function () {
    Login.init();
});