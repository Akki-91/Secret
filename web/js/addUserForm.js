document.addEventListener('DOMContentLoaded', function () {

    function checkCardNumber()
    {
        var clubCardVal = $('#user_info_form_clubCardNumber').val();
        var verifyNumber = isNaN(clubCardVal);
        var clubCardValInt = parseInt(clubCardVal);
        var clubCardLength = clubCardVal.length;

        if(verifyNumber === false){
            if(clubCardLength !== 6){
                var message = 'Numer karty nie składa się z 6 cyfr.';
                return message;
            } else {
                return true;
            }
        } else {
            var message = 'Numer karty nie może składać się z innych znaków niż cyfry.';
            return message;
        }
    }

    function validateFileSize()
    {
        var fileInput = $('#user_info_form_picturePath');
        var fileSize = fileInput[0].files[0].size/1024/1024;
        var roundedFileSize = fileSize.toFixed(2);

        if(roundedFileSize > 7.9){
            var message = 'Plik jest zbyt duży. Limit wynosi 8 MB';
            return message;
        } else {
            return true;
        }
    }

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#userImage').attr('src', e.target.result)
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function isClubCardUnique(clubCardNumber)
    {
        $.ajax({
            url: '/checkClubCardNumber',
            data: {
                'clubCardNumber': clubCardNumber
            },
            type: 'GET',
            dataType: 'json'
        }).done( function (ans) {
            $("#clubCardNumberDuplicateError").remove();
            if(ans.cardNumberExists === true){
                $("#errorDiv").append("<div id='clubCardNumberDuplicateError' class=\"alert alert-warning\"><p>Wskazany numer karty już istnieje. Proszę wprowadzić inny.</p></div>");
            }
        });
    }

    $('#user_info_form_name').on('change', function() {
        var nameNotEmpty = $('#user_info_form_name').val().length;
        $("#nameError").remove();

        if(nameNotEmpty === 0){
            $("#errorDiv").append("<div id='nameError' class=\"alert alert-warning\"><p>Proszę wprowadzić imię i nazwisko</p></div>");
        }
    });

    $('#user_info_form_clubCardNumber').on('change', function() {
        var cardNumberValidation = checkCardNumber();
        var clubCardNumber = $('#user_info_form_clubCardNumber').val();
        $("#clubCardNumberError").remove();

        if(cardNumberValidation !== true){
            $("#errorDiv").append("<div id='clubCardNumberError' class=\"alert alert-warning\"><p>" + cardNumberValidation + " </p></div>");
        } else {
            isClubCardUnique(clubCardNumber);
        }
    });

    $('#user_info_form_picturePath').on('change', function() {
        var fileSize = validateFileSize();
        $("#picturePathError").remove();

        if(fileSize !== true){
            $("#errorDiv").append("<div id='picturePathError' class=\"alert alert-warning\"><p>Plik jest zbyt duży. Limit wynosi 8 MB.</p></div>");
            $('#user_info_form_picturePath').val("");
            $('#userImage').attr('src', 'img/defaultUserImage.png');
            return false;
        }

        imagePreview(this);
    });

    /*
    Chwilowo rezygnuje z weryfikacji na submit, na rzecz poszczególnych pól na on.('change').
     */
    // $("#addUserFormSubmit").on("click", function () {
    //     var cardNumberValidation = checkCardNumber();
    //     var imageSizeValidation = validateFileSize();
    //
    //     if(cardNumberValidation !== true){
    //         $("#clubCardNumberError").remove();
    //         $("#errorDiv").append("<div id='clubCardNumberError' class=\"alert alert-warning\"><p>" + cardNumberValidation + " </p></div>");
    //     }
    //
    //     if(imageSizeValidation !== true){
    //         $("#picturePathError").remove();
    //         $("#errorDiv").append("<div id='picturePathError' class=\"alert alert-warning\"><p> " + imageSizeValidation + " </p></div>");
    //     }
    //
    //     return false;
    // });

});
