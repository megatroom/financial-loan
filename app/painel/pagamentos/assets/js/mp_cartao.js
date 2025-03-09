const publicKey = document.getElementById("mercado-pago-public-key").value;
const mercadopago = new MercadoPago(publicKey);
 const productCost = document.getElementById('inputValor').value;
const productDescription = document.getElementById('inputDescricao').value;
var inputNome_var = document.getElementById("form-checkout__inputNome").value;
var inputSobrenome_var = document.getElementById("form-checkout__inputSobrenome").value;
var inputTelefone_var = document.getElementById("form-checkout__inputTelefone").value;
var inputTelefoneDDD_var = document.getElementById("form-checkout__inputTelefoneDDD").value;
var inputEnderecoRua_var = document.getElementById("form-checkout__inputEnderecoRua").value;
var inputEnderecoNumero_var = document.getElementById("form-checkout__inputEnderecoNumero").value;
var inputEnderecoCEP_var = document.getElementById("form-checkout__inputEnderecoCEP").value;


const ref = document.getElementById('inputRef').value;
const payButton = document.getElementById("form-checkout__submit2");
const validationErrorMessages= document.getElementById('validation-error-messages');
var form = {};

function loadCardForm() {

    form = {
        id: "form-checkout",
        cardholderName: {
            id: "form-checkout__cardholderName",
            placeholder: "Nome no cartão",
            required: "required"
        },
        cardholderEmail: {
            id: "form-checkout__cardholderEmail",
            placeholder: "E-mail",
             required: "required"
        },
        cardNumber: {
            id: "form-checkout__cardNumber",
            placeholder: "Número do cartão",
             required: "required",
            style: {
                fontSize: "1rem"
            },
        },
        expirationDate: {
            id: "form-checkout__expirationDate",
            placeholder: "MM/AA",
            required: "required",
            style: {
                fontSize: "1rem"
            },
        },
        securityCode: {
            id: "form-checkout__securityCode",
            placeholder: "Código de segurança",
            required: "required",
            style: {
                fontSize: "1rem"
            },
        },
        installments: {
            id: "form-checkout__installments",
            placeholder: "Parcelas",
            required: "required"
        },
        identificationType: {
            id: "form-checkout__identificationType",
             required: "required"
        },
        identificationNumber: {
            id: "form-checkout__identificationNumber",
            placeholder: "Documento do titular",
            required: "required"
        },
        issuer: {
            id: "form-checkout__issuer",
            placeholder: "Bandeira",
        },
         inputNome: {
            id: "form-checkout__inputNome",
            placeholder: "Nome",
        },
        inputSobrenome: {
            id: "form-checkout__inputSobrenome",
            placeholder: "Sobrenome",
        },
         inputTelefoneDDD: {
            id: "form-checkout__inputTelefoneDDD",
            placeholder: "DDD",
        },
         inputTelefone: {
            id: "form-checkout__inputTelefone",
            placeholder: "Telefone",
        },
        
         inputEnderecoRua: {
            id: "form-checkout__inputEnderecoRua",
            placeholder: "Endereço",
        },
         inputEnderecoNumero: {
            id: "form-checkout__inputEnderecoNumero",
            placeholder: "Número",
        },
         inputEnderecoCEP: {
            id: "form-checkout__inputEnderecoCEP",
            placeholder: "CEP",
        }
        
    };

    const cardForm = mercadopago.cardForm({
        amount: productCost,
        iframe: true,
        form,
        callbacks: {
            onFormMounted: error => {
                if (error)
                    return console.warn("Form Mounted handling error: ", error);
                console.log("Form mounted");
            },
            onSubmit: event => {
                event.preventDefault();
                document.getElementById("loading-message").style.display = "block";

                const {
                    paymentMethodId,
                    issuerId,
                    cardholderEmail: email,
                    amount,
                    token,
                    installments,
                    identificationNumber,
                    identificationType,
                    inputNome,
                    inputSobrenome,
                    inputTelefone,
                    inputTelefoneDDD,
                    inputEnderecoRua,
                    inputEnderecoNumero,
                    inputEnderecoCEP,
                } = cardForm.getCardFormData();

                fetch("../pagamento.php?acc=geraar", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        ref: ref,
                        token,
                        issuerId,
                        paymentMethodId,
                        transactionAmount: Number(amount),
                        installments: Number(installments),
                        description: productDescription,
                        payer: {
                            first_name: inputNome_var,
                            last_name: inputSobrenome_var,
                            email,
                            identification: {
                                type: identificationType,
                                number: identificationNumber,
                            },
                            phone:{
                                area_code: inputTelefoneDDD_var,
                                number: inputTelefone_var,
                            },
                            address:{
                                zip_code:inputEnderecoCEP_var,
                                street_name:inputEnderecoRua_var,
                                street_number: inputEnderecoNumero_var,
                            }
                        }
                    }),
                })
                .then(response => {
                    return response.json();
                })
                .then(result => {
                    if(!result.hasOwnProperty("error_message")) {
                        document.getElementById("success-response").style.display = "block";
                        document.getElementById("payment-id").innerText = result.id;
                        document.getElementById("payment-status").innerText = result.status;
                        document.getElementById("payment-detail").innerText = result.detail;
                        if(result.status=="rejected"){
                            document.getElementById("payment-detail-retry").style.display = "block";
                        }else{
                                document.getElementById("payment-detail-retry").style.display = "none";
                        }
                    } else {
                        document.getElementById("error-message").textContent = result.error_message;
                        document.getElementById("fail-response").style.display = "block";
                    }
                    
                    $('.container__payment').fadeOut(500);
                    setTimeout(() => { $('.container__result').show(500).fadeIn(); }, 500);
                })
                .catch(error => {
                    alert("Erro:\n"+JSON.stringify(error));
                });
            },
            onFetching: (resource) => {
                console.log("Fetching resource: ", resource);
                payButton.setAttribute('disabled', true);
                return () => {
                    payButton.removeAttribute("disabled");
                };
            },
            onCardTokenReceived: (errorData, token) => {
                if (errorData && errorData.error.fieldErrors.length !== 0) {
                    errorData.error.fieldErrors.forEach(errorMessage => {
                        alert(errorMessage);
                    });
                }

                return token;
            },
            onValidityChange: (error, field) => {
                const input = document.getElementById(form[field].id);
                removeFieldErrorMessages(input, validationErrorMessages);
                addFieldErrorMessages(input, validationErrorMessages, error);
                enableOrDisablePayButton(validationErrorMessages, payButton);
            }
        },
    });
}

function removeFieldErrorMessages(input, validationErrorMessages) {
    Array.from(validationErrorMessages.children).forEach(child => {
        const shouldRemoveChild = child.id.includes(input.id);
        if (shouldRemoveChild) {
            validationErrorMessages.removeChild(child);
        }
    });
}

function addFieldErrorMessages(input, validationErrorMessages, error) {
    if (error) {
        input.classList.add('validation-error');
        error.forEach((e, index) => {
            const p = document.createElement('p');
            p.id = `${input.id}-${index}`;
            p.innerText = e.message;
            validationErrorMessages.appendChild(p);
        });
    } else {
        input.classList.remove('validation-error');
    }
}

function enableOrDisablePayButton(validationErrorMessages, payButton) {
    if (validationErrorMessages.children.length > 0) {
        payButton.setAttribute('disabled', true);
    } else {
        payButton.removeAttribute('disabled');
    }
}

// Handle transitions
document.getElementById('form-checkout__submit').addEventListener('click', function(){
    $('.container__cart').fadeOut(500);
    setTimeout(() => {
        loadCardForm();
        $('.container__payment').show(500).fadeIn();
    }, 500);
});

setTimeout(() => {  $('.container__result').fadeOut(500); $('#form-checkout__submit').trigger("click"); }, 100);

