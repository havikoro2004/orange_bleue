import './styles/app.scss';
import { Tooltip, Toast, Popover } from 'bootstrap';
import './bootstrap';
import axios from 'axios';

// Remove resend forms after refresh page script
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
//Clear les formulaire quand on click sur precedent ou suivant sur le navigateur
window.onpageshow = function(event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === 'back_forward') {
        location.reload();
    }
}

// Activer ou désactiver les clients dans la liste de la page d'accueil
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

// Activer désactiver le client dans sa page profil
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

//Activer ou désactiver les permissions globales d'un client dans sa page profil
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

// Activer ou désactiver une branche d'un client dans sa page profil
if (document.getElementById('branchCard')){
    const activeBranchModal = document.getElementById('branchCard')
    for (let i=0 ; i< activeBranchModal.children.length ; i++){
       const activeBtn = activeBranchModal.children[i].getElementsByClassName('activeBranch')
            activeBtn[0].addEventListener('click',(e)=>{
                console.log(activeBtn)
                e.preventDefault()
                const footer = activeBranchModal.children[i].getElementsByClassName('modal-footer')[0].children[1]
                const body = activeBranchModal.children[i].getElementsByClassName('modal-body')[0]
                footer.addEventListener('click',()=>{
                    body.innerHTML="<div class=\"spinner-border text-primary text-center\" role=\"status\">\n" +
                        "</div>"
                    axios.post('/branch/'+ activeBtn[0].name+'/edit', {
                    })
                        .then(function (response) {
                            console.log(response);
                            document.body.scrollTop = document.documentElement.scrollTop = 0;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    location.reload();
                })
            })
    }

}

// Activer ou désactiver des permissions d'une branche dans la page profil d'un client
if(document.getElementById('branchCard')){
    const branchCard = document.getElementById('branchCard')
    for (let i=0;i<branchCard.children.length;i++){
        const cards = branchCard.children[i].getElementsByClassName('editPermissionsBranch')[0].getElementsByClassName('form-check-input')
        for (let j=0;j<cards.length;j++){
            cards[j].addEventListener('click',(e)=>{
                e.preventDefault()
                const modal = document.getElementById('editBranchPermissions')
                const footer = modal.getElementsByClassName('modal-footer')[0].children[1]
                const idClient = branchCard.getAttribute('class');
                const body = modal.getElementsByClassName('modal-body')[0]
                const idBranch = cards[j].name
                footer.addEventListener('click',()=>{
                    body.innerHTML="<div class=\"spinner-border text-primary text-center\" role=\"status\">\n" +
                        "</div>"
                    axios.post('/permission/'+idClient+'/'+idBranch+'/edit', {
                        inputName: cards[j].value,
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
}

// Mettre l'input email d'un client en mode reaOnly pour ne pas pouvoir le modifier
if (document.getElementById('modifClient')){
    const sectionPartenaireEdit = document.getElementById('modifClient')
    sectionPartenaireEdit.getElementsByTagName('form')[0].children[6].children[1].readOnly=true
}
// Mettre l'input email d'une structure en mode reaOnly pour ne pas pouvoir le modifier
if (document.getElementById('editBranch')){
    const sectionBranchEdit =document.getElementById('editBranch')
    sectionBranchEdit.getElementsByTagName('form')[0].children[2].readOnly=true
}

// Modifier le design de la div erreur de type 'Alert' dans le champs modifier mot de passe
if (document.getElementById('errorsNewPassword')){
    const errorsNewPassword = document.getElementById('errorsNewPassword')
    errorsNewPassword.getElementsByTagName('ul')[0].setAttribute('class','list-unstyled m-0 p-0')
}

<<<<<<< HEAD
// Afficher les clients actifs seulement
if (document.getElementById('actifs')){
    var actifs = document.getElementById('actifs')
    actifs.addEventListener('click',()=>{
        actifs.value = 1
        inactifs.value=0
        tous.value=0
        axios.get('/find_actifs', {
            headers : {
                'X-Requested-With' : 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                document.getElementById('ajaxContent').innerHTML=response.data.ajaxContent
                document.getElementById('paginator').innerHTML=response.data.paginator
            })
    })
}

// Afficher les clients inactifs seulement
if (document.getElementById('inactifs')){
    var inactifs = document.getElementById('inactifs')
    inactifs.addEventListener('click',()=>{
        actifs.value = 0
        inactifs.value=1
        tous.value=0
        axios.get('/find_inactif', {
            headers : {
                'X-Requested-With' : 'XMLHttpRequest'
=======
// Pagination Script
let nbrPage = 2
const plusBtn = document.getElementById('plusBtn')
const seeMoreBtn = document.getElementById('seeMore')
const actifsCheckbox = document.getElementById('actifs')
const inactifCheckbox = document.getElementById('inactifs')
const touCheckbox = document.getElementById('tous')
const contentDiv =document.getElementById('clientCard')
const footer = document.getElementById('footer')
let arrayContent=[...contentDiv.children]
let arraySliced = arrayContent.slice(0,nbrPage)
const alertNotFoundFilter = document.getElementById('alertNotFoundFilter')
const rechercheInput = document.getElementById('recherche')

// Afficher tous les partenaires
touCheckbox.addEventListener('click',()=>{
    rechercheInput.value=''
    alertNotFoundFilter.innerHTML=''
    seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
    nbrPage = 2
    arrayContent=[...contentDiv.children]
    arraySliced = arrayContent.slice(0,nbrPage)
    arrayContent.forEach(item=>{
        if (arraySliced.includes(item)){
            item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
        } else {
            item.setAttribute('class','d-none')
        }
    })
    if (nbrPage >= arrayContent.length){
        seeMoreBtn.setAttribute('class','d-none')
    }
    footer.scrollIntoView()
    finByLetter()
})

// Afficher les partenaires inactifs
inactifCheckbox.addEventListener('click',()=>{
    rechercheInput.value=''
    alertNotFoundFilter.innerHTML=''
    seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
    nbrPage = 2
    arrayContent=[]
    for (let i = 0 ; i < contentDiv.children.length ; i++){
        if (!contentDiv.children[i].getElementsByTagName('input')[0].checked){
            arrayContent.push(contentDiv.children[i])
            contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
            console.log(arrayContent)
        } else {
            contentDiv.children[i].setAttribute('class','d-none')
        }
    }
    arraySliced = arrayContent.slice(0,nbrPage)
    arrayContent.forEach(item=>{
        if (arraySliced.includes(item)){
            item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
        } else {
            item.setAttribute('class','d-none')
        }
    })
    if (nbrPage >= arrayContent.length){
        seeMoreBtn.setAttribute('class','d-none')
    }
    footer.scrollIntoView()

    // Recherche par lettre
    rechercheInput.addEventListener('keyup',()=>{
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-none')
        let arrayFindByLetter=[]
        for ( let i = 0 ; i < contentDiv.children.length ; i++){
            let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].textContent
            if (nameField.includes(rechercheInput.value) && !contentDiv.children[i].getElementsByTagName('input')[0].checked){
                contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
                arrayFindByLetter.push(contentDiv.children[i])
            } else {
                contentDiv.children[i].setAttribute('class','d-none')
>>>>>>> fixBugAjax
            }
        }
        if (arrayFindByLetter.length===0){
            let alert = document.createElement('div')
            alert.innerHTML='<div class="alert alert-danger container text-center">Aucun client trouvé avec ce nom</div>'
            alertNotFoundFilter.appendChild(alert)
        }
    })
<<<<<<< HEAD
}

// Afficher tous les clients
if (document.getElementById('tous')){
    var tous = document.getElementById('tous')
    tous.addEventListener('click',()=>{
        actifs.value = 0
        inactifs.value=0
        tous.value=1
        axios.get('/', {
            headers : {
                'X-Requested-With' : 'XMLHttpRequest'
=======
})

// Afficher les partenaires actifs
actifsCheckbox.addEventListener('click',()=>{
    rechercheInput.value=''
    alertNotFoundFilter.innerHTML=''
    seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
    nbrPage = 2
    arrayContent=[]
    for (let i = 0 ; i < contentDiv.children.length ; i++){
        if (contentDiv.children[i].getElementsByTagName('input')[0].checked){
            arrayContent.push(contentDiv.children[i])
            contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
            console.log(arrayContent)
        } else {
            contentDiv.children[i].setAttribute('class','d-none')
        }
    }
    arraySliced = arrayContent.slice(0,nbrPage)
    arrayContent.forEach(item=>{
        if (arraySliced.includes(item)){
            item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
        } else {
            item.setAttribute('class','d-none')
        }
    })
    if (nbrPage >= arrayContent.length){
        seeMoreBtn.setAttribute('class','d-none')
    }
    footer.scrollIntoView()

    // Recherche par lettre
    rechercheInput.addEventListener('keyup',()=>{
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-none')
        let arrayFindByLetter=[]
        for ( let i = 0 ; i < contentDiv.children.length ; i++){
            let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].textContent
            if (nameField.includes(rechercheInput.value) && contentDiv.children[i].getElementsByTagName('input')[0].checked){
                contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
                arrayFindByLetter.push(contentDiv.children[i])
            } else {
                contentDiv.children[i].setAttribute('class','d-none')
>>>>>>> fixBugAjax
            }
        }
        if (arrayFindByLetter.length===0){
            let alert = document.createElement('div')
            alert.innerHTML='<div class="alert alert-danger container text-center">Aucun client trouvé avec ce nom</div>'
            alertNotFoundFilter.appendChild(alert)
        }
    })
<<<<<<< HEAD
}

// Afficher le client en tapant son nom sur le formulaire de recherche
if (document.getElementById('recherche')){
    const recherche = document.getElementById('recherche')
    recherche.addEventListener('keyup',()=>{
        var filterStatus = null
        if (actifs.value==1){
            filterStatus='actifs'
        } else if (inactifs.value==1){
            filterStatus='inactifs'
=======

})

// Bouton pagination page home
plusBtn.addEventListener('click',()=>{
    nbrPage+=2
    arraySliced = arrayContent.slice(0,nbrPage)
    arrayContent.forEach(item=>{
        if (arraySliced.includes(item)){
            item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
>>>>>>> fixBugAjax
        } else {
            item.setAttribute('class','d-none')
        }
    })
    if (nbrPage >= arrayContent.length){
        seeMoreBtn.setAttribute('class','d-none')
    }
    footer.scrollIntoView()
})
arrayContent.forEach(item=>{
    if (arraySliced.includes(item)){
        item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
    } else {
        item.setAttribute('class','d-none')
    }
})

const finByLetter = function(){
    // Recherche par lettre
    rechercheInput.addEventListener('keyup',()=>{
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-none')
        let arrayFindByLetter=[]
        for ( let i = 0 ; i < contentDiv.children.length ; i++){
            let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].textContent
            if (nameField.includes(rechercheInput.value)){
                contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
                arrayFindByLetter.push(contentDiv.children[i])
            } else {
                contentDiv.children[i].setAttribute('class','d-none')
            }
        }
        if (arrayFindByLetter.length===0){
            let alert = document.createElement('div')
            alert.innerHTML='<div class="alert alert-danger container text-center">Aucun client trouvé avec ce nom</div>'
            alertNotFoundFilter.appendChild(alert)
        }
    })
}
<<<<<<< HEAD

// Afficher les branches activent seulement
if (document.getElementById('branchActifs')){
    var branchActifs = document.getElementById('branchActifs')
    branchActifs.addEventListener('click',()=>{
        axios.post('/branch_actifs', {
            idClient: branchActifs.name,
        })
            .then(function (response) {
                document.getElementById('branchCard').innerHTML=response.data.branchCard
            })
            .catch(function (error) {
                console.log(error);
            });
    })
}

// Afficher les branches inactives seulement
if (document.getElementById('branchInactifs')){
    var branchInactifs = document.getElementById('branchInactifs')
    branchInactifs.addEventListener('click',()=>{
        axios.post('/branch_inactifs', {
            idClient: branchInactifs.name,
        })
            .then(function (response) {
                document.getElementById('branchCard').innerHTML=response.data.branchCard
            })
            .catch(function (error) {
                console.log(error);
            });
    })
}

// Afficher toutes les branches
if (document.getElementById('branchTous')){
    var branchTous = document.getElementById('branchTous')
    branchTous.addEventListener('click',()=>{
        axios.post('/branch_tous', {
            idClient: branchTous.name,
        })
            .then(function (response) {
                document.getElementById('branchCard').innerHTML=response.data.branchCard
            })
            .catch(function (error) {
                console.log(error);
            });
    })
}
=======
finByLetter()
>>>>>>> fixBugAjax
