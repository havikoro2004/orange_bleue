import './styles/app.scss';
import { Tooltip, Toast, Popover } from 'bootstrap';
import './bootstrap';
import axios from 'axios';

// Remove resend forms after refresh page script
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
if(performance.navigation.type == 2){
    location.reload(true);
}

const cardClient = document.getElementById('clientCard')
if (cardClient){
    for (let i = 0 ; i < cardClient.children.length ; i++){
        const switchBtn = cardClient.children[i].getElementsByClassName('form-check-input')
        const modalBtn = cardClient.children[i].getElementsByClassName('modal-footer')
        const modalBody = cardClient.children[i].getElementsByClassName('modal-body')
        const validBtn = modalBtn[0].children[1];
        switchBtn[0].addEventListener('click',(e)=>{
            e.preventDefault()
            validBtn.addEventListener('click',()=>{
                modalBody[0].innerHTML ="<div class=\"spinner-border text-primary\" role=\"status\">\n" +
                    "</div>"
                axios.post('/client/'+validBtn.id+'/active', {
                })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                location.reload();

            })
        })
    }
}
const pageOneInput = document.getElementById('activBtnOnePage')
if (pageOneInput){
    const btnActiveOnePage = pageOneInput.children[0]
    const modalBtn = pageOneInput.getElementsByClassName('modal-footer')[0].children[1]
    const modalOnePageBody =  pageOneInput.getElementsByClassName('modal-body')
    btnActiveOnePage.addEventListener('click',(e)=>{
        e.preventDefault()
        modalBtn.addEventListener('click',()=>{
            modalOnePageBody[0].innerHTML ="<div class=\"spinner-border text-primary\" role=\"status\">\n" +
                "</div>"

            axios.post('/client/'+modalBtn.id+'/active', {
            })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            location.reload();
        })
    })
}
if (document.getElementById('permissionCollaps')){
    const permissionCollaps = document.getElementById('permissionCollaps')
    const switchesBtns = permissionCollaps.getElementsByClassName('form-check-input')
    const permissionModal = document.getElementById('permissionsModal')
    const editPermissionBtn = permissionModal.getElementsByClassName('modal-footer')[0].children[1]
    const modalPermissionBody = permissionModal.getElementsByClassName('modal-body')[0]
    for (let i = 0 ; i < switchesBtns.length ; i++){
        switchesBtns[i].addEventListener('click',(e)=>{
            e.preventDefault()
            editPermissionBtn.addEventListener('click',()=>{
                modalPermissionBody.innerHTML="<div class=\"spinner-border text-primary text-center\" role=\"status\">\n" +
                    "</div>"
                axios.post('/permission/edit/'+ switchesBtns[i].name, {
                    inputName: switchesBtns[i].value,
                })
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                location.reload();
            })

        })
    }

}