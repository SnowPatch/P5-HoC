window.addEventListener("load", function () {
    var bokse = document.getElementsByClassName("bokse");
    for (var i = 0; i < bokse.length; i++) {
        bokse[i].addEventListener("mouseover", function () {
            var tooltip = this.getElementsByClassName("tooltip")[0];
            tooltip.removeAttribute("style");
        });
        bokse[i].addEventListener("mouseout", function () {
            var tooltip = this.getElementsByClassName("tooltip")[0];
            tooltip.style.display = "none";
        });
    }
});
