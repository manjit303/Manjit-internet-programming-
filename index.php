<?php
  require("config.php");
  ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ROYAL CUISINE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        header{text-align: center;
            background-color: black;}
        h4{text-align: right;
            margin-right: 300px;
            color: #fff;}
            ul li a {
    display: block;
    padding: 10px 40px;
    color: #fff;
    font-weight: bold;
    text-decoration: none;
    text-align: right;
    font-size: 17px;
}
ul li ul.dropdown   {
    display: block;
}
ul li ul.dropdown {
    width: 150%;
    background: black;
    position: absolute;
    z-index: 999;
    display: none;
}
ul li a:hover {
    background: black;
}   
ul li:hover ul.dropdown {
    display: block;
}
ul {
    list-style: none;
    background: black;
}
ul li{
    display: inline-block;
    position: relative;
       
}
nav {
    text-align: right;    
}        
h1{
   text-align: center;
   color: black;
}
thead{
  background-color: black;
}
#head{
  background-color: black;
}

    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body class="bg-light">
<header>
   <img src="images.jpeg" alt="Smoothie Bar Logo" width="300">
   <h4> INDIAN AUTHETIC TASTE</h4>
  </header>
  <nav id="nav_menu">
    <ul>
      <li><a href="#">MENU</a>
      <ul class="dropdown">
        <li><a href="#">VEGETARIAN</a></li>
        <li><a href="#">NON - VEG</a></li>
        <li><a href="#">DRINKS</a></li> 
        <li><a href="#">DESERTS</a></li>
      </ul>
      </li>
      <li><a href="forms.php">FOR REGISTER</a> </li>
      <li><a href="aboutus.php">ABOUT US</a> </li>
    </ul>
  </nav>
  <main>
    <div id="head" class="container  p-3 mt-3 rounded my-4">
        <div class="d-flex align-item-center justify-content-between">
            <h2>
              <a href="index.php" class="text-white text-decoration-none"></i> ROYAL CUISINE MENU</a></h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#additem">
            <i class="bi bi-plus-lg"></i>ADD ITEM
            </button>
       </div>
    </div>
    <div class="container mt-3 p-0">
      <table class="table table-hover text-center">
      <thead class="text-light">
    <tr>
      <th width="10%" scope="col" class="rounded-start">Sr.no</th>
      <th width="10%" scope="col">Item Code</th>
      <th width="15%" scope="col">Image</th>
      <th width="35%" scope="col">Item Description</th>
      <th width="10%" scope="col">Price</th>
      <th width="20%" scope="col" class="rounded-end">Action</th>
    </tr>
  </thead>
  <tbody class="bg-white">
  <?php
    function image_upload($img){
         $tmp_loc = $img['tmp_name'];
         $new_name = random_int(11111,99999).$img['name'];
     
         $new_loc = UPLOAD_SRC.$new_name;

         if(!move_uploaded_file($tmp_loc,$new_loc)){
            header("location: index.php?alert=img_upload");
            exit;
         }
         else{
             return $new_name;
         }
      }
    function image_remove($img){
      if(!unlink(UPLOAD_SRC.$img)){
        header("location: index.php?alert=img_rem_failed");
        exit;
      }
    }
     if(isset($_POST['additem']))
     {
        foreach($_POST as $key => $value){
         $_POST[$key] = mysqli_real_escape_string($con,$value);
        }
        $imgpath = image_upload($_FILES['image']);
        
        $query="INSERT INTO menu_list(`code`,`description`,`price`,`image`)
         VALUES ('$_POST[code]','$_POST[description]','$_POST[price]','$imgpath')";
     
         if(mysqli_query($con,$query)){
             header("location: index.php?success=added");
         }
         else{
             header("location: index.php?alert=add_failed");
         }
      }   
     if(isset($_GET['rem']) && $_GET['rem']>0)
     {
      $query="SELECT * FROM `menu_list` WHERE `no`='$_GET[rem]'";
      $result=mysqli_query($con,$query);
      $fetch=mysqli_fetch_assoc($result);

      image_remove($fetch['image']);

      $query="DELETE FROM `menu_list` WHERE `no`='$_GET[rem]'";
      if(mysqli_query($con,$query)){
        header("location: index.php?success=removed");
      }
      else{
        header("location: index.php?success=removed_failed");
      }
     }
     if(isset($_POST['edititem']))
     {
      foreach($_POST as $key => $value){
        $_POST[$key] = mysqli_real_escape_string($con,$value);
      }
     
     if(file_exists($_FILES['image']['tmp_name'])  || is_uploaded_file($_FILES['image']['tmp_name'])){
      $query="SELECT * FROM `menu_list` WHERE `no`='$_POST[editino]'";
      $result=mysqli_query($con,$query);
      $fetch=mysqli_fetch_assoc($result);

      image_remove($fetch['image']);

      $imgpath=image_upload($_FILES['image']);

      $update="UPDATE `menu_list` SET `code`='$_POST[code]',`image`='$imgpath',
      `description`='$_POST[description]',`price`= '$_POST[price]' WHERE `no`='$_POST[editino]'";
     }
     else{
      $update="UPDATE `menu_list` SET `code`='$_POST[code]',
      `description`='$_POST[description]',`price`= '$_POST[price]' WHERE `no`='$_POST[editino]'";
     }
     if(mysqli_query($con,$update)){
      header("location: index.php?success=updated");
    }
    else{
      header("location: index.php?alert=updated_failed");
    }
    }
    ?>
    <?php
      $query="SELECT * FROM `menu_list`";
      $result=mysqli_query($con,$query);
      $i=1;
      $fetch_src=FETCH_SRC;
      while($fetch=mysqli_fetch_assoc($result))
      {
        echo<<<product
         <tr>
           <th scope="row">$i</th>
           <td>$fetch[code]</td>
           <td><img src="$fetch_src$fetch[image]" width="200px"></td>
           <td>$fetch[description]</td>
           <td>$fetch[price]</td>
           <td>
             <a href="?edit=$fetch[no]" class="btn btn-warning me-3"><i class="bi bi-pencil-square"></i></a>
             <button onclick="confirm_rem($fetch[no])" class="btn btn-danger" ><i class="bi bi-trash"></i></button>
           </td>

        </tr>
     
        product;
        $i++;
      }
    ?>
    
  </tbody>
</table>
</div>
  <div class="modal fade" id="additem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form actions="index.php" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >CREATE RECORD</h5>
        
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <span class="input-group-text">ITEM CODE</span>
          <input type="text" class="form-control" name="code" requiblack>
      </div>
      <div class="input-group mb-3">
          <span class="input-group-text">ITEM DESCRIPTION</span>
          <textarea class="form-control" name="description" requiblack></textarea>
      </div>
      <div class="input-group mb-3">
          <span class="input-group-text">PRICE</span>
          <input type="text" class="form-control" name="price" min="1" requiblack>
      </div>
      <div class="input-group mb-3">
         <label class="input-group-text">Image</label>
         <input type="file" class="form-control" name="image" accept=".jpg,.png,.jpeg" requiblack>
        
      </div>
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="submit" class="btn btn-success" name="additem"> ADD </button>
      </div>
    </div>
   </form>
  </div>
</div>
</div>
    <div class="modal fade" id="edititem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form actions="index.php" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >EDIT RECORD</h5>
        
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <span class="input-group-text">ITEM CODE</span>
          <input type="text" class="form-control" name="code" id="editcode" requiblack>
      </div>
      <div class="input-group mb-3">
          <span class="input-group-text">ITEM DESCRIPTION</span>
          <textarea class="form-control" name="description" id="editdescription" requiblack></textarea>
      </div>
      <div class="input-group mb-3">
          <span class="input-group-text">PRICE</span>
          <input type="text" class="form-control" name="price" id="editprice" min="1" requiblack>
      </div>
      <img src="" id="editimg" width="100%" class="mb-3"><br>
      <div class="input-group mb-3">
         <label class="input-group-text">Image</label>
         <input type="file" class="form-control" name="image" accept=".jpg,.png,.jpeg" >
      </div>
      <input type="hidden" name="editino" id="editino">
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
        <button type="submit" class="btn btn-success" name="edititem"> EDIT </button>
      </div>
    </div>
   </form>
  </div>
</div>
<?php
   if(isset($_GET['edit']) && $_GET['edit']>0)
   {
    $query="SELECT * FROM `menu_list` WHERE `no`='$_GET[edit]'";
      $result=mysqli_query($con,$query);
      $fetch=mysqli_fetch_assoc($result);
      echo"
        <script>
        var edititem = new bootstrap.Modal(document.getElementById('edititem'), {
          keyboard: false
        });
        document.querySelector('#editcode').value='$fetch[code]';
        document.querySelector('#editdescription').value='$fetch[description]';
        document.querySelector('#editprice').value='$fetch[price]';
        document.querySelector('#editimg').src='$fetch_src$fetch[image]';
        document.querySelector('#editino').value='$_GET[edit]'; 
        edititem.show();
        </script>
      ";
   }

?>
   <script>
    function confirm_rem(no){
      if(confirm("Are you sure, you want to delete this record?")){
        window.location.href="index.php?rem="+no;
      }
    }
   </script>
  </main>
                                  
</body>
</html>