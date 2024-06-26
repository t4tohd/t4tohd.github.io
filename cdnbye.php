<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://cdn.jsdelivr.net/" crossorigin>
    <title>CDNBye Clappr Demo</title>
    <script src="https://cdn.jsdelivr.net/npm/@clappr/player@0.4.7/dist/clappr.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@0.3.0/dist/level-selector.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swarmcloud-hls@latest/dist/p2p-engine.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swarmcloud-hls@latest/dist/clappr-p2p-plugin.min.js"></script>
	<style>
body {
margin: 0px;
background-color: #000;			
overflow: hidden;
}
p { 
   color: #fff; 
  } 
  .embed-responsive {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style>
</head>
<body>
<div id="player" class="embed-responsive"></div>
<h3>download info:</h3>
<p id="info"></p>
<script>
    const isHlsjsSupported = Clappr.HLS.HLSJS.isSupported
    Clappr.HLS.HLSJS.isSupported = function() {
        if (document.createElement('video').canPlayType('application/vnd.apple.mpegurl')
            && /Safari/.test(navigator.userAgent)
            && !/Chrome/.test(navigator.userAgent)) {
            return false
        }
        return isHlsjsSupported()
    }
    var p2pConfig = {
       
        swFile: 'sw.js',
        live: true,  
        getStats: function (totalP2PDownloaded, totalP2PUploaded, totalHTTPDownloaded) {
            var total = totalHTTPDownloaded + totalP2PDownloaded;
            document.querySelector('#info').innerText = `p2p ratio: ${Math.round(totalP2PDownloaded/total*100)}%, saved traffic: ${totalP2PDownloaded}KB, uploaded: ${totalP2PUploaded}KB`;
        },
        trackerZone: 'us',   
    }
    if (!Clappr.HLS.HLSJS.isSupported()) {
        new P2pEngineHls.ServiceWorkerEngine(p2pConfig)
    }
    P2PEngineHls.tryRegisterServiceWorker(p2pConfig).then(() => {
        var player = new Clappr.Player(
            {
                source: "<?php echo $_GET["url"]; ?>",
                parentId: "#player",
                autoPlay: true,
				width: '100%',
				height: '100%',
				mediacontrol: {seekbar: "#FFF", buttons: "#FFF"},
                plugins: [LevelSelector, SwarmCloudClapprPlugin],
                playback: {
                    hlsjsConfig: {
                        maxBufferSize: 5,      
                        maxBufferLength: 12,     
                        liveSyncDurationCount: 6,   
                        p2pConfig
                    }
                }
            });
    })

</script>
</body>
</html>