<?php
function h($text) {
	echo htmlspecialchars($text, ENT_QUOTES, 'utf-8');
}
?>