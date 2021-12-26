
/*
=============
Counter btns
=============
 */

//Mettre une valeur par defaut 
document.querySelector('.minus-btn').setAttribute("disabled", "disabled");
document.querySelector('.minus-svg').classList.add("disabled-btn");


//declarer variable count
var valueCount = 0;
var MaxValue = document.getElementById("valueCount").innerText;
console.log(MaxValue);
var new_price = document.getElementById('new__price').innerText;

function calculPrice() {
    document.getElementById("new__price").innerText = new_price * valueCount;
}

//plus btn
document.querySelector('.plus-btn').addEventListener("click", function() {

    //valeur de l 
    valueCount = document.getElementById('counter-btn').value;

    if (valueCount > 1) {
        //enlever la valeur par defaut
        document.querySelector('.minus-btn').removeAttribute("disabled");
        document.querySelector('.minus-svg').classList.remove("disabled-btn");
    }
    if (valueCount < MaxValue) {
        //incrementer
        valueCount++;

        if (valueCount > 1) {
            //enlever la valeur par defaut
            document.querySelector('.minus-btn').removeAttribute("disabled");
            document.querySelector('.minus-svg').classList.remove("disabled-btn");
        }

        //afficher sur input
        document.getElementById('counter-btn').value = valueCount;
    } else {
        //ajouter disabled
        document.querySelector('.plus-btn').setAttribute("disabled", "disabled");
        document.querySelector('.plus-svg').classList.add("disabled-btn");
    }

    calculPrice();
});

document.querySelector('.minus-btn').addEventListener("click", function() {

    //valeur de l input
    valueCount = document.getElementById('counter-btn').value;

    if (valueCount < MaxValue) {
        //enlever la valeur par defaut
        document.querySelector('.plus-btn').removeAttribute("disabled");
        document.querySelector('.plus-svg').classList.remove("disabled-btn");
    }

    if (valueCount > 1) {
        //incrementer
        valueCount--;


        if (valueCount < MaxValue) {
            //enlever la valeur par defaut
            document.querySelector('.plus-btn').removeAttribute("disabled");
            document.querySelector('.plus-svg').classList.remove("disabled-btn");
        }

        //afficher sur input
        document.getElementById('counter-btn').value = valueCount;

        //Enlever disabled
        document.querySelector('.minus-btn').removeAttribute("disabled");
        document.querySelector('.minus-svg').classList.remove("disabled-btn");
    } else {
        //Ajouter disabled
        document.querySelector('.minus-btn').setAttribute("disabled", "disabled");
        document.querySelector('.minus-svg').classList.add("disabled-btn");
    }

    calculPrice();

});



var CounterValue = document.getElementById('counter-btn').value;
var modifierText = document.getElementById('btn-modif-span').innerText;
var input = null;

document.querySelector('.btn-modif').addEventListener("click", function() {
    if (document.getElementById('btn-modif-span').innerText == "Modifier") {
        document.getElementById('btn-modif-span').innerText = "Confirmer";
        input = document.querySelectorAll('.counter-btn')
        console.log(input);
        for (let index = 0; index < input.length; index++) {
            const element = input[index];
            console.log(element);
            element.removeAttribute("disabled");
        }


    } else if (document.getElementById('btn-modif-span').innerText == "Confirmer") {

        document.getElementById('btn-modif-span').innerText = "Modifier";

        input = document.querySelectorAll('.counter-btn')

        for (let index = 0; index < input.length; index++) {
            const element = input[index];
            element.setAttribute("disabled", "disabled");
        }


    }
})

