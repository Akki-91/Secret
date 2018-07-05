document.addEventListener('DOMContentLoaded', function () {


    function checkCardNumber()
    {
        var clubCardVal = $('#user_info_form_clubCardNumber').val();
        var verifyNumber = isNaN(clubCardVal);
        var clubCardValInt = parseInt(clubCardVal);
        var clubCardLength =   clubCardVal.length;

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

    $("#addUserFormSubmit").on("click", function () {
        var cardNumberValidation = checkCardNumber();
        var imageSizeValidation = validateFileSize();

        if(cardNumberValidation !== true){
            console.log(cardNumberValidation)
        }

        if(imageSizeValidation !== true){
            console.log(imageSizeValidation)
        }

        return false;
    });

    $('#user_info_form_picturePath').bind('change', function() {
        // w zależności czy ma sprawdza wage pliku na uploadzie czy submit?
    });

});
