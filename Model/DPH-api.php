<?php
/*
    Description: Dundee Picture House API file
    Collection of functions used in the system

    Author: David McRae, Aaron Hay, Brad Mair
*/
//Get All Movies
function GetAllMovies()
{
    require 'dbConnection.php';
    // $dateFilter = '';
    // if (filter_input(INPUT_POST, "month", FILTER_SANITIZE_STRING))
    // {
    //     $month = filter_input (INPUT_POST, "month", FILTER_SANITIZE_STRING);
    //     if ($month == "0")
    //     {
    //         $dateFilter = '';
    //     }
    //     else
    //     {
    //         $dateFilter = "WHERE MONTH(Release_Date) = '$month'";
    //     }
    // }

    $sortOrder = 'ORDER BY Release_Date desc';
    if (filter_input(INPUT_POST, "ordering", FILTER_SANITIZE_STRING))
    {
        $ordering = filter_input (INPUT_POST, "ordering", FILTER_SANITIZE_STRING);
        if ($ordering == "0")
        {
            $sortOrder = 'ORDER BY Release_Date desc';
        }
        elseif ($ordering == "1")
        {
            $sortOrder = 'ORDER BY Release_Date asc';
        }
        elseif ($ordering == "2")
        {
            $sortOrder = 'ORDER BY Age_Rating asc';
        }
        elseif ($ordering == "3")
        {
            $sortOrder = 'ORDER BY Age_Rating desc';
        }
        elseif ($ordering == "4")
        {
            $sortOrder = 'ORDER BY Title asc';
        }
        elseif ($ordering == "5")
        {
            $sortOrder = 'ORDER BY Title desc';
        }
        elseif ($ordering == "6")
        {
            $sortOrder = 'ORDER BY RunTime desc';
        }
        elseif ($ordering == "7")
        {
            $sortOrder = 'ORDER BY RunTime asc';
        }
    }

    $sql = "SELECT * FROM DPH_Movie $sortOrder";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->fetch();
    $success = $stmt->execute();

    if($success && $stmt->rowCount() > 0)
    {
      //  convert to JSON
      $rows = array();
      while($r = $stmt->fetch())
      {
        $rows[] = $r;
      }
      return json_encode($rows);
    }
}

function CreateNewCustomer()
{
  Require 'dbConnection.php';

  if (isset($_POST["registerCustomerSubmit"]))
  {
    $firstName = (filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    $surname = (filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING));
    $email = (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $username = (filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = (filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    $passwordConfirm = (filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRING));

    $Error = false;
    $nameError;
    $emailError;
    $usernameError;
    $passwordError;
    $passwordConfirmError;

    if (!preg_match("/^[a-zA-Z ]*$/",$firstName) || !preg_match("/^[a-zA-Z ]*$/",$surname)) // First & Surname must be Letters
    {
      $Error = true;
      $nameError = "Your name can only contain letters";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Email Must be Valid
    {
      $Error = true;
      $emailError = "Invalid email format";
    }

    if(!preg_match("/^[a-zA-Z0-9]*$/", $username))//Username Must be letters & Numbers
    {
      $Error = true;
      $usernameError = "Username Must Contain only letters and numbers";
    }

    if(!empty($password) && $password == $passwordConfirm) // Password & PasswordConfirm Must Match
    {
      if(strlen($password) <= '8')// Passowrd must be Atleast 8 characters
      {
        $Error = true;
        $passwordError = "Password Must be Atleast 8 characters Long";
      }
      elseif(!preg_match("#[0-9]+#",$password)) // Password must contain a number
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Number!";
      }
      elseif(!preg_match("#[A-Z]+#",$password)) // Password Must contain an Uppercase letter
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Capital Letter!";
      }
      elseif(!preg_match("#[a-z]+#",$password))// Password Must Conatain a Lowercase letter
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Lowercase Letter!";
      }
      else// No password errors have Occured
      {
        $PasswordError = "Password Is Acceptable";
      }
    }
  }
  if(!empty($password) && $password != $passwordConfirm) // Password and PasswordConfirm do NOT Match
  {
    $Error = true;
    $passwordConfirmError = "Please Check You've Confirmed Your Password!";
  }
  if(empty($password)) // Password Is Empty
  {
    $Error = true;
    $passwordError = "Please enter a password";
  }

  if($Error == true) // An Error Has Occured
  {
    echo"'$nameError' </br> '$emailError' </br> '$usernameError' </br> '$passwordError' </br> '$passwordConfirmError'";
  }
  else // Continue with the Registration
  {
    // Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);
    $passwordConfirm ="";

    // Create SQL Template
    $query = $pdo->prepare
    ("

    INSERT INTO DPH_Customer (First_Name, Surname, Email, Username, Password)
    VALUES( :firstName, :surname, :email, :username, :password)

    ");

    // Runs and executes the query
    $success = $query->execute
    ([
      'firstName' => $firstName,
      'surname' => $surname,
      'email' => $email,
      'username' => $username,
      'password' => $password
    ]);

    // If rows returned is more than 0 Let us know if it inserted or not.
    $count = $query->rowCount();
    if($count > 0)
    {
      echo "Insert Successful";
    }
    else
    {
      echo "Insert Failed";
      echo $query -> errorInfo()[2];
    }
  }
}

function CreateNewEmployee()
{
  Require 'dbConnection.php';

  if (isset($_POST["registerEmployeeSubmit"]))
  {
    $firstName = (filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    $surname = (filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING));
    $jobrole = (filter_input(INPUT_POST, 'jobrole' , FILTER_SANITIZE_STRING));
    $username = (filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = (filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
    $passwordConfirm = (filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRING));


    // Error checking variables
    $Error = false;
    $nameError = "";
    $jobRoleError = "";
    $usernameError = "";
    $passwordError = "";
    $passwordConfirmError = "";

    if (!preg_match("/^[a-zA-Z ]*$/",$firstName) || !preg_match("/^[a-zA-Z ]*$/",$surname)) // First & Surname must be Letters
    {
      $Error = true;
      $nameError = "Your name can only contain letters";
    }

    if ($jobrole != "employee" && $jobrole != "supervisor" && $jobrole != "manager" ) // Job role must match a specific type from the list
    {
      $Error = true;
      $jobRoleError = "Job role is not a valid type, please select one from the dropdown list";
    }

    if(!preg_match("/^[a-zA-Z0-9]*$/", $username))//Username Must be letters & Numbers
    {
      $Error = true;
      $usernameError = "Username Must Contain only letters and numbers";
    }

    if(!empty($password) && $password == $passwordConfirm) // Password & PasswordConfirm Must Match
    {
      if(strlen($password) <= '8')// Passowrd must be Atleast 8 characters
      {
        $Error = true;
        $passwordError = "Password Must be Atleast 8 characters Long";
      }
      elseif(!preg_match("#[0-9]+#",$password)) // Password must contain a number
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Number!";
      }
      elseif(!preg_match("#[A-Z]+#",$password)) // Password Must contain an Uppercase letter
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Capital Letter!";
      }
      elseif(!preg_match("#[a-z]+#",$password))// Password Must Conatain a Lowercase letter
      {
        $Error = true;
        $passwordError = "Your Password Must Contain At Least 1 Lowercase Letter!";
      }
      else// No password errors have Occured
      {
        $PasswordError = "Password Is Acceptable";
      }
    }

  if(!empty($password) && $password != $passwordConfirm) // Password and PasswordConfirm do NOT Match
  {
    $Error = true;
    $passwordConfirmError = "Please Check You've Confirmed Your Password!";
  }
  if(empty($password)) // Password Is Empty
  {
    $Error = true;
    $passwordError = "Please enter a password";
  }

  if($Error == true) // An Error Has Occured
  {
    echo $nameError."</br>". $jobRoleError."</br>".$usernameError."</br>".$passwordError."</br>".$passwordConfirmError;
  }
  else // Continue with the Registration
  {
    //Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Create SQL Template
    $query = $pdo->prepare
    ("

    INSERT INTO DPH_Employee (First_Name, Surname, Job_Role, Username, Password)
    VALUES( :firstName, :surname, :jobrole, :username, :password)

    ");

    $success = $query->execute
    ([
      'firstName' => $firstName,
      'surname' => $surname,
      'jobrole' => $jobrole,
      'username' => $username,
      'password' => $password
    ]);

    $count = $query->rowCount();
    if($count > 0)
    {
      echo "Insert Successful";
    }
    else
    {
      echo "Insert Failed";
      echo $query -> errorInfo()[2];
    }
  }
  }
}

function AttemptCustomerLogin()
{
  Require 'dbConnection.php';

  if (isset($_POST["customerLoginSubmit"]))
  {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $sql = "SELECT * FROM DPH_Customer WHERE Username = :username";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute(['username' => $username]);

    if($success && $stmt->rowCount() > 0)
    {
      $result = $stmt->fetch();

      if ($result && password_verify($password, $result['Password']))
      {
        $_SESSION['LoggedIn'] = true;
        $_SESSION['userid'] = $result['Customer_ID'];
        $_SESSION['username'] = $result['Username'];
        $_SESSION['firstname'] = $result['First_Name'];
      }
      else
      {
          echo "Password is invalid";
      }
    }
    else
    {
      echo" Record not found";
    }
  }
}

function AttemptLogOut()
{
    session_start(); // Start Session / Resume Current Session
    session_destroy(); // Destroy Session
    header("Location: ../View/index.php"); // Redirect to index page
}

function AttemptEmployeeLogin()
{
  Require 'dbConnection.php';

  if (isset($_POST["employeeLoginSubmit"]))
  {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $sql = "SELECT * FROM DPH_Employee WHERE Username = :username";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute(['username' => $username]);

    if($success && $stmt->rowCount() > 0)
    {
      $result = $stmt->fetch();

      if ($result && password_verify($password, $result['Password']))
      {
        $_SESSION['LoggedIn'] = true;
        $_SESSION['userid'] = $result['User_ID'];
        $_SESSION['username'] = $result['Username'];
        $_SESSION['jobrole'] = $result['Job_Role'];
      }
      else
      {
          echo "Password is invalid";
      }
    }
    else
    {
      echo" Record not found";
    }
  }
}

function AttemptInsertMovie()
{
    Require 'dbConnection.php';

    // Checks if submit button has been pressed
    if (isset($_POST['insertMovieSubmit']))
    {

        $file = $_FILES['image_link'];

        $fileName = $_FILES['image_link']['name'];
        $fileTmpName = $_FILES['image_link']['tmp_name'];
        $fileSize = $_FILES['image_link']['size'];
        $fileError = $_FILES['image_link']['error'];
        $fileType = $_FILES['image_link']['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = array('jpg', 'jpeg', 'png');

        // Checks if file is an allowed type
        if (in_array($fileActualExt, $allowed))
        {
            // Checks there are no errors
            if ($fileError === 0)
            {
                // Checks file size is below stated value
                if ($fileSize < 1000000)
                {
                    // Gives file a unique id to stop overwriting of files with same name
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    // Determines file location
                    $fileDestination = '../View/images/' . $fileNameNew;
                    // Sends file to specified location
                    move_uploaded_file($fileTmpName, $fileDestination);

                    // Once complete carry out the INSERT statement to database
                    $title = (filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
                    $video = (filter_input(INPUT_POST, 'video', FILTER_SANITIZE_STRING));
                    $image = $fileDestination;
                    $description = (filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
                    $releaseDate = (filter_input(INPUT_POST, 'releaseDate', FILTER_SANITIZE_STRING));
                    $ageRating = (filter_input(INPUT_POST, 'ageRating', FILTER_SANITIZE_STRING));
                    $runTime = (filter_input(INPUT_POST, 'runTime', FILTER_SANITIZE_NUMBER_INT));
                    $genre = (filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING));
                    $director = (filter_input(INPUT_POST, 'director', FILTER_SANITIZE_STRING));
                    $actors = (filter_input(INPUT_POST, 'actors', FILTER_SANITIZE_STRING));
                    $language = (filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING));
                    $starRating = (filter_input(INPUT_POST, 'starRating', FILTER_SANITIZE_STRING));

                    $YoutubeURL = "watch?v=";
                    $embededURL = "embed/";
                    $video = str_replace($YoutubeURL, $embededURL, $video); // replace part of the Youtube URL to make it an embeded video for easy use by users.
                    $releaseDate = date("d-m-Y", strtotime($releaseDate)); // Force date format DD/MM/YYYY

                    switch($starRating)
                    {
                      case "0":
                        $starRating = "../View/images/0_star.png";
                        break;
                      case "1":
                        $starRating = "../View/images/1_star.png";
                        break;
                      case "2":
                        $starRating = "../View/images/2_star.png";
                        break;
                      case "3":
                        $starRating = "../View/images/3_star.png";
                        break;
                      case "4":
                        $starRating = "../View/images/4_star.png";
                        break;
                      case "5":
                        $starRating = "../View/images/5_star.png";
                        break;
                    }

                    switch($ageRating)
                    {
                      case "U":
                        $ageRating = "../View/images/U.png";
                        break;
                      case "PG":
                        $ageRating = "../View/images/PG.png";
                        break;
                      case "12A":
                        $ageRating = "../View/images/12A.png";
                        break;
                      case "15":
                        $ageRating = "../View/images/15.png";
                        break;
                      case "18":
                        $ageRating = "../View/images/18.png";
                        break;
                    }

                    if(isset($_POST['threeD']))
                    {
                      $threeD = 1;
                    }
                    else
                    {
                      $threeD = 0;
                    }

                    if(isset($_POST['audioDescribed']))
                    {
                      $audioDescribed = 1;
                    }
                    else
                    {
                      $audioDescribed = 0;
                    }

                    $query = $pdo->prepare
                    ("

                    INSERT INTO DPH_Movie (Title, Video_Link, Image_Link, Description, Release_Date, Age_Rating, RunTime, Genre, Director, Actors, Language, 3D, Audio_Described, Star_Rating)
                    VALUES (:title, :video, :image, :description, :releaseDate, :ageRating, :runTime, :genre, :director, :actors, :language, :threeD, :audioDescribed, :starRating)

                    ");


                    $success = $query->execute
                    ([
                      'title' => $title,
                      'video' => $video,
                      'image' => $image,
                      'description' => $description,
                      'releaseDate' => $releaseDate,
                      'ageRating' => $ageRating,
                      'runTime' => $runTime,
                      'genre' => $genre,
                      'director' => $director,
                      'actors' => $actors,
                      'language' => $language,
                      'threeD' => $threeD,
                      'audioDescribed' => $audioDescribed,
                      'starRating' => $starRating
                    ]);

                    $count = $query->rowCount();
                    if($count > 0)
                    {
                      echo "Insert Successful";
                    }
                    else
                    {
                      echo "Insert Failed";
                      echo $query -> errorInfo()[2];
                    }
                }
                else
                {
                    echo "Your file is too big!";
                }
            }
            else
            {
                echo "There was an error uploading your file!";
            }
        }
        else
        {
            echo "You cannot upload files of this type!";
        }
    }
}

function RemoveMovieByID($movieid)
{
  require 'dbConnection.php';

  /*
  // IFWE IMPLEMENT COMMENTS WILL NEED TO DELETE THIS BEFORE THE MOVIE.
  $stmtComments = $pdo->prepare
  (
    "DELETE FROM DPH_Comments WHERE Movie_ID = :movieid"
  );

  $success = $stmtComments->execute
  ([
    'movieid' => $movieid
  ]);

  if($success && $stmtComments->rowCount() > 0)
  {
    echo 'Successful';
  }
  else
  {
    echo 'Failed';
  }*/

    $stmt = $pdo->prepare
    (
      "DELETE FROM DPH_Movie WHERE Movie_ID = :movieid"
    );

    $success = $stmt->execute
    ([
      'movieid' => $movieid
    ]);

    if($success && $stmt->rowCount() > 0)
    {
      echo 'Successful';
    }
    else
    {
      echo 'Failed';
    }
}

function getMovieByID($movieid)
{
  require 'dbConnection.php';

  $query = $pdo->prepare
  ("
  SELECT * FROM DPH_Movie WHERE Movie_ID = :movieid LIMIT 1
  ");

  $success = $query->execute
  ([
    'movieid' => $movieid
  ]);

  if($success && $query->rowCount() > 0)
  {
    $row = $query->fetch();
  }
  else
  {
    echo "Nope";
  }

  return json_encode($row);
}

function AttemptPromoteEmployeeByID($employeeid)
{
  require 'dbConnection.php';

  $checkSql = "SELECT Job_Role FROM DPH_Employee WHERE Employee_ID = :employeeid";

  $checkStmt = $pdo->prepare($checkSql);
  $checkSuccess = $checkStmt->execute(['employeeid' => $employeeid]);

  if($checkSuccess && $checkStmt->rowCount() > 0)
  {
    $oldJobRole = $checkStmt->fetch();
  }
  else
  {
    echo 'Check Failed';
  }

  $embededURLJobRole = $oldJobRole['Job_Role'];

  $canDemote = false;
  if($embededURLJobRole == "employee")
  {
    $canDemote = true;
    $embededURLJobRole = "supervisor";
  }
  elseif($embededURLJobRole == "supervisor")
  {
    $canDemote = true;
    $embededURLJobRole = "manager";
  }
  elseif($embededURLJobRole == "manager")
  {
    echo 'Cannot Promote manager any higher than a manager';
  }

  if($canPromote = true)
  {
    $query = $pdo->prepare
    ("
    UPDATE DPH_Employee SET Job_Role = :newJobRole WHERE Employee_ID = :employeeid
    ");

    $success = $query->execute
    ([
      'newJobRole' => $embededURLJobRole,
      'employeeid' => $employeeid
    ]);

    if($success && $query->rowCount() > 0)
    {
      echo 'Done';
    }
    else
    {
      echo "Nope";
      echo $query -> errorInfo()[2];
    }
  }
}

function AttemptDemoteEmployeeByID($employeeid)
{
  require 'dbConnection.php';

  $checkSql = "SELECT Job_Role FROM DPH_Employee WHERE Employee_ID = :employeeid";

  $checkStmt = $pdo->prepare($checkSql);
  $checkSuccess = $checkStmt->execute(['employeeid' => $employeeid]);

  if($checkSuccess && $checkStmt->rowCount() > 0)
  {
    $oldJobRole = $checkStmt->fetch();
  }
  else
  {
    echo 'Check Failed';
  }

  $embededURLJobRole = $oldJobRole['Job_Role'];

  $canDemote = false;
  if($embededURLJobRole == "employee")
  {
    echo 'Cannot Demote employee any lower than an employee';
  }
  elseif($embededURLJobRole == "supervisor")
  {
    $canDemote = true;
    $embededURLJobRole = "employee";
  }
  elseif($embededURLJobRole == "manager")
  {
    $canDemote = true;
    $embededURLJobRole = "supervisor";
  }

  if($canPromote = true)
  {
    $query = $pdo->prepare
    ("
    UPDATE DPH_Employee SET Job_Role = :newJobRole WHERE Employee_ID = :employeeid
    ");

    $success = $query->execute
    ([
      'newJobRole' => $embededURLJobRole,
      'employeeid' => $employeeid
    ]);

    if($success && $query->rowCount() > 0)
    {
      echo 'Done';
    }
    else
    {
      echo "Nope";
      echo $query -> errorInfo()[2];
    }
  }
}

function AttemptDeleteEmployee($employeeid)
{
  require 'dbConnection.php';

  $query = $pdo->prepare
  ("
  DELETE FROM DPH_Employee WHERE Employee_ID = :employeeid
  ");

  $success = $query->execute
  ([
    'employeeid' => $employeeid
  ]);

  if($success && $stmt->rowCount() > 0)
  {
    echo 'Successful';
  }
  else
  {
    echo 'Failed';
  }
}

function GetAllEmployees()
{
  require 'dbConnection.php';

  $sql = "SELECT Employee_ID, First_Name, Surname, Username, Job_Role FROM DPH_Employee";

  $stmt = $pdo->prepare($sql);
  $result = $stmt->fetch();
  $success = $stmt->execute();

  if($success && $stmt->rowCount() > 0)
  {
    //  convert to JSON
    $rows = array();
    while($r = $stmt->fetch())
    {
      $rows[] = $r;
    }
    return json_encode($rows);
  }
}

//REST API SEARCHING
function OMDBSearch()
{
  if(isset($_POST['search-by-title-button']))
  {
    $searchItem = (filter_input(INPUT_POST, 'searchTitle', FILTER_SANITIZE_STRING)); //Sanitize the string
    $searchItem = str_replace(' ', '+', $searchItem); //Replace any whitespace with '+' symbols to work on a url
    $listOfMovies = file_get_contents("http://www.omdbapi.com/?apikey=1917d84&type=movie&s=".$searchItem); //Get a list of search results from the OMDb API
    return $listOfMovies; //Return the results
  }
}

//Ticket Booking
function GenerateTicketCode()
{
  $day = date('d');
  $month = date('m');
  $year = date('y');
  $hour = date('H');
  $uderid = $_SESSION['userid'];

  $unencryptedCode = $day.$month.$year.$hour.$userid;

  return $code = dechex($unencryptedCode); // Decimal: '$unencryptedCode' changed to Hexideciaml: '$code'
}

//Getting Tickets for a certain user
function GetUserTickets($sessionid)
{
  require 'dbConnection.php';

  $query = $pdo->prepare
  ("
    SELECT t.Ticket_ID, t.Code, t.Premium_Ticket, t.Movie_Title, t.Showing_Date, t.Showing_Time, t.Screen_ID, p.PayPal_Email
    FROM DPH_Ticket t JOIN  DPH_Payment p
    ON (t.Payment_ID = p.Payment_ID)
    WHERE (p.Customer_ID = :sessionid)
    ORDER BY t.Showing_Date desc, t.Showing_Time desc
  ");

  $success = $query->execute
  ([
    'sessionid' => $sessionid
  ]);

  if($success && $query->rowCount() > 0)
  {
    //  convert to JSON
    $rows = array();
    while($r = $query->fetch())
    {
      $rows[] = $r;
    }
    return json_encode($rows);
  }
}

function GetTicketInfo()
{
  require 'dbConnection.php';

  $sql = "SELECT * FROM DPH_TicketPrice";

  $stmt = $pdo->prepare($sql);
  $result = $stmt->fetch();
  $success = $stmt->execute();

  if($success && $stmt->rowCount() > 0)
  {
    //  convert to JSON
    $rows = array();
    while($r = $stmt->fetch())
    {
      $rows[] = $r;
    }
    return json_encode($rows);
  }
/*
  if($adultMovieType == "premium")
  {
    $adultPrice = 10.0;
  }
  else
  {
    $adultPrice = 8.50;
  }

  if($childMovieType == "premium")
  {
    $childPrice = 6.50;
  }
  else
  {
    $childPrice = 5.0;
  }

  if($studentMovieType == "premium")
  {
    $studentPrice = 8.0;
  }
  else
  {
    $studentPrice = 6.50;
  }

  if($seniorMovieType == "premium")
  {
    $seniorPrice = 8.0;
  }
  else
  {
    $seniorPrice = 6.50;
  }

  if($familyMovieType == "premium")
  {
    $familyPrice = 26.50;
  }
  else
  {
    $familyPrice = 22.0;
  }

  $adultCost = $adultPrice * $adultQuantity;
  $childCost = $childPrice * $childQuantity;
  $studentCost = $studentPrice * $studentQuantity;
  $seniorCost = $seniorPrice * $seniorQuantity;
  $familyCost = $familyPrice * $familyQuantity;

  $totalCost = $adultCost + $childCost + $studentCost + $seniorCost + $familyCost;

  return array($adultPrice, $adultQuantity, $childPrice, $childQuantity, $studentPrice, $studentQuantity, $seniorPrice, $seniorQuantity, $familyPrice, $familyQuantity, $totalCost);
  */
}

?>
