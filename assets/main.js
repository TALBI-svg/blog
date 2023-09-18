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

// hideTable
// whenSearch
// whenSearchInpt
// form

let searchInpt=document.querySelector('#whenSearchInpt')
let form=document.querySelector("#sendRequestForm")
form.addEventListener('submit', function(){
    // if(searchInpt.value!==""){
    //     return true
    // }else{
    //     return false
    // }
    console.log('ok')
})
