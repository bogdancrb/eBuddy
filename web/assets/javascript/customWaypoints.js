var InitWaypoint = function(elemName, elemNameEnable, offset, scrollTopCorrection) {
    this.elemName = elemName;
    this.elemNameEnable = elemNameEnable;
    this.offset = offset;
    this.scrollTopCorrection = scrollTopCorrection;
    this.waypointEnabled = true;
};

InitWaypoint.prototype = function() {
    var handleWaypoint = function() {
        var thisObj = this;
        var waypoint = new Waypoint({
            element: document.getElementById(thisObj.elemName),
            handler: function (direction) {
                if (thisObj.waypointEnabled) {
                    $('html, body').animate({
                        scrollTop: $("#" + thisObj.elemName).offset().top - thisObj.scrollTopCorrection
                    },500);

                    thisObj.waypointEnabled = false;
                }
            },
            offset: thisObj.offset
        });
    },
    processEnableWaypoint = function() {
        var thisObj = this;
        var waypointAboutEnable = new Waypoint({
            element: document.getElementById(thisObj.elemNameEnable),
            handler: function(direction) {
                thisObj.waypointEnabled = direction == 'down';
            },
            offset: '30%'
        });
    };

    return {
        handleWaypoint: handleWaypoint,
        processEnableWaypoint: processEnableWaypoint
    };
}();