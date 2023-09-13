<?php
include_once 'components/headers.php';
include_once 'resources/db.php';
include_once 'resources/session.php';
include_once 'resources/utilities.php';



if(isset($_SESSION['id'])){
    if($_SERVER['REQUEST_METHOD']==="GET"){
        $id=$_SESSION['id'];
        // Get "$username" from db
        $sql="SELECT * FROM users WHERE id = :id";
        $statement=$connection->prepare($sql);
        $statement->execute(array(':id'=>$id));
        while($res= $statement->fetch()){
            $category_owner=$res['username'];
        }

        $category_active=$_GET['category'];
        if($category_active==='all'){
            try {
                $sql="DELETE FROM show_posts_by_category WHERE category_owner='$category_owner'";
                $statement=$connection->query($sql);
                header("location:index.php");
            } catch (PDOException $ex) {
                echo "Error Exception ".$ex->getMessage();
            }
        }else{
            echo "do traitement";
            $sql="SELECT COUNT(*) as isActive FROM show_posts_by_category WHERE category_owner='$category_owner'";
            $statement=$connection->query($sql);
            if(!$statement){
                die("invalid qurey for CountCatego!").$connection->getMessage();
            }
            while($res=$statement->fetch()){
                $isActive=$res['isActive'];
                if($isActive!=1){
                    echo "create";
                    try {
                        $sql="INSERT INTO show_posts_by_category (category_active,category_owner) 
                        VALUES (:category_active,:category_owner)";
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':category_active'=>$category_active,':category_owner'=>$category_owner));
                        if($statement->rowCount()==1){
                            header("Location:index.php");
                        }
                    } catch (PDOException $ex) {
                        echo "Error Exception ".$ex->getMessage();
                    }
                }else{
                    echo "update";
                    try {
                        $sql="UPDATE show_posts_by_category SET category_active=:category_active WHERE category_owner='$category_owner'";
                        $statement=$connection->prepare($sql);
                        $statement->execute(array(':category_active'=>$category_active));
                        if($statement->rowCount()==1){
                            header("Location:index.php");
                        }else{
                            header("location:index.php");
                        }
                    } catch (PDOException $ex) {
                        echo "Error Exception ".$ex->getMessage();
                    }

                }
            }

        }

    }
}else{
    redirect("login");
}

?>