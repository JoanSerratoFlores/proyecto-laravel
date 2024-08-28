var url = 'http://localhost/proyecto-laravel/public';
window.addEventListener("load", function(){
    
    $('.btn-like').css('cursor','pointer');
    $('.btn-dislike').css('cursor','pointer');
    
    //Boton de like
    function like(){
        $(".btn-like").unbind('click').click(function(){
            console.log('like');
            $(this).addClass("btn-dislike").removeClass("btn-like");
            $(this).attr('src',url+'/assets/img/heart-red.png');
            
            $.ajax({
                
                url : url+'/like/'+$(this).data('id'),
                type:'GET',
                success:function(response){
                    if(response.like){
                        console.log('Has dado like a la publicacion');
                    }else{
                        console.log('Error al darle like')
                    }
                    
                }
            });
            
            dislike();
        });
    }
    like();

    //Boton de dislike
    function dislike(){
        $(".btn-dislike").unbind('click').click(function(){
            console.log('dislike');
            $(this).addClass("btn-like").removeClass("btn-dislike");
            $(this).attr('src',url+'/assets/img/heart-black.png');
            $.ajax({
                
                url : url+'/dislike/'+$(this).data('id'),
                type:'GET',
                success:function(response){
                    if(response.like){
                        console.log('Has dado dislike a la publicacion');
                    }else{
                        console.log('Error al darle dislike')
                    }
                    
                }
            });
            
            like();
        });
    }
    dislike();
    
    //BUSCADOR
    
    $('#buscador').submit(function(e){
        $(this).attr('action',url+'/gente/'+$('#buscador #search').val());
        })
   
  
    
}); 