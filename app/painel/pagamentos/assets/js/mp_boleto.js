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

    fetch("../pagamento.php?acc=gerar&tipo=boleto", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            ref: ref,
            transactionAmount: Number(amount),
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

}

(async function getIdentificationTypes() {
    try {
      const identificationTypes = await mp.getIdentificationTypes();
      const identificationTypeElement = document.getElementById('form-checkout__identificationType');

      createSelectOptions(identificationTypeElement, identificationTypes);
    } catch (e) {
      return console.error('Error getting identificationTypes: ', e);
    }
  })();

  function createSelectOptions(elem, options, labelsAndKeys = { label: "name", value: "id" }) {
    const { label, value } = labelsAndKeys;

    elem.options.length = 0;

    const tempOptions = document.createDocumentFragment();

    options.forEach(option => {
      const optValue = option[value];
      const optLabel = option[label];

      const opt = document.createElement('option');
      opt.value = optValue;
      opt.textContent = optLabel;

      tempOptions.appendChild(opt);
    });

    elem.appendChild(tempOptions);
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

//setTimeout(() => {  $('.container__result').fadeOut(500); $('#form-checkout__submit').trigger("click"); }, 100);

