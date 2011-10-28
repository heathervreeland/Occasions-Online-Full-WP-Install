<h2>Build your Occasions Magazine Badge</h2>

<script type="text/javascript">

	function build_banner() {
		var bsize = $("input[name='badge_size']:checked", '#badge_form').val();
		var twidth = (600 - 40 - bsize);
		var theight = ((bsize/125) * 120);
		var btype = $("input[name='badge_type']:checked", '#badge_form').val();
		var bcat = $("#badge_cat").val();
		var badgedata = '<div style="text-align: center; border: 1px solid #DDD; width: ' + bsize + 'px;"><a href="http://www.atlantaoccasions.com/" target="_blank"><img style="display: block;" src="http://www.atlantaoccasions.com/media/images/occasions_badge_' + bsize + 'w.jpg" alt="Occasions Magazine ' + btype + ' Atlanta ' + bcat + '" /><div style="text-align: center; font-family: arial; font-size: 12px; padding: 4px 0px;">' + btype + ' Atlanta ' + bcat + '</div></a></div>';
		
		$("#badgelive").html(badgedata);
		$("#badgecode").width(twidth);
		$("#badgecode").height(theight);
		$("#badgecode").text($("#badgelive").html());
	}
</script>

<p>Select the <b>banner type</b>:</p>
<form id="badge_form">
<p style="margin-left: 25px;">
<input onclick="build_banner();" type="radio" name="badge_type" id="badge_typep" value="Preferred" checked /><label for="badge_typep">Preferred Vendor</label>&nbsp;&nbsp;&nbsp;<input onclick="build_banner();" type="radio" name="badge_type" id="badge_typef" value="Featured" /><label for="badge_typef">Featured Vendor</label>
</p>

<p>Select the <b>banner size</b>:</p>
<p style="margin-left: 25px;">
<input onclick="build_banner();" type="radio" name="badge_size" id="badge_size125" value="125" checked /><label for="badge_size125">125 pixels wide</label>&nbsp;&nbsp;&nbsp;
<input onclick="build_banner();" type="radio" name="badge_size" id="badge_size200" value="200" /><label for="badge_size200">200 pixels wide</label>&nbsp;&nbsp;&nbsp;
<input onclick="build_banner();" type="radio" name="badge_size" id="badge_size250" value="250" /><label for="badge_size250">250 pixels wide</label>&nbsp;&nbsp;&nbsp;
<input onclick="build_banner();" type="radio" name="badge_size" id="badge_size300" value="300" /><label for="badge_size300">300 pixels wide</label>
</p>

<p>Select your <b>company type</b>:</p>
<p style="margin: 0px 0px 20px 25px;">
<select name="badge_cat" id="badge_cat" class="vendor_select" onchange="build_banner();">
	<option value="Venue" selected>Venue</option>
	<option value="Rehearsal Dinner Location">Rehearsal Dinner Location</option>
	<option value="Photographer">Photographer</option>
	<option value="Band">Band</option>
	<option value="Registry">Registry</option>
	<option value="Cake Designer">Cake Designer</option>
	<option value="Caterer">Caterer</option>
	<option value="Ceremony Musician">Ceremony Musician</option>
	<option value="DJ">DJ</option>
	<option value="Rental Company">Rental Company</option>
	<option value="Planner">Planner</option>
	<option value="Favor">Favor</option>
	<option value="Florals">Florals</option>
	<option value="Hair & Makeup">Hair & Makeup</option>
	<option value="Hotel">Hotel</option>
	<option value="Inviations">Inviations</option>
	<option value="Lighting">Lighting</option>
	<option value="Photobooth">Photobooth</option>
	<option value="Resources">Resources</option>
	<option value="Rings">Rings</option>
	<option value="Limos">Limos</option>
	<option value="Tuxedos">Tuxedos</option>
	<option value="Videographer">Videographer</option>
	<option value="Dresses">Dresses</option>
</select>
</p>

<div id="badgelive" style="float: left; padding-right: 20px;">
	<div style="text-align: center; border: 1px solid #DDD; width: 125px;"><a href="http://www.atlantaoccasions.com/" target="_blank"><img style="display: block;" src="http://www.atlantaoccasions.com/media/images/occasions_badge_125w.jpg" alt="Occasions Magazine Preferred Vendor" /><div style="text-align: center; font-family: arial; font-size: 12px; padding: 4px 0px;">Featured Atlanta Photographer</div></a></div>
</div>
<div style="margin: 0px; text-align: left;">
	<p>Paste the following text into your web page.<br /><a href="#" onclick="$('#badgecode').focus().select(); return false;">Select All Text Below</a></p>
	<div><textarea id="badgecode" onfocus="this.select();" onclick="this.select();" style="font-family: arial; font-size: 12px; width: 435px; height: 120px;" name="txt_comments" rows="7" cols="20" id="txt_comments"><?php echo htmlentities('<div style="text-align: center; border: 1px solid #DDD; width: 104px;"><a href="http://www.atlantaoccasions.com/" target="_blank"><img style="display: block;" src="/media/images/occasions_badge_125.jpg" title="Occasions Magazine Preferred Vendor" /><div style="text-align: center; font-family: arial; font-size: 12px; padding-top: 4px;">Featured Atlanta Photographer</div></a></div>'); ?></textarea></div>
</div>
<div class="clear"></div>
</form>
<script type="text/javascript">
	build_banner();
</script>