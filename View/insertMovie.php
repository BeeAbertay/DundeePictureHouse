<?php
/*
    Description: form used to insert ne movies into the database.

    Author: David McRae
*/
include '../Model/DPH-api.php' ;
include 'header.php';

//if (!isset($_SESSION['LoggedIn']) || $_SESSION['Admin_Status'] != 1)
//{
//  header("Location: index.php");
//}
//else
//{
//echo "
?>
<html>
<head>

</head>

<body>
  <div class='container'>
    <div class='page-header'>
        <h1>Insert Page</h1>
    </div>

    <div class='container'>

        <form class='form-group needs-validation' method='POST' action='../Controller/attempt_insertMovie.php' enctype='multipart/form-data' novalidate>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Title</span>
              </div>
                <input class='form-control' type='text' name='title' placeholder='Title' required>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Video</span>
              </div>
                <input class='form-control' type='text' name='video' placeholder='Please Past the Youtube URL into this field' required>
            </div>

            <div class='form-group input-group'>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Poster Image</span>
              </div>
                <input class='btn btn-info' type='file' name='image_link' placeholder='Image_link' required>
            </div>

            <div class='form-group input-group'>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Decription</span>
              </div>
                </br>
                <textarea class='form-control' type='text' name='description' placeholder='Description' rows='5' required></textarea>
            </div>

            <!--
            <input type='hidden' name='postdate' value='".date('Y-m-d H:i:s')."'>
            <input type='hidden' name='userid' value='".$_SESSION['User_ID']."'>
            -->
            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Release Date</span>
              </div>
                <input class='form-control' type='date' name='releaseDate' placeholder='Release Date' required>
            </div>

            <div class="form-group input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Age Rating</span>
              </div>
              <select class="custom-select" name="ageRating" required>
                <option value="">Please Select a Star Rating</option>
                <option value="U">U</option>
                <option value="PG">PG</option>
                <option value="12A">12A</option>
                <option value="15">15</option>
                <option value="18">18</option>
              </select>
              <div class="invalid-feedback">Please Select an Age rating</div>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">RunTime</span>
              </div>
                <input class='form-control' type='text' name='runTime' placeholder='RunTime' required>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Genre</span>
              </div>
                <input class='form-control' type='text' name='genre' placeholder='Genre' required>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Director</span>
              </div>
                <input class='form-control' type='text' name='director' placeholder='Director' required>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Actors</span>
              </div>
                <input class='form-control' type='text' name='actors' placeholder='Actors' required>
            </div>

            <div class='form-group input-group' form-group-lg>
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Language</span>
              </div>
                <input class='form-control' type='text' name='language' placeholder='Language' required>
            </div>

            <div class="form-group form-check input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">3D</span>
              </div>
            <input class="form-check-input" type="checkbox" name="threeD" value="" id="invalidCheck">
          </div>

          <div class="form-group form-check input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroupPrepend">Audio Described</span>
            </div>
          <input class="form-check-input" type="checkbox" name="audioDescribed" value="" id="invalidCheck">
        </div>

        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend">Star Rating</span>
          </div>
          <select class="custom-select" name="starRating" required>
            <option value="">Please Select a Star Rating</option>
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
          <div class="invalid-feedback">Please Select a star rating</div>
        </div>

            <button class='form-control' type='submit' name='insertMovieSubmit'>Insert Movie</button>
        </form>
    </div>
</div>

<!-- <footer> -->
<?php include 'footer.php'; ?>
<!-- </footer> -->
<!-- <Script> -->
<?php
include '../Controller/bootstrapScript.php';
require '../Controller/ValidateEmptyFields.js';
?>
<!-- </Script> -->

</body>
</html>
}
?>
