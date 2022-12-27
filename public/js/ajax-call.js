
function meGusta(idPost) {

  $.ajax({
    method: "POST",
    url: `{{ path('app_likes') }}`,
    async: true,
    dataType: "json",
    data: { id : idPost },
    success: function (data) {
  alert('holaaaaaaaaaaaaaaa')  

      console.log(data['likes']);
    }
  });

}
