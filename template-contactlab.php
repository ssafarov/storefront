<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Contact Fuel3D Lab
 *
 * @package storefront
 */

get_header();

//sending mail
if(isset($_POST['oid']) && $_POST['oid'] == '00D20000000CmLc')
{
    $uname = sanitize_text_field($_POST['salutation']) . ' ' . sanitize_text_field($_POST['first_name']) . ' ' . sanitize_text_field($_POST['last_name']);
    $ucountry = sanitize_text_field($_POST['country']);
    $organization = sanitize_text_field($_POST['00N20000003PCov']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $msg = sanitize_text_field($_POST['00N20000003PCp5']);

    $to = 'Stephen Crossland <stephen.crossland@fuel-3d.com>';
    $subj = 'Enquiry from Fuel3D Lab contact form';
    $message = 'Message from '. $uname . '<br/>';
    $message .= $ucountry . '<br/>';
    $message .= $organization . '<br/>';
    $message .= $email . '<br/>';
    $message .= $phone . '<br/><br/>';
    $message .= 'Enquiry: '. $msg . '<br/>';

    mail($to, $subj, $message);

?>

<form action='https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8' method='post' name='salesforcefrm'>
<?php
    foreach ($_POST as $a => $b) {
        echo "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>";
    }
?>
</form>
<script language="JavaScript">
    document.salesforcefrm.submit();
</script>
<?php
}

?>

<script type="text/javascript">// <![CDATA[

	var a = Math.ceil(Math.random() * 10);
	var b = Math.ceil(Math.random() * 10);
	var c = a + b;
	function DrawBotBoot()
	{
		document.write("What is "+ a + " + " + b +"? ");
		document.write("<input id='BotBootInput' type='text' maxlength='2' size='2'/>");
	}

	function checkform ( form )
	{
		if (form.first_name.value == "") {
			alert( <?php _e('Please enter your first name.', 'storefront'); ?> );
			form.first_name.focus();
			return false ;
		}
		if (form.last_name.value == "") {
			alert( <?php _e('Please enter your last name.', 'storefront'); ?> );
			form.last_name.focus();
			return false ;
		}
		if (form.country.value == "") {
			alert( <?php _e('Please enter your country.', 'storefront'); ?> );
			form.country.focus();
			return false ;
		}
		if (form.email.value == "") {
			alert( <?php _e('Please enter your email address.', 'storefront'); ?> );
			form.email.focus();
			return false ;
		}
		if (form.email1.value != form.email.value) {
			alert( <?php _e('Please check your email address.', 'storefront'); ?> );
			form.email.focus();
			return false ;
		}

		if (!form.elements['00N20000003PCpK'].checked) {
			alert( <?php _e('Please read and accept our Privacy Policy.', 'storefront'); ?> );
			form.elements['00N20000003PCpK'].focus();
			return false ;
		}

		var d = document.getElementById('BotBootInput').value;
		if (d != c) {
			alert( <?php _e('Please verify you are human.', 'storefront'); ?> );
			form.BotBootInput.focus();
			return false;   }

		return true ;
	}
	// ]]></script>

	<main id="main" class="container site-main" role="main">

		<img style="margin: 0 auto; display:block;" class="alignnone size-full wp-image-1868" width="220" alt="Fuel3D Labs" src="https://www.fuel-3d.com/wp-content/uploads/2014/09/Fuel3D-Labs-Regular-567x214.png"  />
		<hr />
		<form action="#" method="POST" onsubmit="return checkform(this);">
            <input type="hidden" name="oid" value="00D20000000CmLc" />
			<input type="hidden" name="retURL" value="https://www.fuel-3d.com/contact-b2b/thank_you/" />

			<div id="contact_form">
				<div class="cbot leftc">

					<div style="font-size:18px; font-weight:bold; margin:35px auto; width:100%; text-align:left; color:#454849;"><?php _e('Please complete the form below to explore how we can collaborate to develop applications across a diverse range of market sectors', 'storefront'); ?></div>

					<label for="salutation"><?php _e('Title', 'storefront'); ?></label>

					<select id="salutation" name="salutation"><option value=""><?php _e('--None--', 'storefront'); ?></option><option value="Mr."><?php _e('Mr.', 'storefront'); ?></option><option value="Ms."><?php _e('Ms.', 'storefront'); ?></option><option value="Mrs."><?php _e('Mrs.', 'storefront'); ?></option><option value="Dr."><?php _e('Dr.', 'storefront'); ?></option><option value="Prof."><?php _e('Prof.', 'storefront'); ?></option></select>

					<label for="first_name"><?php _e('First Name', 'storefront'); ?></label><input id="first_name" type="text" autocomplete="OFF" maxlength="40" name="first_name" size="20" />

					<label for="last_name"><?php _e('Last Name', 'storefront'); ?></label><input id="last_name" type="text" autocomplete="OFF" maxlength="80" name="last_name" size="20" />

					<label for="organisation"><?php _e('Organisation', 'storefront'); ?></label><input id="00N20000003PCov" type="text" maxlength="200" name="00N20000003PCov" size="20" />

					<label for="country"><?php _e('Country', 'storefront'); ?></label>
					<select name="country" id="country">
						<option value=""><?php _e('Country...', 'storefront'); ?></option>
						<option value="Afganistan"><?php _e('Afghanistan', 'storefront'); ?></option>
						<option value="Albania"><?php _e('Albania', 'storefront'); ?></option>
						<option value="Algeria"><?php _e('Algeria', 'storefront'); ?></option>
						<option value="American Samoa"><?php _e('American Samoa', 'storefront'); ?></option>
						<option value="Andorra"><?php _e('Andorra', 'storefront'); ?></option>
						<option value="Angola"><?php _e('Angola', 'storefront'); ?></option>
						<option value="Anguilla"><?php _e('Anguilla', 'storefront'); ?></option>
						<option value="Antigua &amp; Barbuda"><?php _e('Antigua &amp; Barbuda', 'storefront'); ?></option>
						<option value="Argentina"><?php _e('Argentina', 'storefront'); ?></option>
						<option value="Armenia"><?php _e('Armenia', 'storefront'); ?></option>
						<option value="Aruba"><?php _e('Aruba', 'storefront'); ?></option>
						<option value="Australia"><?php _e('Australia', 'storefront'); ?></option>
						<option value="Austria"><?php _e('Austria', 'storefront'); ?></option>
						<option value="Azerbaijan"><?php _e('Azerbaijan', 'storefront'); ?></option>
						<option value="Bahamas"><?php _e('Bahamas', 'storefront'); ?></option>
						<option value="Bahrain"><?php _e('Bahrain', 'storefront'); ?></option>
						<option value="Bangladesh"><?php _e('Bangladesh', 'storefront'); ?></option>
						<option value="Barbados"><?php _e('Barbados', 'storefront'); ?></option>
						<option value="Belarus"><?php _e('Belarus', 'storefront'); ?></option>
						<option value="Belgium"><?php _e('Belgium', 'storefront'); ?></option>
						<option value="Belize"><?php _e('Belize', 'storefront'); ?></option>
						<option value="Benin"><?php _e('Benin', 'storefront'); ?></option>
						<option value="Bermuda"><?php _e('Bermuda', 'storefront'); ?></option>
						<option value="Bhutan"><?php _e('Bhutan', 'storefront'); ?></option>
						<option value="Bolivia"><?php _e('Bolivia', 'storefront'); ?></option>
						<option value="Bonaire"><?php _e('Bonaire', 'storefront'); ?></option>
						<option value="Bosnia &amp; Herzegovina"><?php _e('Bosnia &amp; Herzegovina', 'storefront'); ?></option>
						<option value="Botswana"><?php _e('Botswana', 'storefront'); ?></option>
						<option value="Brazil"><?php _e('Brazil', 'storefront'); ?></option>
						<option value="British Indian Ocean Ter"><?php _e('British Indian Ocean Ter', 'storefront'); ?></option>
						<option value="Brunei"><?php _e('Brunei', 'storefront'); ?></option>
						<option value="Bulgaria"><?php _e('Bulgaria', 'storefront'); ?></option>
						<option value="Burkina Faso"><?php _e('Burkina Faso', 'storefront'); ?></option>
						<option value="Burundi"><?php _e('Burundi', 'storefront'); ?></option>
						<option value="Cambodia"><?php _e('Cambodia', 'storefront'); ?></option>
						<option value="Cameroon"><?php _e('Cameroon', 'storefront'); ?></option>
						<option value="Canada"><?php _e('Canada', 'storefront'); ?></option>
						<option value="Canary Islands"><?php _e('Canary Islands', 'storefront'); ?></option>
						<option value="Cape Verde"><?php _e('Cape Verde', 'storefront'); ?></option>
						<option value="Cayman Islands"><?php _e('Cayman Islands', 'storefront'); ?></option>
						<option value="Central African Republic"><?php _e('Central African Republic', 'storefront'); ?></option>
						<option value="Chad"><?php _e('Chad', 'storefront'); ?></option>
						<option value="Channel Islands"><?php _e('Channel Islands', 'storefront'); ?></option>
						<option value="Chile"><?php _e('Chile', 'storefront'); ?></option>
						<option value="China"><?php _e('China', 'storefront'); ?></option>
						<option value="Christmas Island"><?php _e('Christmas Island', 'storefront'); ?></option>
						<option value="Cocos Island"><?php _e('Cocos Island', 'storefront'); ?></option>
						<option value="Colombia"><?php _e('Colombia', 'storefront'); ?></option>
						<option value="Comoros"><?php _e('Comoros', 'storefront'); ?></option>
						<option value="Congo"><?php _e('Congo', 'storefront'); ?></option>
						<option value="Cook Islands"><?php _e('Cook Islands', 'storefront'); ?></option>
						<option value="Costa Rica"><?php _e('Costa Rica', 'storefront'); ?></option>
						<option value="Cote DIvoire"><?php _e('Cote D`Ivoire', 'storefront'); ?></option>
						<option value="Croatia"><?php _e('Croatia', 'storefront'); ?></option>
						<option value="Cuba"><?php _e('Cuba', 'storefront'); ?></option>
						<option value="Curaco"><?php _e('Curacao', 'storefront'); ?></option>
						<option value="Cyprus"><?php _e('Cyprus', 'storefront'); ?></option>
						<option value="Czech Republic"><?php _e('Czech Republic', 'storefront'); ?></option>
						<option value="Denmark"><?php _e('Denmark', 'storefront'); ?></option>
						<option value="Djibouti"><?php _e('Djibouti', 'storefront'); ?></option>
						<option value="Dominica"><?php _e('Dominica', 'storefront'); ?></option>
						<option value="Dominican Republic"><?php _e('Dominican Republic', 'storefront'); ?></option>
						<option value="East Timor"><?php _e('East Timor', 'storefront'); ?></option>
						<option value="Ecuador"><?php _e('Ecuador', 'storefront'); ?></option>
						<option value="Egypt"><?php _e('Egypt', 'storefront'); ?></option>
						<option value="El Salvador"><?php _e('El Salvador', 'storefront'); ?></option>
						<option value="Equatorial Guinea"><?php _e('Equatorial Guinea', 'storefront'); ?></option>
						<option value="Eritrea"><?php _e('Eritrea', 'storefront'); ?></option>
						<option value="Estonia"><?php _e('Estonia', 'storefront'); ?></option>
						<option value="Ethiopia"><?php _e('Ethiopia', 'storefront'); ?></option>
						<option value="Falkland Islands"><?php _e('Falkland Islands', 'storefront'); ?></option>
						<option value="Faroe Islands"><?php _e('Faroe Islands', 'storefront'); ?></option>
						<option value="Fiji"><?php _e('Fiji', 'storefront'); ?></option>
						<option value="Finland"><?php _e('Finland', 'storefront'); ?></option>
						<option value="France"><?php _e('France', 'storefront'); ?></option>
						<option value="French Guiana"><?php _e('French Guiana', 'storefront'); ?></option>
						<option value="French Polynesia"><?php _e('French Polynesia', 'storefront'); ?></option>
						<option value="French Southern Ter"><?php _e('French Southern Ter', 'storefront'); ?></option>
						<option value="Gabon"><?php _e('Gabon', 'storefront'); ?></option>
						<option value="Gambia"><?php _e('Gambia', 'storefront'); ?></option>
						<option value="Georgia"><?php _e('Georgia', 'storefront'); ?></option>
						<option value="Germany"><?php _e('Germany', 'storefront'); ?></option>
						<option value="Ghana"><?php _e('Ghana', 'storefront'); ?></option>
						<option value="Gibraltar"><?php _e('Gibraltar', 'storefront'); ?></option>
						<option value="Great Britain"><?php _e('Great Britain', 'storefront'); ?></option>
						<option value="Greece"><?php _e('Greece', 'storefront'); ?></option>
						<option value="Greenland"><?php _e('Greenland', 'storefront'); ?></option>
						<option value="Grenada"><?php _e('Grenada', 'storefront'); ?></option>
						<option value="Guadeloupe"><?php _e('Guadeloupe', 'storefront'); ?></option>
						<option value="Guam"><?php _e('Guam', 'storefront'); ?></option>
						<option value="Guatemala"><?php _e('Guatemala', 'storefront'); ?></option>
						<option value="Guinea"><?php _e('Guinea', 'storefront'); ?></option>
						<option value="Guyana"><?php _e('Guyana', 'storefront'); ?></option>
						<option value="Haiti"><?php _e('Haiti', 'storefront'); ?></option>
						<option value="Hawaii"><?php _e('Hawaii', 'storefront'); ?></option>
						<option value="Honduras"><?php _e('Honduras', 'storefront'); ?></option>
						<option value="Hong Kong"><?php _e('Hong Kong', 'storefront'); ?></option>
						<option value="Hungary"><?php _e('Hungary', 'storefront'); ?></option>
						<option value="Iceland"><?php _e('Iceland', 'storefront'); ?></option>
						<option value="India"><?php _e('India', 'storefront'); ?></option>
						<option value="Indonesia"><?php _e('Indonesia', 'storefront'); ?></option>
						<option value="Iran"><?php _e('Iran', 'storefront'); ?></option>
						<option value="Iraq"><?php _e('Iraq', 'storefront'); ?></option>
						<option value="Ireland"><?php _e('Ireland', 'storefront'); ?></option>
						<option value="Isle of Man"><?php _e('Isle of Man', 'storefront'); ?></option>
						<option value="Israel"><?php _e('Israel', 'storefront'); ?></option>
						<option value="Italy"><?php _e('Italy', 'storefront'); ?></option>
						<option value="Jamaica"><?php _e('Jamaica', 'storefront'); ?></option>
						<option value="Japan"><?php _e('Japan', 'storefront'); ?></option>
						<option value="Jordan"><?php _e('Jordan', 'storefront'); ?></option>
						<option value="Kazakhstan"><?php _e('Kazakhstan', 'storefront'); ?></option>
						<option value="Kenya"><?php _e('Kenya', 'storefront'); ?></option>
						<option value="Kiribati"><?php _e('Kiribati', 'storefront'); ?></option>
						<option value="Korea North"><?php _e('Korea North', 'storefront'); ?></option>
						<option value="Korea Sout"><?php _e('Korea South', 'storefront'); ?></option>
						<option value="Kuwait"><?php _e('Kuwait', 'storefront'); ?></option>
						<option value="Kyrgyzstan"><?php _e('Kyrgyzstan', 'storefront'); ?></option>
						<option value="Laos"><?php _e('Laos', 'storefront'); ?></option>
						<option value="Latvia"><?php _e('Latvia', 'storefront'); ?></option>
						<option value="Lebanon"><?php _e('Lebanon', 'storefront'); ?></option>
						<option value="Lesotho"><?php _e('Lesotho', 'storefront'); ?></option>
						<option value="Liberia"><?php _e('Liberia', 'storefront'); ?></option>
						<option value="Libya"><?php _e('Libya', 'storefront'); ?></option>
						<option value="Liechtenstein"><?php _e('Liechtenstein', 'storefront'); ?></option>
						<option value="Lithuania"><?php _e('Lithuania', 'storefront'); ?></option>
						<option value="Luxembourg"><?php _e('Luxembourg', 'storefront'); ?></option>
						<option value="Macau"><?php _e('Macau', 'storefront'); ?></option>
						<option value="Macedonia"><?php _e('Macedonia', 'storefront'); ?></option>
						<option value="Madagascar"><?php _e('Madagascar', 'storefront'); ?></option>
						<option value="Malaysia"><?php _e('Malaysia', 'storefront'); ?></option>
						<option value="Malawi"><?php _e('Malawi', 'storefront'); ?></option>
						<option value="Maldives"><?php _e('Maldives', 'storefront'); ?></option>
						<option value="Mali"><?php _e('Mali', 'storefront'); ?></option>
						<option value="Malta"><?php _e('Malta', 'storefront'); ?></option>
						<option value="Marshall Islands"><?php _e('Marshall Islands', 'storefront'); ?></option>
						<option value="Martinique"><?php _e('Martinique', 'storefront'); ?></option>
						<option value="Mauritania"><?php _e('Mauritania', 'storefront'); ?></option>
						<option value="Mauritius"><?php _e('Mauritius', 'storefront'); ?></option>
						<option value="Mayotte"><?php _e('Mayotte', 'storefront'); ?></option>
						<option value="Mexico"><?php _e('Mexico', 'storefront'); ?></option>
						<option value="Midway Islands"><?php _e('Midway Islands', 'storefront'); ?></option>
						<option value="Moldova"><?php _e('Moldova', 'storefront'); ?></option>
						<option value="Monaco"><?php _e('Monaco', 'storefront'); ?></option>
						<option value="Mongolia"><?php _e('Mongolia', 'storefront'); ?></option>
						<option value="Montserrat"><?php _e('Montserrat', 'storefront'); ?></option>
						<option value="Morocco"><?php _e('Morocco', 'storefront'); ?></option>
						<option value="Mozambique"><?php _e('Mozambique', 'storefront'); ?></option>
						<option value="Myanmar"><?php _e('Myanmar', 'storefront'); ?></option>
						<option value="Nambia"><?php _e('Nambia', 'storefront'); ?></option>
						<option value="Nauru"><?php _e('Nauru', 'storefront'); ?></option>
						<option value="Nepal"><?php _e('Nepal', 'storefront'); ?></option>
						<option value="Netherland Antilles"><?php _e('Netherland Antilles', 'storefront'); ?></option>
						<option value="Netherlands"><?php _e('Netherlands (Holland, Europe)', 'storefront'); ?></option>
						<option value="Nevis"><?php _e('Nevis', 'storefront'); ?></option>
						<option value="New Caledonia"><?php _e('New Caledonia', 'storefront'); ?></option>
						<option value="New Zealand"><?php _e('New Zealand', 'storefront'); ?></option>
						<option value="Nicaragua"><?php _e('Nicaragua', 'storefront'); ?></option>
						<option value="Niger"><?php _e('Niger', 'storefront'); ?></option>
						<option value="Nigeria"><?php _e('Nigeria', 'storefront'); ?></option>
						<option value="Niue"><?php _e('Niue', 'storefront'); ?></option>
						<option value="Norfolk Island"><?php _e('Norfolk Island', 'storefront'); ?></option>
						<option value="Norway"><?php _e('Norway', 'storefront'); ?></option>
						<option value="Oman"><?php _e('Oman', 'storefront'); ?></option>
						<option value="Pakistan"><?php _e('Pakistan', 'storefront'); ?></option>
						<option value="Palau Island"><?php _e('Palau Island', 'storefront'); ?></option>
						<option value="Palestine"><?php _e('Palestine', 'storefront'); ?></option>
						<option value="Panama"><?php _e('Panama', 'storefront'); ?></option>
						<option value="Papua New Guinea"><?php _e('Papua New Guinea', 'storefront'); ?></option>
						<option value="Paraguay"><?php _e('Paraguay', 'storefront'); ?></option>
						<option value="Peru"><?php _e('Peru', 'storefront'); ?></option>
						<option value="Phillipines"><?php _e('Philippines', 'storefront'); ?></option>
						<option value="Pitcairn Island"><?php _e('Pitcairn Island', 'storefront'); ?></option>
						<option value="Poland"><?php _e('Poland', 'storefront'); ?></option>
						<option value="Portugal"><?php _e('Portugal', 'storefront'); ?></option>
						<option value="Puerto Rico"><?php _e('Puerto Rico', 'storefront'); ?></option>
						<option value="Qatar"><?php _e('Qatar', 'storefront'); ?></option>
						<option value="Republic of Montenegro"><?php _e('Republic of Montenegro', 'storefront'); ?></option>
						<option value="Republic of Serbia"><?php _e('Republic of Serbia', 'storefront'); ?></option>
						<option value="Reunion"><?php _e('Reunion', 'storefront'); ?></option>
						<option value="Romania"><?php _e('Romania', 'storefront'); ?></option>
						<option value="Russia"><?php _e('Russia', 'storefront'); ?></option>
						<option value="Rwanda"><?php _e('Rwanda', 'storefront'); ?></option>
						<option value="St Barthelemy"><?php _e('St Barthelemy', 'storefront'); ?></option>
						<option value="St Eustatius"><?php _e('St Eustatius', 'storefront'); ?></option>
						<option value="St Helena"><?php _e('St Helena', 'storefront'); ?></option>
						<option value="St Kitts-Nevis"><?php _e('St Kitts-Nevis', 'storefront'); ?></option>
						<option value="St Lucia"><?php _e('St Lucia', 'storefront'); ?></option>
						<option value="St Maarten"><?php _e('St Maarten', 'storefront'); ?></option>
						<option value="St Pierre &amp; Miquelon"><?php _e('St Pierre &amp; Miquelon', 'storefront'); ?></option>
						<option value="St Vincent &amp; Grenadines"><?php _e('St Vincent &amp; Grenadines', 'storefront'); ?></option>
						<option value="Saipan"><?php _e('Saipan', 'storefront'); ?></option>
						<option value="Samoa"><?php _e('Samoa', 'storefront'); ?></option>
						<option value="Samoa American"><?php _e('Samoa American', 'storefront'); ?></option>
						<option value="San Marino"><?php _e('San Marino', 'storefront'); ?></option>
						<option value="Sao Tome &amp; Principe"><?php _e('Sao Tome &amp; Principe', 'storefront'); ?></option>
						<option value="Saudi Arabia"><?php _e('Saudi Arabia', 'storefront'); ?></option>
						<option value="Senegal"><?php _e('Senegal', 'storefront'); ?></option>
						<option value="Serbia"><?php _e('Serbia', 'storefront'); ?></option>
						<option value="Seychelles"><?php _e('Seychelles', 'storefront'); ?></option>
						<option value="Sierra Leone"><?php _e('Sierra Leone', 'storefront'); ?></option>
						<option value="Singapore"><?php _e('Singapore', 'storefront'); ?></option>
						<option value="Slovakia"><?php _e('Slovakia', 'storefront'); ?></option>
						<option value="Slovenia"><?php _e('Slovenia', 'storefront'); ?></option>
						<option value="Solomon Islands"><?php _e('Solomon Islands', 'storefront'); ?></option>
						<option value="Somalia"><?php _e('Somalia', 'storefront'); ?></option>
						<option value="South Africa"><?php _e('South Africa', 'storefront'); ?></option>
						<option value="Spain"><?php _e('Spain', 'storefront'); ?></option>
						<option value="Sri Lanka"><?php _e('Sri Lanka', 'storefront'); ?></option>
						<option value="Sudan"><?php _e('Sudan', 'storefront'); ?></option>
						<option value="Suriname"><?php _e('Suriname', 'storefront'); ?></option>
						<option value="Swaziland"><?php _e('Swaziland', 'storefront'); ?></option>
						<option value="Sweden"><?php _e('Sweden', 'storefront'); ?></option>
						<option value="Switzerland"><?php _e('Switzerland', 'storefront'); ?></option>
						<option value="Syria"><?php _e('Syria', 'storefront'); ?></option>
						<option value="Tahiti"><?php _e('Tahiti', 'storefront'); ?></option>
						<option value="Taiwan"><?php _e('Taiwan', 'storefront'); ?></option>
						<option value="Tajikistan"><?php _e('Tajikistan', 'storefront'); ?></option>
						<option value="Tanzania"><?php _e('Tanzania', 'storefront'); ?></option>
						<option value="Thailand"><?php _e('Thailand', 'storefront'); ?></option>
						<option value="Togo"><?php _e('Togo', 'storefront'); ?></option>
						<option value="Tokelau"><?php _e('Tokelau', 'storefront'); ?></option>
						<option value="Tonga"><?php _e('Tonga', 'storefront'); ?></option>
						<option value="Trinidad &amp; Tobago"><?php _e('Trinidad &amp; Tobago', 'storefront'); ?></option>
						<option value="Tunisia"><?php _e('Tunisia', 'storefront'); ?></option>
						<option value="Turkey"><?php _e('Turkey', 'storefront'); ?></option>
						<option value="Turkmenistan"><?php _e('Turkmenistan', 'storefront'); ?></option>
						<option value="Turks &amp; Caicos Is"><?php _e('Turks &amp; Caicos Is', 'storefront'); ?></option>
						<option value="Tuvalu"><?php _e('Tuvalu', 'storefront'); ?></option>
						<option value="Uganda"><?php _e('Uganda', 'storefront'); ?></option>
						<option value="Ukraine"><?php _e('Ukraine', 'storefront'); ?></option>
						<option value="United Arab Erimates"><?php _e('United Arab Emirates', 'storefront'); ?></option>
						<option value="United Kingdom"><?php _e('United Kingdom', 'storefront'); ?></option>
						<option value="United States of America"><?php _e('United States of America', 'storefront'); ?></option>
						<option value="Uraguay"><?php _e('Uruguay', 'storefront'); ?></option>
						<option value="Uzbekistan"><?php _e('Uzbekistan', 'storefront'); ?></option>
						<option value="Vanuatu"><?php _e('Vanuatu', 'storefront'); ?></option>
						<option value="Vatican City State"><?php _e('Vatican City State', 'storefront'); ?></option>
						<option value="Venezuela"><?php _e('Venezuela', 'storefront'); ?></option>
						<option value="Vietnam"><?php _e('Vietnam', 'storefront'); ?></option>
						<option value="Virgin Islands (Brit)"><?php _e('Virgin Islands (Brit)', 'storefront'); ?></option>
						<option value="Virgin Islands (USA)"><?php _e('Virgin Islands (USA)', 'storefront'); ?></option>
						<option value="Wake Island"><?php _e('Wake Island', 'storefront'); ?></option>
						<option value="Wallis &amp; Futana Is"><?php _e('Wallis &amp; Futana Is', 'storefront'); ?></option>
						<option value="Yemen"><?php _e('Yemen', 'storefront'); ?></option>
						<option value="Zaire"><?php _e('Zaire', 'storefront'); ?></option>
						<option value="Zambia"><?php _e('Zambia', 'storefront'); ?></option>
						<option value="Zimbabwe"><?php _e('Zimbabwe', 'storefront'); ?></option>
					</select>

					<label for="phone"><?php _e('Phone', 'storefront'); ?></label><input id="phone" type="text" autocomplete="OFF" maxlength="40" name="phone" size="20" />

					<label for="email"><?php _e('Email', 'storefront'); ?></label><input id="email" type="text" autocomplete="OFF" maxlength="80" name="email" size="20" />

					<label for="email"><?php _e('Confirm Email', 'storefront'); ?></label><input id="email1" type="text" autocomplete="OFF" maxlength="80" name="email1" size="20" />

				</div>
				<div class="cbot rightc">

					<label for="Message"><?php _e('Message', 'storefront'); ?></label>
					<textarea id="00N20000003PCp5" name="00N20000003PCp5" rows="5" wrap="soft"></textarea>

					<br /><br />
					<b><?php _e('I have read the', 'storefront'); ?> <a style="text-decoration: underline;" href="https://www.fuel-3d.com/privacy/"><?php _e('Privacy Policy', 'storefront'); ?></a>:</b> <input id="00N20000003PCpK" type="checkbox" name="00N20000003PCpK" value="1" />

					<br /><br />
					<strong><?php _e('Are you human?', 'storefront'); ?></strong>

					<script type="text/javascript">// <![CDATA[
						DrawBotBoot()
						// ]]></script>
					<br /><br />
					<input type="submit" name="btnSubmit" value="<?php _e('Submit Query', 'storefront'); ?>" />
				</div>
			</div>
		</form>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
			do_action( 'storefront_page_before' );
			?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php
			/**
			 * @hooked storefront_display_comments - 10
			 */
			do_action( 'storefront_page_after' );
			?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->

<?php get_footer(); ?>
