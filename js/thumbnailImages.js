function thumbnailImg(img, newWidth) {
    var newHeight, thumb;
    if (img && img.tagName.toLowerCase() === "img") {
        newHeight = Math.round(newWidth/img.width * img.height);
        thumb = document.createElement('a');
        thumb.href = img.src;
        thumb.target = "_blank"
        thumb.style.display = "inline-block";
        thumb.style.position = "relative";
        thumb.style.width = newWidth.toString() + "px";
        thumb.style.height = newHeight.toString() + "px";
        thumb.style.backgroundImage = "url(" + img.src + ")";
        thumb.style.backgroundSize = "100%";
        thumb.innerHTML = '<span style="background-color: black;color: white;position:absolute;bottom:0;right:0;">clique para ampliar</span>';
        img.parentNode.replaceChild(thumb,img);
    }
}
function thumbnailImgsFromElement(elem, width, wLimit) {
    var imgs = elem.getElementsByTagName("img"),
    i, n = imgs.length;
    for (i = 0; i < n; i += 1) {
        if (imgs[i].width > wLimit) {
            thumbnailImg(imgs[i], width);
        }
    }
}
function thumbnailImgsFromClass(className, width, wLimit) {
    var elems = document.getElementsByClassName(className),
    i, n = elems.length;
    for (i = 0; i < n; i += 1) {
        thumbnailImgsFromElement(elems[i], width, wLimit);
    }
}
