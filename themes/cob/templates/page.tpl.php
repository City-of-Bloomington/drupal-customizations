<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>

<div id="page">
	<div id="header">
	<?php
		echo theme('links__system_main_menu', array(
			'links'      => $main_menu,
			'attributes' => array(
				'id' => 'main-menu',
				'class'      => array('links')
			)
		));
		echo render($page['header']);
		echo "
			<div id=\"logo\">
		";

		$home_text = t('Home');
		if ($logo) {
			echo "
				<a href=\"$front_page\" title=\"$home_text\" rel=\"home\">
					<img src=\"$logo\"    alt=\"$home_text\" />
				</a>
			";
		}
		if ($site_name) {
			echo "
				<div id=\"site_name\">
					<h1>
						<a href=\"$front_page\" title=\"$home_text\" rel=\"home\">
						$site_name
						</a>
					</h1>

				</div>
			";
		}

		echo "</div>";

			if ($site_slogan) {
				echo "<div id=\"site-slogan\"><h2>$site_slogan</h2></div>";
			}
	?>
	</div> <!-- , /#header -->
	<div id="menubar">
		<ul id="social-media-links">
			<li><a href="http://facebook.com/cityofbloomington">Facebook</a></li>
			<li><a href="https://twitter.com/citybloomington">Twitter</a></li>
			<li><a href="http://youtube.com/user/cityofbloomington">Youtube</a></li>
		</ul>
		<ul class="megadropdown">
		<?php
			$links = array(
				'menu_government' => array('text'=>'My Government', 'node_id'=>2),
				'menu_want'       => array('text'=>'I Want To',     'node_id'=>3),
				'menu_learn'      => array('text'=>'Learn About',   'node_id'=>4),
				'menu_students'   => array('text'=>'Students',      'node_id'=>5),
				'menu_business'   => array('text'=>'Businesses',    'node_id'=>6),
				'menu_residents'  => array('text'=>'Residents',     'node_id'=>7),
				'menu_visitors'   => array('text'=>'Visitors',      'node_id'=>8)
			);
			$attributes = array('attributes'=>array('class'=>'megadropdown-link'));
			foreach ($links as $region_id=>$link) {
				$a_href = l($link['text'], 'node/'.$link['node_id'], $attributes);
				echo "
				<li class=\"main-nav-item\">
					$a_href
					<div id=\"$region_id\" class=\"megadropdown-region\">
				";
					if (isset($page[$region_id])) { echo render($page[$region_id]); }
				echo "
					</div>
				</li>
				";
			}
		?>
		</ul>
	</div>
	<?php
		if ($title || $breadcrumb) {
			echo "<div id=\"breadcrumb\">";
			echo render($title_prefix);
			if ($title) {
				echo "<h1 class=\"title\" id=\"page-title\">$title</h1>";
			}
			echo render($title_suffix);
			if ($breadcrumb) {
				echo $breadcrumb;
			}
			echo "</div>";
		}

		echo $messages;
	?>
	<div id="main" class="clearfix">
		<div id="content" class="column">
			<div id="main-content">
			<?php
				if ($page['highlighted']) {
					echo '<div id="highlighted">';
					echo render($page['highlighted']);
					echo '</div>';
				}
				if ($tabs) {
					echo '<div class="tabs">';
					echo render($tabs);
					echo '</div>';
				}
				echo render($page['help']);
				if ($action_links) {
					echo '<ul class="action-links">';
					echo render($action_links);
					echo '</ul>';
				}

				echo '<div id="sidebar-second" class="region region-sidebar-second sidebar">';
				include $directory.'/includes/sidebar-second.php';
				if ($page['sidebar_second']) {
					echo render($page['sidebar_second']);
				}
				echo '</div>';

				echo render($page['content']);
				echo $feed_icons;
			?>
			</div>
			<?php
			if ($page['sidebar_first']) {
				echo '<div id="sidebar-first" class="region region-sidebar-first sidebar">';
				if (isset($sponsors)) {
					include $directory.'/includes/sponsors.php';
				}
				echo render($page['sidebar_first']);
				echo '</div>';
			}

		?>
		</div> <!-- /#content -->
	</div> <!-- /#main -->

	<div id="footer">
		<?php
			echo render($page['footer']);
			echo theme('links__system_secondary_menu', array(
				'links' => $secondary_menu,
				'attributes' => array(
					'id' => 'secondary-menu',
					'class' => array('links', 'inline')
				)
			));
		?>
	</div> <!-- /#footer -->
</div> <!-- /#page -->
