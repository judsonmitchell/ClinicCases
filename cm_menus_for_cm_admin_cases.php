<script>
function check(){
document.bugs.code.value=navigator.appCodeName;
document.bugs.app.value=navigator.appName;
document.bugs.version.value=navigator.appVersion;
document.bugs.platform.value=navigator.platform;
document.bugs.agent.value=navigator.userAgent;
document.bugs.java.value=navigator.javaEnabled();
document.bugs.page.value=location.href;
var screenWidth = screenWidth();
function screenWidth() {

	if (window.screen) {
		return(screen.width);
	} else {
		return(0);
	}
}
document.bugs.width.value=screenWidth;
var screenHeight = screenHeight();

function screenHeight() {

	if (window.screen) {
		return(screen.height);
	} else {
		return(0);
	}
}
document.bugs.height.value=screenHeight;
}
</script><div id="menus"><img src="images/logo_small4.png"> <a class="menu" href="#"  onClick="Effect.Appear('bug');createTargets('bug','bug');sendDataGet('bugs_report.php');return false;">Report Problems</a> | <a class="menu" href="logout.php">Logout</a> | Status: <span id="session_info"><span style="color:red;">Offline</span></span></div>  
