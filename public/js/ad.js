//création de la fonction d'action click-ajout sur le bouton ajouter une image
$('#add-image').click(function(){
    // je récupère les numéros des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();

    // je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    // j'injecte ce code au sein de la div

    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // je gère mon bouton supprimer

    handleDeleteButton();

}
);

function handleDeleteButton(){

$('button[data-action="delete"]').click(function(){
    const target = this.dataset.target;
    $(target).remove();

});
}

function updateCounter(){
const count = +$('#annonce_images div.form-group').length;
$('#widgets-counter').val(count);
}


updateCounter();
handleDeleteButton();
