<?php
//-----------------------------------------------------------------------------
// @library        form.inc.php
// @version        1.0
// @date           20.7.2003
// @update         26.10.2013
// @authors        Gabriel Mainberger <gabisoft@freesurf.ch>
// @licence        GPL
//-----------------------------------------------------------------------------
// Copyright (c) 2003-2013 Gabriel Mainberger <gabisoft@freesurf.ch>
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
//   - Add type "smart" to db_form()
// 18.01.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Add type "select" to db_form()
// 18.01.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Add type "file" to db_form()
// 21.01.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Fix </text> to </textarea>
// 25.01.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Add type "idfile" to db_form()
// 09.02.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - XHTML 1.1 compatible code
// 14.02.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Add type "htmlarea" to db_form()
// 16.07.2004 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Add type "checkbox" to db_form()
//	24.10.2006 - Gabriel Mainberger <gabisoft@freeusrf.ch>
//   - Add type "uid"
// 04.11.2006 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Recode
// 26.10.2013 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Change backend from MySQL to MongoDB
//
//------------------------------------------------------------------------------

// Filename: File Extention

function extractfileext($filename)
{
  $s = split('\.', $filename);

  if(count($s)>1)
    return($s[count($s)-1]);

  return("");
}

// Feldtyp, Bezeichnung, DB-Name, default, grösse

function db_form($formname, $collection, $fields)
{
	global $id;

	$db_form_type_array = array("text", "textarea", "password", "select", "file", "idfile", "htmlarea", "checkbox", "seq");

	if(!isset($id))
		$id = $_REQUEST['id'];

  if($_POST[$formname.'_del']>0)
    unset($id);

  echo "<form accept-charset=\"utf-8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."\">\n";

  if(isset($id))
  {
    echo "<input type=\"hidden\" name=\"".$formname."_mod\" value=\"".$id."\" />";
    if(isset($id))
    {

	$fld = array();

      foreach($fields as $field)
      {
        if(in_array($field[0], $db_form_type_array))
		$fld[$field[2]] = 1;
      }

	$query = array("_id" => new MongoId($id));
	$data = db_query($collection, $query, $fld);  
    }
  }
  else
    echo "<input type=\"hidden\" name=\"".$formname."_add\" value=\"0\" />\n";
    
  // hidden fields
  //seq
  echo "  <input type=\"hidden\" name=\"".$formname."_seq\" value=\"".$data[0]."\" />\n";
  $d = 1;
  foreach($fields as $field)
  {
    if($field[0]=="hidden")
      echo "  <input type=\"hidden\" name=\"".$formname."_".$field[2]."\" value=\"".$field[3]."\" />\n";
    else if($field[0]=="silent")
      echo "  <input type=\"hidden\" name=\"".$field[2]."\" value=\"".$field[3]."\" />\n";
  }

  echo "<table>\n";

  foreach($fields as $field)
  {
    if(($field[0]=="hidden") || ($field[0]=="silent"))
      continue;

    echo "<tr>\n";

    if($field[1]!="say")
      echo "  <td>".$field[1]."</td>\n";

    if(($field[0]=="text") || ($field[0]=="password"))
      echo "  <td><input type=\"".$field[0]."\" name=\"".$formname."_".$field[2]."\" size=\"".$field[5]."\" value=\"".(isset($id)?htmlspecialchars($data[$field[2]]):htmlspecialchars($field[3]))."\" /></td>\n";
    else if($field[0]=="textarea")
      echo "  <td><textarea id=\"".$formname."_".$field[2]."\" name=\"".$formname."_".$field[2]."\" cols=\"".$field[4]."\" rows=\"".$field[5]."\">".(isset($id)?htmlspecialchars($data[$field[2]]):htmlspecialchars($field[3]))."</textarea></td>\n";
    else if($field[0]=="htmlarea")
      echo "  <td><textarea id=\"".$formname."_".$field[2]."\" name=\"".$formname."_".$field[2]."\" cols=\"".$field[4]."\" rows=\"".$field[5]."\" style=\"width: 100%\">".(isset($id)?htmlspecialchars($data[$field[2]]):htmlspecialchars($field[3]))."</textarea></td>\n";
    else if($field[0]=="checkbox")
    {
      if($data[$d++]=='1')
        echo " <td><input type=\"checkbox\" name=\"".$formname."_".$field[2]."\" checked=\checked\" /></td>\n";
      else
        echo " <td><input type=\"checkbox\" name=\"".$formname."_".$field[2]."\" /></td>\n";
    }
    else if($field[0]=="select")
    {
      $selectedid = $data[$field[2]];
      echo "  <td><select name=\"".$formname."_".$field[2]."\" size=\"1\">";

	$fld = array();
	$fld["_id"] = 1;
	$fld[$field[4]] = 1;

	$documents = db_query_array($field[3], array(), $fld);

	foreach ($documents as $document)
	{
		if($document["_id"]==$selectedid)
			echo "<option selected=\"selected\" value=\"".$document["_id"]."\">".$document[$field[4]]."</option>";
		else
			echo "<option value=\"".$document["_id"]."\">".$document[$field[4]]."</option>";
	}
      echo "</select></td>";
    }
    else if($field[0]=="file")
    {
      if(isset($id))
        echo "  <td><a href=\"".$field[4].$data[$d]."\">".$data[$d++]."</a></td>\n";
      else
      {
        echo "  <td><input type=\"file\" name=\"".$formname."_".$field[2]."\"></td>\n";
        $d++;
      }
    }
    else if($field[0]=="idfile")
    {
      if(isset($id))
      {
        if($data[$d]!="")
          $ext = ".".$data[$d++];
        echo "  <td><a href=\"".$field[4].$id.$ext."\">".$id.$ext."</a></td>\n";
      }
      else
      {
        echo "  <td><input type=\"file\" name=\"".$formname."_".$field[2]."\" /></td>\n";
        $d++;
      }
    }
     else
      echo "  <td colspan=\"2\">".$field[3]."</td>\n";

    echo "</tr>\n";
  }

  echo "  <tr><td></td><td>";

  if(isset($id))
  {
    echo "<input type=\"submit\" value=\"Modify\" accesskey=\"s\" /></form>";
    echo "<form method=\"post\"><input type=\"hidden\" name=\"".$formname."_del\" value=\"$id\" /><input type=\"hidden\" name=\"".$formname."_seq\" value=\"".$data[0]."\" /><input type=\"submit\" value=\"Delete\" /></form>";    
  }
  else
    echo "<input type=\"submit\" value=\"Insert\" accesskey=\"s\" />";

  echo "</td></tr></table>\n";
}

function db_add($formname, $collection, $fields, $text)
{
	global $id;
	global $mysql_connect_handle;
	
	$db_add_type_array = array("text", "textarea", "password", "hidden", "select", "file", "idfile", "htmlarea", "checkbox");

	if($_POST[$formname.'_add']=="0")
	{
		$id = new MongoId(); 

		$fld = array();
		$fld["_id"] = $id;

		foreach($fields as $field)
		{
			if(in_array($field[0], $db_add_type_array))
			{
				switch($field[0])
				{
					case "file": $fld[$field[2]] = $_FILES[$formname."_".$field[2]]["name"]; break;
					case "idfile": $fld[$field[2]] = extractfileext($_FILES[$formname."_".$field[2]]["name"]); break;
					case "htmlarea": $fld[$field[2]] = "'".$_POST[ta]."'"; break;
					default: $fld[$field[2]] = $_POST[$formname."_".$field[2]];
				}			
		      	}
		}

		db_insert($collection, $fld);

		if($text!="")
		{
			echo $text;
			footer();
			page_end();
			exit;
		}

  }
}

function db_mod($formname, $collection, $fields)
{
  global $id;
  
  $db_mod_type_array = array("text", "textarea", "password", "hidden", "select", "htmlarea", "checkbox");

  if($_POST[$formname.'_mod']!="")
  {
    $id = $_POST[$formname.'_mod'];
    
	$fld = array();
    
    foreach($fields as $field)
    {
      if(in_array($field[0], $db_mod_type_array))
      {
        if($field[0]=="checkbox")
        {
          if($_POST[$formname."_".$field[2]]=="on")
            $fld[$field[2]] = true;
          else
            $fld[$field[2]] = false;
        }
        else
		$fld[$field[2]] = $_POST[$formname."_".$field[2]];
       }
    }

	$update['$set'] = $fld;
	$criteria["_id"] = new MongoId($id);
    
	db_update($collection, $criteria, $update);
  }
}

function db_del($formname, $collection)
{
	global $id;
  
	if($_POST[$formname.'_del']!="")
	{
		$criteria["_id"] = new MongoId($id);
		db_remove($collection, $criteria);
	}
}
?>
