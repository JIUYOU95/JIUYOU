var player = document.getElementById('player');
var audio = document.getElementById('audio');  
var progress = document.getElementById('progress');  
var playpause = document.getElementById("play-pause");  
var volume = document.getElementById("volume");  
audio.controls = false;  
  
    /** 
     * 暂停播放 
     */  
    function togglePlayPause() {  
       if (audio.paused || audio.ended) {  
          playpause.title = "Pause";  
          playpause.innerHTML = '<i class="icon-pause icon-3x"></i>';
          player.style.animation="spin 6s linear infinite"; 
          audio.play();  
       } else {  
          playpause.title = "Play";  
          playpause.innerHTML = '<i class="icon-play icon-3x"></i>'; 
          player.style.animation=""; 
          audio.pause();  
       }  
    }  
 

    function resetPlayer() {  
          audio.currentTime = 0; context.clearRect(0,0,canvas.width,canvas.height);  
      playpause.title = "Play";  
          playpause.innerHTML = '<i class="icon-play"></i>';  
    }  