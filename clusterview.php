<?php
//-----------------------------------------------------------------------------
// library        clusterview.php
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

	echo "<h1>Clusters</h1>";
	echo "<p>· <a href=\"clusterform.php\" accesskey=\"a\"><b>A</b>dd</a> ·</p>";

	$o = $_REQUEST['o'];

	$limit = 1;
	$collection = "cluster";
	if(isset($offset))
		$o = 0;

	echo "<p>";
	db_navigation($collection, $limit);
	echo "</p>";

	echo "<table class=\"sqltable\">";
	echo "<tr>";
	echo "<th></th>";
	echo "<th>Cluster Name</th>";
	echo "<th>DNS Domain</th>";
	echo "<th>Management Subnet</th>";
	echo "<th>Management Gateway</th>";
	echo "<th>Storage Subnet</th>";
	echo "<th>vMotion Subnet</th>";
	echo "<th>Fault Tolerance Subnet</th>";
	echo "</tr>";

	function show($data)
	{
		echo
		"<tr id=\"".$data['_id']."\" onClick=\"location.href='clusterform.php?id=".$data["_id"]."'\">"
		."<td><a href=\"clusterform.php?id=".$data["_id"]."\"><img src=\"img/edit.png\" alt=\"\" /></a></td>"
		."<td>".htmlspecialchars($data["cluster_name"])."</td>"
		."<td>".htmlspecialchars($data["dns_domain"])."</td>"
		."<td>".htmlspecialchars($data["management_subnet"])."</td>"
		."<td>".htmlspecialchars($data["management_gateway"])."</td>"
		."<td>".htmlspecialchars($data["storage_subnet"])."</td>"
		."<td>".htmlspecialchars($data["vmotion_subnet"])."</td>"
		."<td>".htmlspecialchars($data["faulttolerance_subnet"])."</td>"

		."</tr>"
		;
	}
 
	db_query_multi(show, $collection); 

	echo "</table>";

	echo "<br /><p>";
	//db_navigation($collection, $limit);
	echo "</p>";

	page_end();
?>
