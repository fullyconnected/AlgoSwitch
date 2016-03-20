/// learn more here   http://www.codediesel.com/javascript/adding-html5-geolocation-to-your-applications/
    var latitude;
    var longitude;
    var accuracy;
    
    function loadLocation() {
    
        if(navigator.geolocation) {
           // document.getElementById("status").innerHTML = "HTML5 Geolocation is supported in your browser.";
          //  document.getElementById("status").style.color = "#1ABC3C";
            
            if($.cookie("posLat")) {
                latitude = $.cookie("posLat");
                longitude = $.cookie("posLon");
                accuracy = $.cookie("posAccuracy");
                //document.getElementById("status").innerHTML = "Location data retrieved from cookies. <a id=\"clear_cookies\" href=\" javascript:clear_cookies();\" style=\"cursor:pointer; margin-left: 15px;\"> clear cookies</a>";
                updateDisplay();
                
            } else {
                navigator.geolocation.getCurrentPosition(
                                    success_handler, 
                                    error_handler, 
                                    {timeout:10000});
            }
        }
    }

    function success_handler(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        accuracy = position.coords.accuracy;
        
        if (!latitude || !longitude) {
            document.getElementById("status").innerHTML = "HTML5 Geolocation supported, but location data is currently unavailable.";
            return;
        }
        
        updateDisplay();
        
        $.cookie("posLat", latitude);
        $.cookie("posLon", longitude);
        $.cookie("posAccuracy", accuracy);
      
    }
    
    function updateDisplay() {

     //   document.getElementById("latitude").innerHTML = latitude;
     //   document.getElementById("longitude").innerHTML = longitude;
     //   document.getElementById("accuracy").innerHTML = accuracy;
    }
    
    
    function error_handler(error) {
        var locationError = '';
        
        switch(error.code){
        case 0:
            locationError = "There was an error while retrieving your location: " + error.message;
            break;
        case 1:
            locationError = "The user prevented this page from retrieving a location.";
            break;
        case 2:
            locationError = "The browser was unable to determine your location: " + error.message;
            break;
        case 3:
            locationError = "The browser timed out before retrieving the location.";
            break;
        }

        document.getElementById("status").innerHTML = locationError;
        document.getElementById("status").style.color = "#D03C02";
    }
    
    function clear_cookies() {
        $.cookie('posLat', null);
        document.getElementById("status").innerHTML = "Cookies cleared.";
    }
    
   