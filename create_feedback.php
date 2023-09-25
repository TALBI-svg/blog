<?php
include_once 'components/headers.php';
include_once 'resources/session.php';
include_once 'resources/db.php';
include_once 'resources/utilities.php';

if(isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $username=getUsername($connection,$id);

    $sql="SELECT * FROM users WHERE username='$username'";
    $statement=$connection->query($sql);
    if(!$statement){
        die('invalid query!').$connection->getMessage();
    }
    while($res=$statement->fetch()){
        $feedback_owner_img=$res['profile_pic'];
        $feedback_owner_title=$res['title'];
    }

    if(isset($_POST['feedbackBtn'])){
        $form_errors=array();
        $required_fields=array('title','message');
        $form_errors=array_merge($form_errors,check_empty_fields($required_fields));

        $fields_to_check_length=array('title'=>5,'message'=>15);
        $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));
        
        if(empty($form_errors)){
            $title=$_POST['title'];
            $message=$_POST['message'];
            try {
                $sql="INSERT INTO feedbacks (feedback_owner,feedback_owner_img,feedback_owner_title,feedback_date,title,message) 
                      VALUES (:feedback_owner,:feedback_owner_img,:feedback_owner_title,now(),:title,:message) ";
                $statement=$connection->prepare($sql);
                $statement->execute(array(':feedback_owner'=>$username,':feedback_owner_img'=>$feedback_owner_img,':feedback_owner_title'=>$feedback_owner_title,':title'=>$title,':message'=>$message));
                if($statement->rowCount()==1){
                    $result=falshMessage('Feedback send successfully','Pass');
                }
            } catch (PDOException $ex) {
                $result=falshMessage('Error Exception '.$ex->getMessage());
            }
        }else{
            if(count($form_errors)==1){
                $result=falshMessage('One error in the form');
    
            }else{
                $result=falshMessage('There were '.count($form_errors).' errors in the form');
    
            }
        }
    }
}else{
    redirect('login');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="container CREATEFEEDBACK">
       <div class="container">
            <div class="row title-area mt-3 mb-2">
                <h3 class="d-flex align-items-center"><a class="nav-link pe-2" href="profile.php">Profile</a> > <a class="nav-link px-2" href="user_settings.php">Settings</a> > <a class="nav-link px-2" href="feedback.php">Feedback</a> > <a class="nav-link px-2" href="create_feedback.php">Create Feedback</a></h3>
            </div> 
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <form action="" method="post">
                        <div class="mb-3">
                          <input type="text" name="title" class="form-control" value="" placeholder="Feedback Title">
                        </div>
                        <div class="mb-3">
                          <textarea class="form-control" name="message" value="" placeholder="Feedback Message" id="floatingTextarea"  style="height: 200px"></textarea>
                        </div>
                        <div class="row">
                          <div class="d-flex justify-content-between align-items-center">
                              <button type="submit" name="feedbackBtn" class="signupBtn btn btn-warning">Send</button>
                          </div>
                        </div>
                    </form>
                    <?php if(isset($result)) echo $result;?>
                    <?php if(isset($form_errors)) echo show_errors($form_errors);?>
                </div>
            </div>
       </div>
    </div>
    
</body>
</html>

