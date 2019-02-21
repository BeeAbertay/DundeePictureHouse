<?php
/*
    Description: Dundee Picture House API file
    Collection of functions used in the system

    Author: David McRae, Aaron Hay
*/
//Get All Movies
function GetAllMovies()
{
    require 'dbConnection.php';;

    $dateFilter = '';
    if (filter_input(INPUT_POST, "month", FILTER_SANITIZE_STRING))
    {
        $month = filter_input (INPUT_POST, "month", FILTER_SANITIZE_STRING);
        if ($month == "0")
        {
            $dateFilter = '';
        }
        else
        {
            $dateFilter = "WHERE MONTH(Release_Date) = '$month'";
        }
    }

    $sql = "SELECT * FROM DPH_Movie $dateFilter ORDER BY Release_Date desc";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->fetch();
    $success = $stmt->execute();

    if($success && $stmt->rowCount() > 0)
    {
      //  convert to JSON
      $rows = array();
      while($r = $result->fetch())
      {
        $rows[] = $r;
      }
      return json_encode($rows);
    }
}

//  Function to create a new customer
function CreateNewCustomer()
{
  Require 'dbConnection.php';

  if (isset($_POST["registerSubmit"]))
  {
    $firstName = (filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    $surname = (filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING));
    $email = (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $username = (filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = (filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    // Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Create SQL Template
    $query = $pdo->prepare
    ("

    INSERT INTO DPH_Customer (First_Name, Surname, Email, Username, Password)
    VALUES( :firstName, :surname, :email, :username, :password)

    ");

    $success = $query->execute
    ([
      'firstName' => $firstName,
      'surname' => $surname,
      'email' => $email,
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

// Function to create a new employee
function CreateNewEmployee()
{
  Require 'dbConnection.php';

  if (isset($_POST["registerSubmit"]))
  {
    $jobrole = (filter_input(INPUT_POST, 'jobrole' , FILTER_SANITIZE_STRING));
    $firstName = (filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    $surname = (filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_STRING));
    $username = (filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = (filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    //Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Create SQL Template
    $query = $pdo->prepare
    ("

    INSERT INTO DPH_Emplpoyee (Job_Role, First_Name, Surname, Username, Password)
    VALUES( :jobrole :firstName, :surname, :email, :username, :password)

    ");

    $success = $query->execute
    ([
      'firstName' => $firstName,
      'surname' => $surname,
      'email' => $email,
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

// Function attempts to login a customer
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
        $_SESSION['userid'] = $result['User_ID'];
        $_SESSION['username'] = $result['Username'];
        $_SESSION['firstname'] = $result['First_name'];
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

// Function attempts to login an employee
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
?>
