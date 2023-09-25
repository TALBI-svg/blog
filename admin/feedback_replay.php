<?php
include_once 'admin_components/headers_admin.php';


if(isset($_SESSION['admin_id'])){
    // $admin_id=$_SESSION['admin_id'];
    // $username_admin=getAdminUsername($connection,$admin_id);
    $username_admin="Admin";

    if(isset($_POST['replayBtn'])){
        $form_errors=array();
        $required_fields=array('message');
        $form_errors=array_merge($form_errors,check_empty_fields($required_fields));
        $fields_to_check_length=array('message'=>15);
        $form_errors=array_merge($form_errors,check_min_length($fields_to_check_length));
        if(empty($form_errors)){
            $message=$_POST['message'];
            $feedback_id=$_POST['feedback_id'];

            $sql="SELECT * FROM feedbacks WHERE id='$feedback_id'";
            $statement=$connection->query($sql);
            if(!$statement){
                die('invalid query!').$connection->getMessage();
            }
            while($res=$statement->fetch()){
                $isReplayed=$res['replayed'];
                if($isReplayed==='false'){
                    // echo "change it to true";
                    function getFeedbackOwnerName($conn,$id){
                        $sql="SELECT * FROM feedbacks WHERE id='$id'";
                        $statement=$conn->query($sql);
                        if(!$statement){
                            die('invalid query!').$conn->getMessage();
                        }
                        while($res=$statement->fetch()){
                            $feedback_owner=$res['feedback_owner'];
                            return $feedback_owner;
                        }
                    }
                    
                    function setReplayedToTrue($conn,$id){
                        try{
                            $replayed='true';
                            $sql="UPDATE feedbacks SET replayed =:replayed WHERE id =:id";
                            $statement=$conn->prepare($sql);
                            $statement->execute(array(':replayed'=>$replayed,':id'=>$id));

                        }catch(PDOException $ex){
                            $result=falshMessage('Error Exception '.$ex->getMessage());
                        }
                    }
                    
                    try {
                        $feedback_owner=getFeedbackOwnerName($connection,$feedback_id);
                        $content=$feedback_owner.', '.$message;
                    
                        $sql="INSERT INTO feedbacks_replay (replay_message, replay_owner, replay_date, feedback_replayed_on_id) 
                              VALUES (:replay_message, :replay_owner, now(), :feedback_replayed_on_id) ";
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':replay_message'=>$content, ':replay_owner'=>$username_admin, ':feedback_replayed_on_id'=>$feedback_id));
                        if($statement->rowCount()==1){
                            setReplayedToTrue($connection,$feedback_id);
                            $result=falshMessage('Feedback replay sent successfully','Pass');
                        }
                    } catch (PDOException $ex) {
                        $result=falshMessage('Error Exception '.$ex->getMessage());
                    }
                }else{
                    // echo "back to feedback page";
                    header("location: panel_options.php");
                }
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
    header("location: ../login.php");
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
                <h3 class="d-flex align-items-center"><a class="nav-link pe-2" href="panel_options.php">Feedback</a> > <a class="nav-link px-2" href="feedback_replay.php">Feedback Replay</a></h3>
            </div> 
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <form action="" method="post">
                        <div class="mb-3">
                            <?php
                            if($_SERVER['REQUEST_METHOD']==='GET'){
                                $feedback_id=$_GET['feedback_id'];
                            }
                            ?>
                            <input type="text" name="feedback_id" hidden value="<?php echo $feedback_id;?>">
                        </div>
                        <div class="mb-3">
                          <textarea class="form-control" name="message" value="" placeholder="Feedback Message" id="floatingTextarea"  style="height: 200px"></textarea>
                        </div>
                        <div class="row">
                          <div class="d-flex justify-content-between align-items-center">
                              <button type="submit" name="replayBtn" class="signupBtn btn btn-warning">Send</button>
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

