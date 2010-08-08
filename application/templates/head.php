<title>YSS</title>
<meta charset="utf-8" />

<link rel="stylesheet" href="resources/css/style.css" />
<link rel="stylesheet" href="resources/css/skin.css" />
<link rel="stylesheet" href="resources/css/jquery.ui.theme.css" />
<link rel="stylesheet" href="resources/css/jqModal.css" />
<link rel="shortcut icon" href="" />
<link rel="apple-touch-icon" href="" />

<script src="http://code.jquery.com/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>


<?
	// if release configuration then load in compressed javascript
	// else load src js
if("release" == YSSConfiguration::applicationConfiguration()):?>
	<script src="resources/js/script.min.js"></script>
<?else:?>
	<script src="resources/js/src/cufon/cufon-yui.js"></script>
	<script src="resources/js/src/cufon/Vegur.font.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.address.min.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.annotate.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.easing.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.countup.js"></script>
	<script src="resources/js/src/jquery-addons/jqModal.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.jeditable.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.phui.js"></script>
	<script src="resources/js/src/jquery-addons/jquery.phui.transitions.js"></script>
	<script src="resources/js/src/utils/json2.js"></script>
	<script src="resources/js/src/yss/main.js"></script>
	<script src="resources/js/src/yss/deeplink.js"></script>
	<script src="resources/js/src/yss/api.js"></script>
	<script src="resources/js/src/yss/modules.js"></script>
	<script src="resources/js/src/yss/editable-fields.js"></script>
	<script src="resources/js/src/yss/forms.js"></script>
	<script src="resources/js/src/yss/forms.validation.js"></script>
	<script src="resources/js/src/yss/modal.js"></script>
	<script src="resources/js/src/yss/notes.js"></script>
	<script src="resources/js/src/yss/progressbar.js"></script>
	<script src="resources/js/src/yss/table-orientation.js"></script>
<?endif;?>