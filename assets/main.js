// "my_posts.php" deletePost alert message 
let deletePost=document.querySelectorAll('.deleteBtn')
deletePost.forEach(function (ele){
    ele.addEventListener('click', function(){
        let state=confirm('Are you sur to delete this post ?')
        if(state == true){
            ele.href
        }else{
            ele.href='my_posts.php'
        }
    })
});

// "panel_options.php" deleteUser alert message 
let deleteUser=document.querySelectorAll('#deleteUserBtn')
deleteUser.forEach(function (ele){
    ele.addEventListener('click', function(){
        let state=confirm('Are you sur to delete this user ?')
        if(state === true){
            ele.href
        }else{
            ele.href='panel_options.php'
        }
    })
});


// "panel_options.php" defaultPassword alert message 
let defaultPassword=document.querySelectorAll('#defaultPasswordBtn')
defaultPassword.forEach(function (ele){
    ele.addEventListener('click', function(){
        let state=confirm('Are you sur to set default password for this user ?')
        if(state == true){
            ele.href
        }else{
            ele.href='panel_options.php'
        }
    })
});

// "panel_options.php" posts section deletePost alert message 
let deletePost_admin=document.querySelectorAll('#deletePost_adminBtn')
deletePost_admin.forEach(function (ele){
    ele.addEventListener('click', function(){
        let state=confirm('Are you sur to delete this post definitelly ?')
        if(state == true){
            ele.href
        }else{
            ele.href='panel_options.php'
        }
    })
});

// "post_details.php" updateComment alert message 
let updateBtn=document.querySelectorAll('#update_comment')
updateBtn.forEach(function (ele){
    ele.addEventListener('click',function(e){
        let element=e.target
        let upadateArea=element.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.nextElementSibling
        upadateArea.style.display='block'
        // console.log(upadateArea)
    })
})

