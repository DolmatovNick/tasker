(function() {

    // Utility function to convert a canvas to a BLOB
    var dataURLToBlob = function(dataURL) {
        var BASE64_MARKER = ';base64,';
        if (dataURL.indexOf(BASE64_MARKER) == -1) {
            var parts = dataURL.split(',');
            var contentType = parts[0].split(':')[1];
            var raw = parts[1];

            return new Blob([raw], {type: contentType});
        }

        var parts = dataURL.split(BASE64_MARKER);
        var contentType = parts[0].split(':')[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;

        var uInt8Array = new Uint8Array(rawLength);

        for (var i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uInt8Array], {type: contentType});
    };

    var controller = {

        // ImageUrl
        dataUrl: null,
        // Image Blob
        resizedImageBlob: null,

        showPreview: function() {

            var userName = $('#taskForm input[name="userName"]').val();
            var email = $('#taskForm input[name="email"]').val();
            var text = $('#taskForm textarea[name="text"]').val();
            var image = $('#taskForm img[name="image"]').attr('src');

            $('#preview h2[name="userName"]').html(userName);
            $('#preview a[name="email"]').html(email);
            $('#preview pre').html(text);
            $('#preview img[name="image"]').attr('src', image);
            $('#preview input[name="executed"]').prop('checked',
                $('#taskForm input[name="executed"]').prop('checked')
            );

            $('#myModal').modal({
                show: true
            });

        },
         
        uploadImage: function() {
            var file = document.getElementById('takePictureField').files[0];

            if (file) {
                var reader = new FileReader();

                // Подписываемся на событие 
                reader.onloadend = function(e) {

                    var image = new Image();
                    image.src = e.target.result;

                    // Подписываемся на загрузку картинки в область
                    // var image = new Image();
                    image.onload = function (imageEvent) {

                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(image, 0, 0);

                        var MAX_WIDTH = 320;
                        var MAX_HEIGHT = 240;
                        var width = image.width;
                        var height = image.height;

                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                        }
                        canvas.width = width;
                        canvas.height = height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(image, 0, 0, width, height);

                        // Слылка на картинку с измененным размером
                        controller.dataUrl = canvas.toDataURL(file.type);
                        controller.resizedImageBlob = dataURLToBlob(this.dataUrl);

                        var resizedImage  = document.getElementById('resizedImage');
                        resizedImage.src = controller.dataUrl;

                    }.bind(controller);
                }.bind(controller);
                reader.readAsDataURL(file);
            }
        }

    };

    var app = {
        init : function() {
            this.event();
        },
        
        event : function() {
            // Обработчик кнопки "Предварительный просмотр"
            var btnPreview = document.getElementById('btnPreview');
            btnPreview.onclick  = controller.showPreview;
            
            // Обработчик кнопки "Выберите файл"
            var takePictureField = document.getElementById('takePictureField');
            takePictureField.onchange = controller.uploadImage;
        }
  
    };
    app.init();

}());
