window.addEventListener("load", function () {
    let bokse = document.getElementsByClassName("bokse");
    for (let i = 0; i < bokse.length; i++) {
        bokse[i].addEventListener("mouseover", function () {
            let tooltip = this.getElementsByClassName("tooltip")[0];
            tooltip.removeAttribute("style");
        });
        bokse[i].addEventListener("mouseout", function () {
            let tooltip = this.getElementsByClassName("tooltip")[0];
            tooltip.style.display = "none";
        });
    }
});
