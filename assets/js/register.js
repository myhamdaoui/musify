/**
 * name: Musify
 * description: JS for the register page
 * author: HAMDAOUI Mohammed-Yassin
 * version: 1.0
 */

$(document).ready(function() {
	$(".hideLogin").click(function() {
		$("#login-form").hide();
		$("#register-form").show();
	});

	$(".hideRegister").click(function() {
		$("#login-form").show();
		$("#register-form").hide();
	});
});