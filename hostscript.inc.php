<?php
//-----------------------------------------------------------------------------
// library        hostscript.inc.php
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

$query = array("_id" => new MongoId($_REQUEST['id']));
$host_attributs = db_query("host", $query);

$query = array("_id" => new MongoId($host_attributs['cluster_id']));
$cluster_attributs = db_query("cluster", $query);

$query = array("_id" => new MongoId($host_attributs['template_id']));
$template_attributs = db_query("template", $query);

$query = array("cluster_id" => $host_attributs['cluster_id']);
$datastores = db_query_array("datastore", $query);

$script = $template_attributs["script"];

foreach ($host_attributs as $key => $value)
	$script = str_replace("###".strtoupper($key)."###", $value, $script);

foreach ($cluster_attributs as $key => $value)
	$script = str_replace("###".strtoupper($key)."###", $value, $script);

$nfs_datastores = "";
foreach ($datastores as $key)
	$nfs_datastores .= "esxcli storage nfs -H ".$key["nfs_host"]." -s ".$key["nfs_share"]." -v \"".$key["nfs_volume_name"]."\"\n";

$script = str_replace("###NFS_DATASTORES###", $nfs_datastores, $script);

foreach ($template_attributs as $key => $value)
	$script = str_replace("###".strtoupper($key)."###", $value, $script);

?>
