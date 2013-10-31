<?php
//-----------------------------------------------------------------------------
// @library        config.inc.php
// @version        1.0
// @date           2.6.2003
// @update         26.10.2003
// @authors        Gabriel Mainberger <gabisoft@freesurf.ch>
// @licence        GPL
//-----------------------------------------------------------------------------
// People School Project
// Copyright (C) 2003 Gabriel Mainberger <gabisoft@freesurf.ch>,
// Christian Fischer <cfischer@ee.ethz.ch>
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
// 26.10.2003 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - First Publicated Version
// 18.01.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Configure for Intranet
//
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// MongoDB Configuration
//------------------------------------------------------------------------------

$config_mongodb_connection_string = "localhost";
$config_mongodb_database = "esxibuilder";

//------------------------------------------------------------------------------
// Other
//------------------------------------------------------------------------------

$config_login = true;
$config_host = "esxibuilder";

//------------------------------------------------------------------------------
// MENU entries
//------------------------------------------------------------------------------

$config_menu = array(
	array("Home", ".", "h"),
	array("Accounts", "accountview.php"),
	array("Hosts", "hostview.php"),
	array("Clusters", "clusterview.php"),
	array("Datastores", "datastoreview.php"),
	array("Templates", "templateview.php"),
	array("Exports", "exportview.php"),
	array("Logout", "logout.php")
);

?>
