<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: progress_bar.class.php,v 1.1.2.1 2011-07-21 08:51:35 gueluneau Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

class progress_bar{
	 
	//Constructeur.	 $text
	function progress_bar($text='',$count=0,$pas=1) {
		
		$this->show();
		if($text)$this->set_text($text);
		$this->count=$count;
		$this->pas=$pas;
		$this->nb_progress_call=0;
		$this->finish=0;
	}
		
	function show(){
        print "
	        <div class='row' id='progress_bar' style='text-align:center; width:80%; border: 1px solid #000000; padding: 4px;'>
	            <div style='text-align:left; width:100%; height:16px;'>
	                <img id='progress' src='images/jauge.png' style='width:1px; height:16px'/>
	            </div>
	            <div style='text-align:center'>
	                <span id='progress_text'></span>&nbsp;
	                <span id='progress_percent'></span>
	            </div>
	        </div>";
        flush();
    }
   
    function init() {
        print "<script>document.getElementById('progress').src='images/jauge.png'</script>";
        flush();
    }
   
    function set_percent($percent) {
    	// on envoit des espaces en plus pour que flush() vide bien le buffer (>256)
        print "
	        <script>document.getElementById('progress').style.width='$percent%';
	                document.getElementById('progress_percent').innerHTML='$percent%';
	        </script> 
	                                                                                                                                                                          
	                                                                                                                                                                          
	                                                                                                                                                                          
	                                                                                                                                                                          
	                                                                                                                                                                          
	        
	                                                                                                                                                                          
	   ";
       flush();
    }
    
    function progress() {
    	if($this->finish) return;    	
    	$this->nb_progress_call++;
    	
    	$percent=intval(100*($this->nb_progress_call/$this->count));
    	
    	if($percent>=100){
    		$this->set_percent(100);
    		$this->finish=1;
    	}    	
    	if(!($this->nb_progress_call%$this->pas)){    		 		
	        $this->set_percent($percent);
    	
    	}
    } 
     
    function set_text($text){
        global $charset;
        print "<script>document.getElementById('progress_text').innerHTML='".htmlentities($text,ENT_QUOTES,$charset)."';</script>";
        flush();
    }
    
    function hide(){
        print "<script>var obj=document.getElementById('progress_bar'); obj.parentNode.removeChild(obj)</script>";
        flush();
    }	
					
}
?>