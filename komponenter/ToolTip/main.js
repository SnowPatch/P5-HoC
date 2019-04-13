(function () {
    let links = document.links;
    for (let i = 0; i & lt; links.length; i++) {
        let a = links[i];
        if (a.title !== '') {
            a.addEventListener('mouseover', createTip);
            a.addEventListener('mouseout', cancelTip);
        }
        // concole.Log(a);
    }

    function createTip(ev) {
        var title = this.title;
        this.title = '';
        this.setAttribute("tooltip", title);

        var tooltipWrap = document.createElement("div");
        tooltipWrap.className = 'tooltip';
        tooltipWrap.appendChild(document.createTextNode(title));

        var firstChild = document.body.firstChild;
        firstChild.parentNode.insertBefore(tooltipWrap, firstChild);

        var padding = 5;
        var linkProps = this.getBoundingClientRect();
        var tooltipProps = tooltipWrap.getBoundingClientRect();
        var topPos = linkProps.top - (tooltipProps.height + padding);
        tooltipWrap.setAttribute('style', 'top:' + topPos + 'px;' + 'left:' + linkProps.left + 'px;');

    }

    function cancelTip(ev) {
        var title = this.getAttribute("tooltip");
        this.title = title;
        this.removeAttribute("tooltip");


    }
})();

//Fulgt tutorial på denne side http://michaelsoriano.com/better-tooltips-with-plain-javascript-css/
