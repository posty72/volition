window.onload = function() {
    var windowSize = document.width;
    var container = document.getElementById('content');
    
    container.style.width = windowSize+'px';
}

window.onresize = function() {
    var windowSize = document.width;
    var container = document.getElementById('content');
    
    console.log(windowSize);
    
    container.style.width = (windowSize+'px');
}