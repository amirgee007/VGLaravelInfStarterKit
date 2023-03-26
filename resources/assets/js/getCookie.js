"use strict";
var page = require('webpage').create()
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36'
page.open('https://www.fruugo.us/', function(status) {
    if(status === "success") {
        setInterval(function() {
            var bodytxt = page.evaluate(function() {
                if(document.title != 'Just a moment...') {
                    return true
                }
                return false
            });
            if(bodytxt) {
                for(var i in page.cookies) {
                    console.log(page.cookies[i].name+"="+page.cookies[i].value);
                }
                phantom.exit();
            }
        },1000)
    }
});
page.onError = function(msg, trace) {

};