<?php
//-----------------------------------------------------------------------------
// library        hostconfig.php
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
echo "<h1>Host Config</h1>";
echo "<p>· <a href=\"hostview.php\" accesskey=\"b\"><b>B</b>ack</a></p>";

include("hostscript.inc.php");

echo "<h2>Attributs</h2>";

echo "<table>";

foreach ($host_attributs as $key => $value)
	echo "<tr><td>".strtoupper($key)."</td><td>$value</td></tr>";

foreach ($cluster_attributs as $key => $value)
	echo "<tr><td>".strtoupper($key)."</td><td>$value</td></tr>";

foreach ($template_attributs as $key => $value)
{
	if ($key != "script")
		echo "<tr><td>".strtoupper($key)."</td><td>$value</td></tr>";
}

echo "</table>";

echo "<h2>Script</h2>";
echo "<pre>";

echo $script;

echo "</pre>";

page_end(); ?>
