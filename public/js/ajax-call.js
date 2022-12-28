
function meGusta(idPost) {

  $.ajax({
    method: "POST",
    url: `{{ path('app_likes', {'id': 'id' }) }}`.replace('id', idPost),
    dataType: 'json',
    success: function (data) {
      console.log(data);
    },
    error: function (error) {
      console.log("No se ha podido obtener la información.");
      console.log(error);
    }
  });

}
