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

// Activer ou désactiver un partenaire depuis la page d'accueil
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

// Activer ou désactiver un partenaire dans sa page profil
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

// Activer ou désactiver une permission globale
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

// Activer ou désactiver une structure
if (document.getElementById('branchCard')){
    const activeBranchModal = document.getElementById('branchCard')
    for (let i=0 ; i< activeBranchModal.children.length ; i++){

        const activeBtn = activeBranchModal.children[i].getElementsByClassName('activeBranch')
        activeBtn[0].addEventListener('click',(e)=>{
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

// Activer ou désactiver une ou plusieurs permissions d'une structure
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

// Mettre le formulaire email en ReadOnly pour ne pas pouvoir modifier l'adresse mail de l'utilisateur partenaire
if (document.getElementById('modifClient')){
    const sectionPartenaireEdit = document.getElementById('modifClient')
    sectionPartenaireEdit.getElementsByTagName('form')[0].children[6].children[1].readOnly=true
}

// Mettre le formulaire email en ReadOnly pour ne pas pouvoir modifier l'adresse mail de l'utilisateur structure
if (document.getElementById('editBranch')){
    const sectionBranchEdit =document.getElementById('editBranch')
    sectionBranchEdit.getElementsByTagName('form')[0].children[2].readOnly=true
}

// Modifier le css de la div alert erreur de la page modifier mot de passe user
if (document.getElementById('errorsNewPassword')){
    const errorsNewPassword = document.getElementById('errorsNewPassword')
    errorsNewPassword.getElementsByTagName('ul')[0].setAttribute('class','list-unstyled m-0 p-0')
}

if (document.getElementById('filtrageForm')){
    // Pagination Script
    let nbrPage = 4
    const plusBtn = document.getElementById('plusBtn')
    const seeMoreBtn = document.getElementById('seeMore')
    const actifsCheckbox = document.getElementById('actifs')
    const inactifCheckbox = document.getElementById('inactifs')
    const touCheckbox = document.getElementById('tous')
    const contentDiv =document.getElementById('clientCard')
    const footer = document.getElementById('footer')
    let arrayContent=[...contentDiv.children]
    let arraySliced = arrayContent.slice(0,nbrPage)
    const rechercheInput = document.getElementById('recherche')
    const ulSuggestions = document.getElementById('ulSuggestions')

// Création d'une fonction qui affiche des partenaires en tapant leurs noms dans la barre de recherche et dans la page qui affiche tous partenaires
const finAll = function(){
    rechercheInput.addEventListener('keyup',()=>{
        document.getElementById('notFoundError').innerHTML=''
        document.addEventListener('click',(e)=>{
            const clickOutInput = rechercheInput.contains(e.target)
            if (!clickOutInput){
                ulSuggestions.innerHTML=''
                ulSuggestions.setAttribute('class','d-none')
                document.getElementById('notFoundError').innerHTML=''
            }
        })
        ulSuggestions.innerHTML=''
        for ( let i = 0 ; i < contentDiv.children.length ; i++){
            let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].getElementsByTagName('span')[0].textContent.toLowerCase()
            let idField = contentDiv.children[i].getElementsByTagName('ul')[0].children[0].getElementsByTagName('span')[0].textContent
            if (nameField.startsWith(rechercheInput.value.toLowerCase()) && rechercheInput.value!==''){
                const link = document.createElement('a')
                link.textContent=nameField
                link.setAttribute('class','list-group-item list-group-item-action  border-0')
                link.href='/client/'+idField
                ulSuggestions.appendChild(link)
            }
        }
        if (ulSuggestions.children.length > 0){
            ulSuggestions.setAttribute('class','position-absolute list-group border border-2')
        } else{
            ulSuggestions.setAttribute('class','d-none')
            document.getElementById('notFoundError').innerHTML='<div class="alert-danger alert container text-center">Aucun resultat trouvé</div>'
        }
    })

}

// On lance la function par défaut vue qu'on va la réutiliser si on click sur le button tous partenaires
    finAll()

// Afficher tous les partenaires
    touCheckbox.addEventListener('click',()=>{
        document.getElementById('notFoundError').innerHTML=''
        rechercheInput.value=''
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
        nbrPage = 4
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
// On réutilise la function qui trouve les partenaires en tapant leurs noms dans la page tous
        finAll()

    })


// Afficher les partenaires inactifs
    inactifCheckbox.addEventListener('click',()=>{
        let nbrInactifs=0
        document.getElementById('notFoundError').innerHTML=''
        rechercheInput.value=''
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
        nbrPage = 4
        arrayContent=[]
        for (let i = 0 ; i < contentDiv.children.length ; i++){
            if (!contentDiv.children[i].getElementsByTagName('input')[0].checked){
                arrayContent.push(contentDiv.children[i])
                contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
                nbrInactifs++
            } else {
                contentDiv.children[i].setAttribute('class','d-none')
            }
        }
        if (nbrInactifs===0){
            document.getElementById('notFoundError').innerHTML='<div class="alert-danger alert container text-center">Aucun partenaire inactif trouvé</div>'
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

        // Recherche un partenaire par les premières lettres de son nom et dans la page qui affiche que les partenaires inactifs
        rechercheInput.addEventListener('keyup',()=>{
            document.getElementById('notFoundError').innerHTML=''
            document.addEventListener('click',(e)=>{
                const clickOutInput = rechercheInput.contains(e.target)
                if (!clickOutInput){
                    ulSuggestions.innerHTML=''
                    ulSuggestions.setAttribute('class','d-none')
                    document.getElementById('notFoundError').innerHTML=''
                }
            })
            ulSuggestions.innerHTML=''
            for ( let i = 0 ; i < contentDiv.children.length ; i++){
                let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].getElementsByTagName('span')[0].textContent.toLowerCase()
                let idField = contentDiv.children[i].getElementsByTagName('ul')[0].children[0].getElementsByTagName('span')[0].textContent
                if (nameField.startsWith(rechercheInput.value.toLowerCase()) && !contentDiv.children[i].getElementsByTagName('input')[0].checked && rechercheInput.value!==''){
                    const link = document.createElement('a')
                    link.textContent=nameField
                    link.setAttribute('class','list-group-item list-group-item-action  border-0')
                    link.href='/client/'+idField
                    ulSuggestions.appendChild(link)
                }
            }
            if (ulSuggestions.children.length > 0){
                ulSuggestions.setAttribute('class','position-absolute list-group border border-2')
            } else{
                ulSuggestions.setAttribute('class','d-none')
                document.getElementById('notFoundError').innerHTML='<div class="alert-danger alert container text-center">Aucun resultat trouvé</div>'
            }
        })

    })

// Afficher les partenaires actifs
    actifsCheckbox.addEventListener('click',()=>{
        let nbrActifs = 0
        document.getElementById('notFoundError').innerHTML=''
        rechercheInput.value=''
        alertNotFoundFilter.innerHTML=''
        seeMoreBtn.setAttribute('class','d-flex justify-content-center align-items-center')
        nbrPage = 4
        arrayContent=[]
        for (let i = 0 ; i < contentDiv.children.length ; i++){
            if (contentDiv.children[i].getElementsByTagName('input')[0].checked){
                arrayContent.push(contentDiv.children[i])
                contentDiv.children[i].setAttribute('class','my-3 p-4 article m-auto rounded list-group')
                nbrActifs++
            } else {
                contentDiv.children[i].setAttribute('class','d-none')
            }
        }
        if (nbrActifs===0){
            document.getElementById('notFoundError').innerHTML='<div class="alert-danger alert container text-center">Aucun partenaire actif trouvé</div>'
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

        // Recherche un partenaire par les premières lettres de son nom et dans la page qui affiche que les partenaires actifs
        rechercheInput.addEventListener('keyup',()=>{
            document.getElementById('notFoundError').innerHTML=''
            document.addEventListener('click',(e)=>{
                const clickOutInput = rechercheInput.contains(e.target)
                if (!clickOutInput){
                    ulSuggestions.innerHTML=''
                    ulSuggestions.setAttribute('class','d-none')
                    document.getElementById('notFoundError').innerHTML=''
                }
            })
            ulSuggestions.innerHTML=''
            for ( let i = 0 ; i < contentDiv.children.length ; i++){
                let nameField = contentDiv.children[i].getElementsByTagName('ul')[0].children[1].getElementsByTagName('span')[0].textContent.toLowerCase()
                let idField = contentDiv.children[i].getElementsByTagName('ul')[0].children[0].getElementsByTagName('span')[0].textContent
                if (nameField.startsWith(rechercheInput.value.toLowerCase()) && contentDiv.children[i].getElementsByTagName('input')[0].checked && rechercheInput.value!==''){
                    const link = document.createElement('a')
                    link.textContent=nameField
                    link.setAttribute('class','list-group-item list-group-item-action  border-0')
                    link.href='/client/'+idField
                    ulSuggestions.appendChild(link)
                }
            }
            if (ulSuggestions.children.length > 0){
                ulSuggestions.setAttribute('class','position-absolute list-group border border-2')
            } else{
                ulSuggestions.setAttribute('class','d-none')
                document.getElementById('notFoundError').innerHTML='<div class="alert-danger alert container text-center">Aucun resultat trouvé</div>'
            }
        })

    })

// Bouton pagination
    plusBtn.addEventListener('click',()=>{
        nbrPage+=4
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
    })
    arrayContent.forEach(item=>{
        if (arraySliced.includes(item)){
            item.setAttribute('class','my-3 p-4 article m-auto rounded list-group')
        } else {
            item.setAttribute('class','d-none')
        }
    })

}
// Filtrer les structures

if (document.getElementById('branchCard')){
    const branchCard = document.getElementById('branchCard')
    const actifsBranch =document.getElementById('actifsBranch')
    const inactifsBranch =document.getElementById('inactifsBranch')
    const touBranch =document.getElementById('tousBranch')


// Afficher les structures actives
    actifsBranch.addEventListener('click',()=>{
        let nbrActifsDiv = 0
        document.getElementById('notFoundErrorBranch').innerHTML=''
        footer.scrollIntoView()
        for (let i = 0 ; i < branchCard.children.length ; i++){
            const input = branchCard.children[i].getElementsByTagName('input')
            if (!input[0].checked){
                branchCard.children[i].setAttribute('class','d-none')
            } else {
                branchCard.children[i].setAttribute('class','grandDivCardPermissions my-4')
                nbrActifsDiv++
            }
        }
        if (nbrActifsDiv===0){
            document.getElementById('notFoundErrorBranch').innerHTML='<div class="alert-danger alert container text-center">Aucun partenaire actif trouvé</div>'
        }
    })

// Afficher les structures inactives
    inactifsBranch.addEventListener('click',()=>{
        let nbrInactifs = 0
        document.getElementById('notFoundErrorBranch').innerHTML=''
        for (let i = 0 ; i < branchCard.children.length ; i++){
            const input = branchCard.children[i].getElementsByTagName('input')
            if (input[0].checked){
                branchCard.children[i].setAttribute('class','d-none')

            } else {
                branchCard.children[i].setAttribute('class','grandDivCardPermissions my-4')
                nbrInactifs++
            }
        }
        if (nbrInactifs===0){
            footer.scrollIntoView()
            document.getElementById('notFoundErrorBranch').innerHTML='<div class="alert-danger alert container text-center">Aucun partenaire inactif trouvé</div>'
        }
    })
// Afficher toutes les structures
    touBranch.addEventListener('click',()=>{
        document.getElementById('notFoundErrorBranch').innerHTML=''
        for (let i = 0 ; i < branchCard.children.length ; i++){
            branchCard.children[i].setAttribute('class','grandDivCardPermissions my-4')
        }
    })

}

const codePostal = document.getElementById('branch_code_postal')
const suggestionVillDiv= document.getElementById('suggestionVillDiv')
const branchVille = document.getElementById('branch_ville')
        codePostal.addEventListener('keyup',()=>{
            document.addEventListener('click',(e)=>{
                const clickOutInput = codePostal.contains(e.target)
                if (!clickOutInput){
                    suggestionVillDiv.setAttribute('class','d-none')
                }
            })
            suggestionVillDiv.textContent=''
            if (codePostal.value.length >=5){
                axios.post('/get_code_postal', {
                    codePostal: codePostal.value,
                })
                    .then(function (response) {
                        suggestionVillDiv.textContent=''
                        console.log(response.data.forEach(result=>{
                            const suggestionVill = document.createElement('button')
                            suggestionVill.setAttribute('class','list-group-item list-group-item-action')
                            suggestionVill.textContent=result.ville
                            suggestionVill.addEventListener('click',(e)=>{
                                e.preventDefault()
                                branchVille.value=suggestionVill.textContent
                                suggestionVillDiv.setAttribute('class','d-none')
                            })
                            document.getElementById('suggestionVillDiv').appendChild(suggestionVill)
                            suggestionVillDiv.setAttribute('class','border border-2 list-group p-2 list-unstyled')

                        }));
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            } else {
                suggestionVillDiv.setAttribute('class','d-none')
            }
        })