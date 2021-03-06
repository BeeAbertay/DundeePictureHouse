<?php
/*
    Description: Dundee Picture House Home Page
    Author: Brad Mair
*/
?>
<html>
<!--<head>-->
    <?php
        include '../Model/DPH-api.php' ;
        include 'header.php';
    ?>
<!-- </head> -->
<title>DPH - Home</title>
<body>
  <br>
  <?php

  $searchResults = OMDBSearch();
  $movieArray = json_decode($searchResults);
  if ($movieArray->Response == 'True')
  {
    echo "<div class='container'>
            <form action='' method='post'>
              <div class='input-group input-group-sm mb-3'>
                <input type='text' class='form-control' placeholder='Search' aria-label='Small' aria-describedby='inputGroup-sizing-sm' id='searchTitle' name='searchTitle'>
                <div class='input-group-append'>
                  <button id='search-by-title-button' name='search-by-title-button' type='submit' class='btn btn-outline-info'>Search</button>
                </div>
              </div>
            </form>
          </div>";
    $rows = 0;
    $cols = 3;
    $counter = 1;
    $nbsp = $cols - ($rows % $cols);

    echo '<div class="container">';

    for ($i=0 ; $i < sizeof($movieArray->Search) ; $i++)
    {
      if(($counter % $cols) == 1)
      {
        echo '<div class="row">';
      }
        echo "<div class='col-md-4'>";
        echo '<div class="card" style="width: 20rem;">'; // Open card div
        echo '<img src="'.$movieArray->Search[$i]->Poster.'" class="card-img-top" alt="..." style="height: 30rem">'; // card image
        echo '<div class="card-body">';// open card body
        echo '<h5 class="card-title">'.$movieArray->Search[$i]->Title.'</h5>'; // card title
        echo '</div>';// close card body
        echo '<ul class="list-group list-group-flush">'; // start list inside the card
        echo '<li class="list-group-item">Release Date: '.$movieArray->Search[$i]->Year.'</li>';
        echo '</ul>';
        //echo "<a class='btn btn-info' href='movie.php?id=".$movieArray[$i]->Movie_ID ."'>More</a>";
// more info button
        echo ' </div>';// close card body
        echo ' </div>';// close card

      if(($counter % $cols) == 0)
      {
          echo '</div></br>';
      }
    $counter++;
    }

    if($nbsp > 0)
    {
        for ($i = 0; $i < $nbsp; $i++)
        {
           echo'<div class="col-md-4">&nbsp;</div>';
        }
    }
    echo ' </div>';// close container
    echo '</div><br>';
  }
  else
  {
    echo "<div class='container'>
            <form action='' method='post'>
              <div class='input-group input-group-sm mb-3'>
                <div class='input-group-prepend'>
                  <span class='input-group-text btn-outline-danger disable' id='inputGroup-sizing-sm'>No Movie Found, Try Again </span>
                </div>
                <input type='text' class='form-control' placeholder='Search' aria-label='Small' aria-describedby='inputGroup-sizing-sm' id='searchTitle' name='searchTitle'>
                <div class='input-group-append'>
                  <button id='search-by-title-button' name='search-by-title-button' type='submit' class='btn btn-outline-info'>Search</button>
                </div>
              </div>
            </form>
          </div>";
  }

  ?>
</div>
<!-- <footer> -->
        <?php include 'footer.php'; ?>
<!-- </footer> -->

</div><!-- end of container-->
    <?php include '../Controller/bootstrapScript.php'; ?>
</body>
</html>
