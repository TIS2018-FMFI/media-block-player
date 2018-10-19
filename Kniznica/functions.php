<?php

function get_media_files(){
  $page_entries = 5;
  $paths = array("Data/A-V sources", "Data/Scripts", "Data/Translations", "Data/Sync files");
  $start_from = 0;
  if (isset($_GET['page'])) $start_from = (($_GET['page']-1) * $page_entries)+2;
  //echo "$start_from";
  //if (isset($_GET['page'])) echo "$_GET['page']";

  echo "<table class='table table-striped table-bordered'>
    <tr>
      <th>File name</th>
      <th>Type</th>
      <th>Added</th>
      <th>Size</th>
    </tr>";

  for ($i = 0; $i < count($paths); $i++){
    $files = scandir($paths[$i]);
    for ($x = $start_from; ($x < $start_from + $page_entries && $x < count($files)); $x++) {
      if ($files[$x] != '.' && $files[$x] != '..'){
        $arr = explode('/', mime_content_type("$paths[$i]/$files[$x]"));
        if (in_array('video', $arr) || in_array('audio', $arr)){
          $size = filesize("$paths[$i]/$files[$x]");
          $mtime = filemtime("$paths[$i]/$files[$x]");
          $filename = explode('.',$files[$x])[0];
          echo "<tr>\n\t\t<th><button type='button' class='btn w3-theme-d5' data-toggle='modal' data-target='#$filename'>$filename</button></th>\n";
          echo "\t\t<th>$arr[0]</th>\n\t\t<th>$mtime</th>\n\t\t<th>$size kB</th>\n\t</tr>\n";

          echo "<div class='modal' id='$filename'>\n";
          echo "\t<div class='modal-dialog'>\n";
          echo "\t\t<div class='modal-content'>\n";
          echo "\t\t\t<div class='modal-header'>\n";
          echo "\t\t\t\t<h4>$filename</h4>\n";
          echo "\t\t\t\t<button type='button' class='close' data-dismiss='modal'>&times;</button>\n\t\t\t</div>\n";
          echo "\t\t\t<div class='modal-body'>\n";
          echo "\t\t\t\tMODAL BODY\n";
          echo "\t\t\t</div>\n";
          echo "\t\t\t<div class='modal-footer'><button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button></div>\n";
          echo "\t\t</div>\n\t</div>\n</div>\n\n\t";
        }
      }
    }
  }
  echo "</table><ul class='pagination'>";
  for ($i = 1; $i <= (count(scandir("Data/A-V sources")) / $page_entries)+1; $i++){
    if (isset($_GET['page']) && $i == $_GET['page']) echo "<li class='page-item active'> <a class='page-link w3-theme w3-border-theme w3-text-black' href='?page=$i'>$i</a></li>";
    else echo "<li class='page-item'> <a class='page-link w3-border-theme w3-text-black' href='?page=$i'>$i</a></li>";
  }
  echo "</ul>";
}
?>
