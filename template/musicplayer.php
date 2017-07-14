
<!DOCTYPE HTML>
<html>
   <head>
      <title>Orgelman</title>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      
      <link rel="stylesheet" href="//cdn.orgelman.systems/bootstrap/css/bootstrap.css">
      <link rel="stylesheet" href="//cdn.orgelman.systems/bootstrap/buttons.css" />
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      
      
      <link       rel="stylesheet"                                                  href="//cdn.orgelman.systems/font-awesome/css/font-awesome.css" />
      <link        rel="stylesheet"                                                  href="//zeroseven.orgelman.systems/js/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.min.css" />
      <link        rel="stylesheet"                                                  href="player.css" />
      <script                                                                        src="../src/jquery.jplayer.js"></script>
      <script                                                                        src="//zeroseven.orgelman.systems/js/jPlayer/dist/add-on/jplayer.playlist.min.js"></script>
      <style>
      
#collapseOne #player .jp-volume-bar-value, #collapseOne #player .jp-volume-bar-value {
   background-color: #00b4ff;
}
#collapseOne #player .jp-play-bar {
   border-right: 5px solid #00b4ff;
}
         #collapseOne #player button .fa, .playMusic .fa {
            color:  #00b4ff;
         }
         
      </style>
   </head>
   <body>
      
               <p>
                  <ul style="list-style:none;">
                     <li><a class="playMusic 1orgelman" data-class="1orgelman" data-title="Orgelman @ V&#228;rldens Fest -17 (170603)" data-alias="1orgelman" data-artist="DJ Orgelman" data-image="" data-music="mp3.php" data-id="AB323BF9CD558FF6827175DB1CBE577A" title="Orgelman @ V&#228;rldens Fest -17 (170603)" rel="nofollow"><i class="playButtons fa fa-play textprimary media5966707179ace"></i> Orgelman @ V&#228;rldens Fest -17 (170603)</a></li>
                  </ul>
               </p>
      <div class="panel-group no-print" id="toggleFoot" style="display:none;z-index:100; margin:0;">
         <div class="panel panel-default" style="margin: 0;padding: 0;width: 100%;border-radius: 0;">
            <div class="panel-heading backgroundprimary">
               <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#toggleFoot" class="redirect" id="toggleFooter">
                     <i class="fa fa-arrow-up"></i>
                 </a>
              </h4>
           </div>
            <script>
               $("#toggleFooter").click(function() {
                  $("#collapseOne").collapse("toggle");
               });
           </script>
            <div id="collapseOne" class="panel-collapse collapse">
               <div class="panel-body">
                  <div id="player">
                     <script>
                        var jQueryScriptOutputted59666f378cd5b = false;
                        function initJQuery59666f378cd5b() {
                           if(typeof(jQuery) == "undefined") {
                              if(!jQueryScriptOutputted59666f378cd5b) {
                                 jQueryScriptOutputted59666f378cd5b = true;
                                 console.log("Load jQuery");
                                 document.write("<scr" + "ipt type='text/javascript' src='http://83.249.121.179:50050/broadsword/js/jquery-3.1.1.min.js'></scr" + "ipt>");
                              }
                              setTimeout("initJQuery59666f378cd5b()", 50);
                           }else{
                              console.log("jQuery loaded");
                              $(document).ready(function(){ 
                                 var myPlaylist = new jPlayerPlaylist({
                                    jPlayer: "#jquery_jplayer_N",
                                    cssSelectorAncestor: "#jp_container_N"
                                 }, [
                                 ], {
                                    playlistOptions: {
                                       enableRemoveControls: true
                                    },
                                    swfPath: "js/jPlayer/dist/jplayer",
                                    supplied: "webmv, ogv, m4v, oga, mp3",
                                    useStateClassSkin: true,
                                    autoBlur: false,
                                    smoothPlayBar: true,
                                    keyEnabled: false,
                                    audioFullScreen: false,
                                    errorAlerts: true,
                                    warningAlerts: true
                                 });
                                 $(document).on("click", ".playMusic", function(event){
                                    console.log("180 play music");
                                    $("#toggleFoot").show();
                                    event.preventDefault();
                                    var mp3 = false;
                                    if(!$(this).hasClass("playing")){
                                       console.log("Playing music: " + $(this).attr("data-title"));
                                       if(!$("#collapseOne").hasClass("in")){
                                          $("#toggleFooter").click();
                                       }
                                       if(!mp3){
                                          $('html, body').animate({scrollTop: $("#toggleFooter").offset().top}, 500);
                                          if($(this).attr("data-alias") == $(".jp-alias").attr("data-alias")) {
                                             myPlaylist.play();
                                             console.log("193 play");
                                          } else {
                                             console.log("195 add song");
                                             myPlaylist.add({
                                                id       : $(this).attr("data-id"),
                                                alias    : $(this).attr("data-alias"),
                                                class    : $(this).attr("data-class"),
                                                title    : $(this).attr("data-title"),
                                                artist   : $(this).attr("data-artist"),
                                                mp3      : $(this).attr("data-music"),
                                                img      : $(this).attr("data-image")
                                             }, true);
                                          }
                                       }
                                       $(".jp-playlist li").each(function(){
                                          if(!$(this).hasClass("jp-playlist-current")) {
                                             $(this).remove();
                                          }
                                       });
                                    } else {
                                       console.log("213 pause");
                                       myPlaylist.pause();
                                       $(".playButtons").removeClass("fa-play").removeClass("fa-pause").addClass("fa-play");
                                       $(this).removeClass("playing");
                                    }
                                 });
                                 $("#jquery_jplayer_N").bind($.jPlayer.event.play, function (event) {
                                    var current = myPlaylist.current,
                                       playlist = myPlaylist.playlist;
                                    $.each(playlist, function (index, obj) {
                                       if((typeof(obj.id) !== "undefined") && (obj.id!="")){
                                          isPlaying(obj);
                                       }
                                    });
                                 });
                                 $("#jquery_jplayer_N").bind($.jPlayer.event.pause, function (event) {
                                    console.log("229 pause");
                                    myPlaylist.pause();
                                    $(".playButtons").removeClass("fa-play").removeClass("fa-pause").addClass("fa-play");
                                    $("a").removeClass("playing");
                                 });
                                 $("#jquery_jplayer_N").bind($.jPlayer.event.seeking, function(event){
                                    if($(".jp-title span").length <= 0){
                                       $(".jp-load").show();
                                    }
                                 });
                                 $("#jquery_jplayer_N").bind($.jPlayer.event.seeked, function(event){
                                    $(".jp-load").hide();
                                 });
                                 $("#jquery_jplayer_N").bind($.jPlayer.event.progress, function (event){
                                    if (event.jPlayer.status.seekPercent === 100){
                                       $(".jp-load").hide();
                                    } else {
                                       $(".jp-load").show();
                                    }
                                 });
                              });
                           }
                        }
         function isPlaying(obj) {
            console.log("253 is playing");
            if($("#toggleFoot:visible")) {
               $(".playMusic i").removeClass("fa-play").removeClass("fa-pause").addClass("fa-play");
               if((typeof obj !== "undefined") && (obj != "")) {
                  console.log(obj);
                  $("a.playMusic").removeClass("playing");
                  $("a.playMusic."+obj.class+" i").removeClass("fa-play").addClass("fa-pause");
                  $("a.playMusic."+obj.class).addClass("playing");
                  $("#jp-title").text(obj.title);
                  $("#jp-artist").text(obj.artist);
                  $(".background").css("background-image","url("+obj.img+")");
                  $(".jp-alias").attr("href",obj.alias);
                  $(".jp-alias").attr("data-title",obj.title).attr("data-artist",obj.artist).attr("data-alias",obj.alias).attr("data-class",obj.class);
               } else if($("#jp_container_N").hasClass("jp-state-playing")){
                  $("a.playMusic").removeClass("playing");
                  $("a.playMusic."+$(".jp-alias").attr("data-class")+" i").removeClass("fa-play").addClass("fa-pause");
                  $("a.playMusic."+$(".jp-alias").attr("data-class")).addClass("playing");
               }
            }
         }
                        initJQuery59666f378cd5b();
                    </script>
                     <div id="jp_container_N" class="jp-video jp-video-270p no-print" role="application" aria-label="media player">
                        <div class="jp-type-playlist">
                           <div id="jquery_jplayer_N" class="jp-jplayer"></div>
                           <div class="jp-gui">
                              <div class="jp-video-play">
                             </div>
                              <div class="jp-interface">
                                 <div class="background">
                                </div>
                                 <div class="foreground">
                                    <div class="clearfix">
                                        <div class="jp-progress">
                                          <div class="jp-seek-bar">
                                             <div class="jp-play-bar"></div>
                                         </div>
                                      </div>
                                       <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                                       <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                                   </div>
                                    <div class="clearfix">
                                       <div class="jp-controls-holder">
                                          <div class="jp-controls">
                                             <button class="jp-load"><i class="fa fa-spin fa-circle-o-notch"></i></button>
                                             <button class="jp-play" role="button" tabindex="0"><i class="fa fa-play"></i></button>
                                             <button class="jp-pause" role="button" tabindex="0" style="display:none;"><i class="fa fa-pause"></i></button>
                                             <button class="jp-stop" role="button" tabindex="0"><i class="fa fa-stop"></i></button>
                                             <a style="display:none;" class="jp-alias" data-id="66B3229B6C195395D14413B9C94EC79C"></a>
                                         </div>
                                          <div class="jp-volume-controls backgroundprimary">
                                             <button class="jp-mute" role="button" tabindex="0"><i class="fa fa-volume-off"></i></button>
                                             <button class="jp-volume-max" role="button" tabindex="0"><i class="fa fa-volume-up"></i></button>
                                             <div class="jp-volume-bar">
                                                <div class="jp-volume-bar-value"></div>
                                            </div>
                                         </div>
                                      </div>
                                   </div>
                                    <div class="clearfix">
                                       <div class="details">
                                          <div id="jp-title" aria-label="title">&nbsp;</div>
                                          <div id="jp-artist" aria-label="artist">&nbsp;</div>
                                      </div>
                                   </div>
                                </div>
                             </div>
                          </div>
                           <div class="jp-playlist">
                              <ul>
                                 <li>&nbsp;</li>
                             </ul>
                          </div>
                           <div class="jp-no-solution">
                              <span>Update</span>
                              <a href="http://get.adobe.com/flashplayer/" onClick="trackLink('http://get.adobe.com/flashplayer/')" data-id="40DF69D6CDCFBC26F99831D96AF485AB" title="Flash plugin" rel="nofollow">Flash plugin</a>.
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
      <script>
         $("#collapseOne").on("show.bs.collapse", function () {
            console.log("339 Open footer");
            $(".panel-heading").addClass("open").animate({
               backgroundColor: "#515151"
            }, 500);
         });
         $("#collapseOne").on("hide.bs.collapse", function () {
            console.log("345 close footer");
            $(".panel-heading").removeClass("open").animate({
               backgroundColor: "#00B4FF"
            }, 500);
         });
     </script>
   </body>
</html>
