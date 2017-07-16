/**
 * Created by Bruno on 27/02/2017.
 */
$(function() {


   $('.reply').click(function(e){
       e.preventDefault();
       $('.annuler').show();//Montre le bouton annuler
       var form = $('.formulaire');
       var $this = $(this);
       var parent_id = $this.data('id');
       var commentaire = $('#comment-' + parent_id);

       form.find('h3').text('Répondre à ce commentaire:')
       $('#appbundle_commentaire_recupIdCommentaire').val(parent_id);
       commentaire.after(form);

   })

    $('.annuler').click(function() {
        $('.annuler').hide(); //Cache le bouton annuler
        var form = $('.formulaire'); //Récupère le formulaire
        var commentaire = $('.place'); //Récupère la div ou placer le formulaire
        $('#appbundle_commentaire_recupIdCommentaire').val('');
        form.find('h3').text('Ajouter un commentaire:'); //Modifie le texte
        form.appendTo(commentaire); //Insère le formulaire à la fin de l'élèment place
    })

})
