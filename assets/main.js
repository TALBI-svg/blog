// "my_posts.php" deletePost alert message 
let deleteAlert=document.querySelectorAll('.deleteBtn')
deleteAlert.forEach(function (ele){
    let element=ele
    element.addEventListener('click', function(){
        let state=confirm('Are you sur to delete this post')
        if(state == true){
            element.href
        }else{
            element.href='my_posts.php'
        }
    })
});


let updateBtn=document.querySelectorAll('#update_comment')
updateBtn.forEach(function (ele){
    ele.addEventListener('click',function(e){
        let element=e.target
        let upadateArea=element.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.nextElementSibling
        upadateArea.style.display='block'
        // console.log(upadateArea)
    })
})



// let validate_Email_Inpt=document.querySelector('#email')
// validate_Email_Inpt.addEventListener('change',function(){
//     let email=validate_Email_Inpt.value 
//     let admin_regex=/^(\w{5,10}.admin)@(gmail|email|hotmail|outlook).(com|ma|fr|net)$/ig
//     let manager_regex=/^(\w{5,10}.manager)@(gmail|email|hotmail|outlook).(com|ma|fr|net)$/ig
//     let user_regex=/^(\w{5,10})@(gmail|email|hotmail|outlook).(com|ma|fr|net)$/ig
    
//     if(email.match(admin_regex)){
//         console.log('admin redirection')

//     }else if(email.match(manager_regex)){

//         console.log('manager redirection')

//     }else if(email.match(user_regex)){

//         console.log('user redirection')
//     }else{
//         console.log('email form not valide!')
//     }
// })
