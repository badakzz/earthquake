<?php
function nav_item_active($id, $link, $title): string {
	$class = 'nav-link';
	$scriptname = 'https://lucasderay.com' . $_SERVER['SCRIPT_NAME'];
	if ($scriptname === $link) {
		$class .= ' active" aria-current="page"';
	}
	return <<<HTML
	<li class="nav-item">
		<a id="$id" class="$class" href="$link">$title</a>
	<li>
HTML;
}

function nav_item_inactive($username): string {
	return <<<HTML
	<li class="nav-item">
		<a class="nav-link disabled" tabindex="-1" aria_disabled="true">$username</a>
	</li>
HTML;
}

function nav_logged_out()
{
	echo nav_item_active('navlink1', 'https://lucasderay.com/app/index.php', 'Home');
	echo nav_item_active('navlink1', 'https://lucasderay.com', 'Landing page');
	echo <<<HTML
		</ul>
	<ul class="navbar-nav ms-lg-4 ms-xl-7 border-bottom border-lg-bottom-0 pt-2 pt-lg-0 d-flex py-3 py-lg-0">
HTML;
	echo nav_item_active('navlink2', 'https://lucasderay.com/app/tutorial.php', 'How to setup ?');
	echo '</ul>';
}

function nav_logged_in()
{
	echo nav_item_active('navlink1', 'https://lucasderay.com/app/clock.php', 'Home');
	echo nav_item_active('navlink1', 'https://lucasderay.com/app/profile.php', 'Profile');
	echo nav_item_active('navlink1', 'https://lucasderay.com/', 'Landing Page');
	echo <<<HTML
		</ul>
	<ul class="navbar-nav ms-lg-4 ms-xl-7 border-bottom border-lg-bottom-0 pt-2 pt-lg-0 d-flex py-3 py-lg-0">
HTML;
	echo nav_item_active('navlink1', 'https://lucasderay.com/app/tutorial.php', 'How to setup ?');
	echo <<<HTML
	</ul>
	<form class="d-flex py-3 py-lg-0">
		<a class="btn btn-info order-0 me-1" href="https://lucasderay.com/app/logout.php" role="button">Log out</a>
	</form>
HTML;
}