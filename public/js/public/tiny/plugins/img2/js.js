 // https://wpcafe.org/hacks/ajax-zagruzka-faylov-na-server-s-pomoshhyu-jquery/

$(function(){
    var files;
 

    $('input[type=file]').change(function(){
        files = this.files;
        tinymce = parent.tinymce;
        $(window.parent.document).find('.mce-ImgUploadButton').removeClass('mce-disabled'); 
    });

    $(window.parent.document).find('.mce-ImgUploadButton').on('click',function(){
        debugger;
        var editor = parent.tinymce.EditorManager.activeEditor;
        editor.insertContent('11111<img src="">22222');
        editor.windowManager.close();
    });

    $('#SEND').click(function( event ){


        event.stopPropagation(); // Остановка происходящего
        event.preventDefault();  // Полная остановка происходящего
    
        // Создадим данные формы и добавим в них данные файлов из files
    
        var data = new FormData();
        $.each( files, function( key, value ){
            data.append( key, value );
        });


        // Отправляем запрос
    
        $.ajax({
            url: './submit.php?uploadfiles',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( respond, textStatus, jqXHR ){
                // Если все ОК
                if( typeof respond.error === 'undefined' ){
                    // Файлы успешно загружены, делаем что нибудь здесь
                    // выведем пути к загруженным файлам в блок '.ajax-respond'
                    var files_path = respond.files;
                    var html = '';
                    $.each( files_path, function( key, val ){ html += val +'<br>'; } )
                    $('.ajax-respond').html( html );
                }
                else{
                    console.log('ОШИБКИ ОТВЕТА сервера: ' + respond.error );
                }
            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log('ОШИБКИ AJAX запроса: ' + textStatus );
            }
        });
    
    });

});