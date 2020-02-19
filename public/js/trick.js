$('#add-img').click(function(){

    const index= +$('#counter').val(); 
    // Récupération du champs image
    const tmpl= $('#trick_imgs').data('prototype').replace(/__name__/g,index);
    //Envoi du template
    $('#trick_imgs').append(tmpl);
    //Incrémentation de la valeur du counteur
    $('#counter').val(index+1);
    //appelle fonction de suppression
    handleDeleteButtons();
})

function handleDeleteButtons(){
    //Recupération du numero de ligne
    $('button[data-action="delete"]').click(function () {
        //recuperation du block_id
        const target = this.dataset.target ;
        //suppression
        $(target).remove();
    });
}

function updateCounter() {
    const index = +$('#trick_imgs div.form-group').length;
    $('#counter').val(index);
}

updateCounter();
handleDeleteButtons();    