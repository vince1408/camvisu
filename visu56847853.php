<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />

<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<h2> Last 10 min - <a href="historyip.txt">Histo</a> - <a href="used65656673.php">Used</a> - <a href="?all=y">All</a> - <a href="/caamrecord">Vids</a></h2> 
<form action="" method="GET">
<div class="input-append"><span style="color: white;">Il y a</span>
 <select name="s" class="input-small">
<?php
// no error
error_reporting(E_ERROR);
	for ($i = 600; $i <= 86400; $i+=600) {
?>
	<option <?php if (@$_GET['s']==$i){ echo "selected";}?> value="<?php echo $i ?>"><?php echo gmdate("H:i", $i);?></option>
<?php
	}
?>
 </select>
 <input class="btn-success" type="submit" value="OK" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"><?php echo date('d-m-Y');?></span><span class="label label-primary"><?php echo date('H:i:s');?></span>
 </div>
</form>
<br/>
<div id="links">
<?php

// echo phpinfo();

$i=1;
chdir('snap');
if ($handle = opendir('.')) {
	// parcours du dossier pour les mettre dans un tableau avec date de modif
	while (false !== ($file = readdir($handle))) {
		if (preg_match("/.jpg/", $file)){
			$files[filemtime($file)] = $file;
		}
	}
    closedir($handle);
	// classe par date de modif
    ksort($files);
	
	
	// Parcours du dossier
    foreach($files as $entry) {
        //if (preg_match("/SDAlarm_/", $entry)){
			// Si correspond a un fichier de la cam et plus vieux que x sec, alors traitement
			// Exemple : Schedule_20171217-172420.jpg 
			$info = new SplFileInfo($entry);
			// Pour afficher la date et l'heure en guise de titre (à ma guise lol)
			$tyear = substr($entry, -19, 4);
			$tmonth = substr($entry, -15, 2);
			$tday = substr($entry, -13, 2);
			$thour = substr($entry, -10, 2);
			$tmin = substr($entry, -8, 2);
			$ts = substr($entry, -6, 2);
			$mytitle = $tday."-".$tmonth."-".$tyear." - ".$thour.":".$tmin.":".$ts;
			if (isset($_GET['s'])) {
				$start=intval($_GET['s']);
				if((time()-$info->getMTime()<$start)&&(time()-$info->getMTime()>($start-600))){
				?>
				<a class="fancybox-vince" href="snap/<?php echo $entry; ?>" rel="group1" title="<?php echo $mytitle; ?>" data-fancybox-group="button">
					<img  src="snap/<?php echo $entry; ?>" alt="<?php echo $i; ?>" width="75">
				</a>
				<?php
				$i++;
				}
			}elseif (empty($_GET['all'])){  // si pas de début et fin on fait les 10 dernières minutes
				if (time() - $info->getMTime() < 600 ){
				?>
				<a class="fancybox-vince" href="snap/<?php echo $entry; ?>" rel="group1" title="<?php echo $mytitle; ?>" data-fancybox-group="button">
					<img src="snap/<?php echo $entry; ?>" alt="<?php echo $i; ?>" width="75">
				</a>
				<?php
				$i++;
				}
			}elseif ($_GET['all']="y"){ // on affiche la derniere heure au calme
				if (time() - $info->getMTime() < 3600 ){
				?>
				<a class="fancybox-vince" href="snap/<?php echo $entry; ?>" rel="group1" title="<?php echo $mytitle; ?>" data-fancybox-group="button">
					<img src="snap/<?php echo $entry; ?>" alt="<?php echo $i; ?>" width="75">
				</a>
				<?php
				$i++;
				}
			}
			
		
        
		
    }
}

//Envoi rapport
// if ($i > 0){
// $headers  = 'MIME-Version: 1.0' . "\r\n";
// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// mail('toto@gmail.com', 'Purge FTP','OK, '.$i.' fichiers supprimés.',$headers);
// echo "OK Rapport";
// }else{	
// echo "Pas de rapport";
// }

// Log des IP connectés
// Chemin vers fichier texte
$file ="../../historyip.txt";
$log = date("Y-m-d H:i:s") . " " .$_SERVER['REMOTE_ADDR'] . "\r\n";
// Ouverture en mode écriture
$handle=(fopen("$file",'a'));
fwrite($handle,$log);
fclose($handle);
?>
</div>
<br/>
<textarea  id="mytextarea" class="form-control" rows="5" style="margin: 0px -14px 0px 0px; width: 1155px; height: 125px;color: white;background-color: #222222;">
<?php
// lecture du fichier
$handle = fopen("$file", 'r');
	while (!feof($handle))
	{
		/*On lit la ligne courante*/
		$buffer = fgets($handle);
		/*On l'affiche*/
		echo $buffer;
	}
fclose($handle);
?>
</textarea>
</div>
	<script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script>
$(document).ready(function(){
	$('.fancybox').fancybox();
	$('.fancybox-vince').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',

		prevEffect : 'none',
		nextEffect : 'none',

		closeBtn  : false,
		
		afterLoad : function() {
			this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		}
	});
	$('.fancybox-buttons').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',

		prevEffect : 'none',
		nextEffect : 'none',

		closeBtn  : false,

		helpers : {
			title : {
				type : 'inside'
			},
			buttons	: {}
		},
		afterLoad : function() {
			this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		}
	});
    $('#mytextarea').scrollTop($('#mytextarea')[0].scrollHeight);
});
</script>
</body>
</html>
