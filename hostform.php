<?php
//-----------------------------------------------------------------------------
// library        hostform.php
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
echo "<h1>Host</h1>";
echo "<p>· <a href=\"hostview.php\" accesskey=\"b\"><b>B</b>ack</a> · <a href=\"hostform.php\" accesskey=\"a\"><b>A</b>dd</a> ·</p>";

$collection = "host";

$fields = array(
  array("select", "Template", "template_id", "template", "template_name"),
  array("select", "Cluster", "cluster_id", "cluster", "cluster_name"),
  array("text", "Hostname", "hostname", ""),
  array("text", "Management IP", "management_ip", ""),
  array("text", "Storage IP", "storage_ip", ""),
  array("text", "vMotion IP", "vmotion_ip", ""),
  array("text", "Fault Tolerance IP", "faulttolerance_ip", ""),
  array("text", "License", "host_license", "AAAAA-BBBBB-CCCCC-DDDDD-EEEEE")
);

db_add("form1", $collection, $fields, "");
db_mod("form1", $collection, $fields);
db_del("form1", $collection);
db_form("form1", $collection, $fields);

page_end(); ?>
