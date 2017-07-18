<?php 
// echo $_SERVER['DOCUMENT_ROOT'];exit();
chdir($_SERVER['DOCUMENT_ROOT']);
error_reporting(E_ALL);
if($_POST){
	
	if($_POST['wpinstalled'] != 3){
		$output = shell_exec('wp core download --path='.$_POST['projectname']);		
		if (strpos($output, 'Success') !== false) {
			chdir($_POST['projectname']);
			$output = shell_exec('wp core config --dbname='.$_POST['dbname'].' --dbuser='.$_POST['dbuser'].' --dbpass='.$_POST['dbpass']);
			$output = shell_exec('wp db create');
			$output = shell_exec('wp core install --url='.$_POST['projecturl'].' --title="'.$_POST['projecttitle'].'" --admin_user='.$_POST['adminuser'].' --admin_password='.$_POST['adminpass'].' --admin_email='.$_POST['adminemail'].'');		
		}
		$_POST['wpinstalled'] = 3;
	}	
	if(!empty($_POST['pluginslist']) && $_POST['wpinstalled'] == 3)  {
		chdir($_POST['projectname']);
		foreach ($_POST['pluginslist'] as $plugin) {
			$output = shell_exec('wp plugin install '.$plugin.' --activate');
		}			
	}
}
?>
<html>
<head>
	<title>Install WP</title>
	<link rel="stylesheet" href="style.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
		    $(".srchplgn").click(function() {
				var input = this;
		    	input.disabled = true;
		    	var searchplugin = $('.searchplugin').val();
		    	var searchplugin = searchplugin.replace(/\s/g, '');
		    	var searchplugin = searchplugin.toLowerCase();
		    	var projectname = $('.projectname').val();
		    	$('.loader').addClass('active');
		        $.get("script.php?code=searchplugin&name="+searchplugin+'&dir='+projectname, function(data, status) {
		        	var matches = data.match(/\[(.*?)\]/);
		        	if (matches) {
		        	    var submatch = matches[1];
		        	}
		        	var data = '['+submatch+']';
		        	var data = JSON.parse(data);
		        	for(var i in data)
		            {
		                var slug = data[i].slug;
		                var name = data[i].name;
		                $( ".search_result" ).append('<div class="form_row"><label>'+name+'</label><input type="checkbox" name="pluginslist[]" value="'+slug+'"></div>');
		            }
		            $('.loader').removeClass('active');
		            input.disabled = false;
		        });
		    });
		});
	</script>
</head>
<body>
	<div class="form">
		<form class="installwp" method="POST" >
			<div class="fill_content">
				<div class="form_row">
					<label>Project Name:</label>
					<input type="text" name="projectname" placeholder="Enter Folder Name with path from WWW" value="<?= isset($_POST['projectname']) ? htmlspecialchars($_POST['projectname']) : '' ?>" class="projectname">
				</div>
				<div class="form_row">
					<label>DB Name:</label>
					<input type="text" name="dbname" placeholder="Enter database" value="<?= isset($_POST['dbname']) ? htmlspecialchars($_POST['dbname']) : '' ?>">
				</div>
				<div class="form_row">
					<label>DB User Name:</label>
					<input type="text" name="dbuser" value="root" placeholder="Enter Database username" value="<?= isset($_POST['dbuser']) ? htmlspecialchars($_POST['dbuser']) : '' ?>">
				</div>
				<div class="form_row">
					<label>DB Password:</label>
					<input type="text" name="dbpass" value="root" placeholder="Enter Database password" value="<?= isset($_POST['dbpass']) ? htmlspecialchars($_POST['dbpass']) : '' ?>">
				</div>
				<div class="form_row">
					<label>Project URL:</label>
					<input type="text" name="projecturl" placeholder="Enter Project URL Like : http://192.168.39.26/projectfoldername" value="<?= isset($_POST['projecturl']) ? htmlspecialchars($_POST['projecturl']) : '' ?>">
				</div>
				<div class="form_row">
					<label>Project Title:</label>
					<input type="text" name="projecttitle" placeholder="Enter Project Title" value="<?= isset($_POST['projecttitle']) ? htmlspecialchars($_POST['projecttitle']) : '' ?>">
				</div>
				<div class="form_row">
					<label>Admin Username:</label>
					<input type="text" name="adminuser" placeholder="Enter admin Username" value="<?= isset($_POST['adminuser']) ? htmlspecialchars($_POST['adminuser']) : '' ?>">
				</div>
				<div class="form_row">
					<label>Admin password:</label>
					<input type="text" name="adminpass" placeholder="Enter admin password" value="<?= isset($_POST['adminpass']) ? htmlspecialchars($_POST['adminpass']) : '' ?>">
				</div>
				<div class="form_row">
					<label>Admin Email:</label>
					<input type="text" name="adminemail" placeholder="Enter admin email" value="<?= isset($_POST['adminemail']) ? htmlspecialchars($_POST['adminemail']) : '' ?>">
				</div>
				<input type="hidden" name="wpinstalled" value="<?= isset($_POST['wpinstalled']) ? htmlspecialchars($_POST['wpinstalled']) : '0' ?>">
			</div>
			<?php 
			if(isset($_POST['wpinstalled']) && $_POST['wpinstalled'] == 3 ) {
			?>				
				<div class="plugin_content">
						<label>Select Plugins You want to install</label>
					    <div class="form_row search_plugin">
							<label>Search Plugin</label>
							<input type="text" name="searchplugin" value="" class="searchplugin">
							<input type="button" name="search" value="search" class="srchplgn">
							<div class="loader"></div>
					    </div>
						<div class="form_row">
							<label>Custom Post Type UI</label>
							<input type="checkbox" name="pluginslist[]" value="custom-post-type-ui">
						</div>
						<div class="form_row">
							<label>Contact Form 7</label>
							<input type="checkbox" name="pluginslist[]" value="contact-form-7">
						</div>
						<div class="form_row">
							<label>Advanced Custom Fields</label>
							<input type="checkbox" name="pluginslist[]" value="advanced-custom-fields">
						</div>
						<div class="form_row">
							<label>Simple Custom Post Order</label>
							<input type="checkbox" name="pluginslist[]" value="simple-custom-post-order">
						</div>
						<div class="form_row">
							<label>WP Migrate DB</label>
							<input type="checkbox" name="pluginslist[]" value="wp-migrate-db">
						</div>
						<div class="form_row">
							<label>Simple Image Sizes</label>
							<input type="checkbox" name="pluginslist[]" value="simple-image-sizes">
						</div>
						<div class="form_row">
							<label>WP Mail SMTP by WPForms</label>
							<input type="checkbox" name="pluginslist[]" value="wp-mail-smtp">
					    </div>
					    <div class="search_result"></div>
					    					    
				</div>
				<div class="clear"></div>
			<?php } ?>	
			<div class="form_row submit_btn">
				<input type="submit" value="submit" name="submit">
			</div>
		</form>
	</div>
</body>
</html>