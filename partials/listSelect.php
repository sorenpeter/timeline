<!-- List UI -->
<form action="" method="get">
  <!-- Select a list: -->
    <select name="list" onchange="this.form.submit()">
    <option value="twtxt.txt" selected>twtxt.txt (Main)</option>
    <?php

      if( isset($_SESSION['password'])) {
        if($_SESSION['password']=="$password") { // Hacky login

        // Private lists
        echo "<option disabled>Private Lists:</option>";
        foreach (glob("private/twtxt-*.txt") as $filename) {
            if($filename == $_GET['lists']) $attr="selected";
            else $attr = "";
            $listName = $filename;
            $listName = str_replace("private/twtxt-", "", $listName);
            $listName = str_replace("_", " ", $listName);
            $listName = str_replace(".txt", "", $listName);
            echo "<option value='{$filename}' {$attr}>$listName</option>";
        }

        // Public Lists
        echo "<option disabled>Public Lists:</option>";
        }
      }

      foreach (glob("lists/twtxt-*.txt") as $filename) {
          if($filename == $_GET['lists']) $attr="selected";
          else $attr = "";
          $listName = $filename;
          $listName = str_replace("lists/twtxt-", "", $listName);
          $listName = str_replace("_", " ", $listName);
          $listName = str_replace(".txt", "", $listName);
          echo "<option value='{$filename}' {$attr}>$listName</option>";
      }

    ?>
    </select>
    <noscript><button type="submit">View list</button></noscript>
</form>