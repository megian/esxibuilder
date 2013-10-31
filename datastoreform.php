<?php
//-----------------------------------------------------------------------------
// library        datastoreform.php
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
echo "<h1>Datastore</h1>";
echo "<p>· <a href=\"datastoreview.php\" accesskey=\"b\"><b>B</b>ack</a> · <a href=\"datastoreform.php\" accesskey=\"a\"><b>A</b>dd</a> ·</p>";

$collection = "datastore";

$fields = array(
  array("select", "Cluster", "cluster_id", "cluster", "cluster_name"),
  array("text", "NFS Host", "nfs_host", ""),
  array("text", "NFS Share", "nfs_share", ""),
  array("text", "NFS Volume Name", "nfs_volume_name", "")
);

db_add("form1", $collection, $fields, "");
db_mod("form1", $collection, $fields);
db_del("form1", $collection);
db_form("form1", $collection, $fields);

page_end(); ?>
