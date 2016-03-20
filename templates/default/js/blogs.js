function paste_string(e, s){
e.value+=s;
e.focus();
}

    $(document).ready(function(){

          $("div.blog_content img").each(function() {

           //Get the width of the image
           var width = $(this).width();

           //Max-width substitution (works for all browsers)
           if (width > 780) {
             $(this).css("width", "780px");
           }

         });

       });
