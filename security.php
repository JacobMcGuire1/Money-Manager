<?php /* Converts special characters to HTML entities so that they don't interfere with the HTML. */
function h($text) {
	echo htmlspecialchars($text, ENT_QUOTES, 'utf-8');
}
?>