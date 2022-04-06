
$(function () {
    var emailisvalid = false;
    let email = document.querySelector('#mail');
    email.addEventListener('change', function () {
        let validemail = verifyEmail(this);
    });
    
     const verifyEmail = function (Email) {
        //let emailRegExp = new RegExp('^[a-zA-Z0-9.-_]+[@]{1}[a-zA-Z0-9.-_]+[.]{1}[a-z]{2,10}$', 'g');
        let emailRegExp = new RegExp('^([\\S]+)@([\\S]+\\.)([a-zA-Z]{2,4})', 'i');
        console.log(emailRegExp)
        let testEmail = emailRegExp.test(Email.value);
        if (testEmail == false) {
            document.getElementById('validemail').innerHTML = '<span style="color:red;">Veuillez saisir une adresse email valide</span>';
        } else {
            document.getElementById('validemail').innerHTML = '';
            emailisvalid = true;
            return true;
        }
        return false;
    }
    $("#button").on("click", function (){
        
        if (emailisvalid){
            let objArr = {
                
                    "prenom": $("#prenom").val(),
                    "nom": $("#nom").val(),
                    "ville": $("#ville").val(),
                    "code_postal": $("#code_postal").val(),
                    "mail": $("#mail").val(),
                    0237386000
    
                };
                objArr= JSON.stringify(objArr)
    
    
                $.ajax({
                    method: "POST",
                    url: "/newsletter/inscription",
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    data: objArr,
                    error: function (xhr, status, error) {
    
                        console.log("response avec erreur = ", response);
                        document.getElementById('champserreurs').innerHTML = '<span style="color:red;">Une erreur est survenue. Veuillez réessayer.</span>';
                        $("#load").removeClass('spinner-border');
                        $("#post").attr('disabled', false);
                    },
                }).done((response)=>{
                    console.log(response)
                })
            }
        }
    )
    })