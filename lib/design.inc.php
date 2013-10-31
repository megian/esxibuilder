<?php
//-----------------------------------------------------------------------------
// library        project.inc.php
// version        1.0
// date           26.10.2013
// update         26.10.2013
// authors        Gabriel Mainberger <gabisoft@freesurf.ch>
// licence        GPL
//-----------------------------------------------------------------------------
// Copyright (c) 2013 Gabriel Mainberger <gabisoft@freesurf.ch>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//------------------------------------------------------------------------------
//
// History:
//
// 26.10.2013 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Initial Version
//
//------------------------------------------------------------------------------

//-----------------------------------------------------------------------------
// function			page_begin()
// description		Erstell Grundseite
//-----------------------------------------------------------------------------

function page_begin()
{
	global $config_menu;
	global $config_login;

	db_open();
	
	// SESSION
	if($config_login)
		session();

	// HTML BEGIN
	html_begin("ESXi Builder");

	// BODY BEGIN
	echo "<div id=\"page\"><div id=\"header\"><div id=\"header-text\"></div></div><div id=\"content\"><div id=\"menu\">";

	// BODY MENU
	body_menu($config_menu);
	
	// BODY MIDDLE
	echo "</div><div id=\"text\">";
}

//-----------------------------------------------------------------------------
// function			page_end()
// description		Beendet HTML Seite.
//-----------------------------------------------------------------------------

function page_end()
{
	// BODY END
	echo "</div></div>";
  
	// FOOTER
	echo "<div id=\"footer\">© 2013 Gabriel Mainberger</div></div>";

	// HTML END
	html_end();
}

//-----------------------------------------------------------------------------
// function			body_begin()
// description		Menu (begin)
//-----------------------------------------------------------------------------

function body_menu($menu)
{
	echo "<ul>";
	foreach($menu as $entry)
	{
		echo "<li>";   
		echo "<a href=\"".$entry[1]."\"";
		if($entry[3]!="")
			echo " accesskey=\"".$entry[3]."\"";
		echo ">".$entry[0]."</a>";
		if(is_array($entry[2]))
			body_menu($entry[2]);
		echo "</li>\n";
	}	
	echo "</ul>";
}

//-----------------------------------------------------------------------------
// function				session()
// description			Session initialitsation
//-----------------------------------------------------------------------------

function session()
{
	global $config_menu;
	global $config_login;

	session_start();
  
	if(array_key_exists('_username', $_POST))
	{
		$query = array("username" => $_POST['_username']);
		$fields = array("password");
		$data = db_query("account", $query, $fields);

		if($data["password"]!="" && $data["password"]==$_POST['_password'])
		{
			$_SESSION['username'] = $_POST['_username'];
			$_SESSION['login_state'] = "TRUE";
		}
	}

	if($_SESSION['login_state']!="TRUE")
	{
		$config_login = FALSE;
		$config_menu = array(array("Home", "index.php"));
		page_begin();
		echo "<h1>Login</h1>";
		echo "<form id=\"loginform\" method=\"post\" action=\"$SELF_PHP\">";
		echo "<p>";
		echo "<input name=\"_username\" /><br />";
		echo "<input name=\"_password\" type=\"password\" /><br />";
		echo "<input type=\"submit\" value=\"Login\" />";
		echo "</p>";
		echo "</form>";
		echo "<script type=\"text/javascript\">\n";
		echo "document.forms.loginform._username.focus();\n";
		echo "</script>\n";
		page_end();
		exit();
	}
}

?>
