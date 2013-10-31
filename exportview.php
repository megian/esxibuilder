<?php
//-----------------------------------------------------------------------------
// library        exportview.php
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

	include("default.inc.php");
	page_begin();

	echo "<h1>Export Config</h1>";

	$collection = "host";

	echo "<table class=\"sqltable\">";
	echo "<tr>";
	//echo "<th></th>";
	echo "<th>Hostname</th>";
	echo "<th>Management IP</th>";
	echo "</tr>";

	function show($data)
	{
		echo
		"<tr id=\"".$data['_id']."\" onClick=\"location.href='exportconfig.php?id=".$data["_id"]."'\">"
		//."<td></td>"
		."<td>".htmlspecialchars($data["hostname"])."</td>"
		."<td>".htmlspecialchars($data["management_ip"])."</td>"
		."</tr>";
	}
 
	db_query_multi(show, $collection); 

	echo "</table>";

echo "<h2>Syslinux.cfg</h2>";

echo "<p>Please add this lines to your syslinux.cfg config from <a href=\"http://unetbootin.sourceforge.net/\">UNetbootin</a></p>";

echo "<pre>";

/*echo "

default menu.c32
prompt 0
menu title UNetbootin
timeout 100

label unetbootindefault
menu label Default
kernel /ubnkern
append initrd=/ubninit -c boot.cfg

label ubnentry1
menu label ^Boot from local disk
kernel /ubnkern
append initrd=/ubninit 

";*/

$hosts = db_query_array("host");

foreach ($hosts as $host_attributs)
{
	$query = array("_id" => new MongoId($host_attributs['cluster_id']));
	$cluster_attributs = db_query("cluster", $query);

	$query = array("_id" => new MongoId($host_attributs['template_id']));
	$template_attributs = db_query("template", $query);

	$menu_label = $template_attributs["template_menu_label"];
	$menu_label = str_replace("###HOSTNAME###", $host_attributs["hostname"], $menu_label);

	echo "label custom-".$host_attributs["hostname"]."\n";
	echo "menu label ".$menu_label ."\n";
	echo "kernel /MBOOT.C32\n";
	echo "append initrd=/ubninit -c boot.cfg ks=usb:/ks/ks-".$host_attributs["hostname"].".cfg\n";
	echo "\n";
}

echo "</pre>";

	page_end();
?>
