function MusuWriter(app) {
  this.appContext = app;
}

var musu;
var steamid = "tsaxjaguirre";

Musubi.ready(function(context) {
    console.log("launching Game Check In.");
    musu = new MusuWriter(context);

    if (musu.appContext.message != null) {
      if (musu.appContext.message.obj != null) {
        var text = musu.appContext.message.obj.data.text;
        console.log("o " + text);
      }
    }
});

