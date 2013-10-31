<?php
//-----------------------------------------------------------------------------
// library        clusterform.php
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

$id = $_REQUEST['id'];

page_begin();
echo "<h1>Cluster</h1>";
echo "<p>· <a href=\"clusterview.php\" accesskey=\"b\"><b>B</b>ack</a> · <a href=\"clusterform.php\" accesskey=\"a\"><b>A</b>dd</a> ·</p>";

$collection = "cluster";

$fields = array(
  array("text", "Cluster Name", "cluster_name", ""),
  array("text", "Root Password", "root_password", ""),
  array("text", "DNS Domain", "dns_domain", ""),
  array("text", "DNS Server 1", "dns_server1", ""),
  array("text", "DNS Server 2", "dns_server2", ""),
  array("text", "Management Subnet", "management_subnet", ""),
  array("text", "Management Gateway", "management_gateway", ""),
  array("text", "Storage Subnet", "storage_subnet", ""),
  array("text", "vMotion Subnet", "vmotion_subnet", ""),
  array("text", "Fault Tolerance Subnet", "faulttolerance_subnet", "")
);

db_add("form1", $collection, $fields, "");
db_mod("form1", $collection, $fields);
db_del("form1", $collection);
db_form("form1", $collection, $fields);

page_end(); ?>
