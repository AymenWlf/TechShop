const getProducts = async() => {
    try {
        const results = await fetch("./data/products.json");
        const data = await results.json();
        const products = data.products;
        return products;
    } catch (err) {
        console.log(err);
    }
};

/*
=============
Load Category Products
=============
 */
const categoryCenter = document.querySelector(".category__center");

window.addEventListener("DOMContentLoaded", async function() {
    const products = await getProducts();
    displayProductItems(products);
});

const displayProductItems = items => {
    let displayProduct = items.map(
        product => ` 
                  <div class="product category__products">
                    <div class="product__header">
                      <img src=${product.image} alt="product">
                    </div>
                    <div class="product__footer">
                      <h3>${product.title}</h3>
                      <div class="rating">
                        <svg>
                          <use xlink:href="./images/sprite.svg#icon-star-full"></use>
                        </svg>
                        <svg>
                          <use xlink:href="./images/sprite.svg#icon-star-full"></use>
                        </svg>
                        <svg>
                          <use xlink:href="./images/sprite.svg#icon-star-full"></use>
                        </svg>
                        <svg>
                          <use xlink:href="./images/sprite.svg#icon-star-full"></use>
                        </svg>
                        <svg>
                          <use xlink:href="./images/sprite.svg#icon-star-empty"></use>
                        </svg>
                      </div>
                      <div class="product__price">
                        <h4>$${product.price}</h4>
                      </div>
                      <a href="#"><button type="submit" class="product__btn">Add To Cart</button></a>
                    </div>
                  <ul>
                      <li>
                        <a data-tip="Quick View" data-place="left" href="#">
                          <svg>
                            <use xlink:href="./images/sprite.svg#icon-eye"></use>
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a data-tip="Add To Wishlist" data-place="left" href="#">
                          <svg>
                            <use xlink:href="./images/sprite.svg#icon-heart-o"></use>
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a data-tip="Add To Compare" data-place="left" href="#">
                          <svg>
                            <use xlink:href="./images/sprite.svg#icon-loop2"></use>
                          </svg>
                        </a>
                      </li>
                  </ul>
                  </div>
                  `
    );

    displayProduct = displayProduct.join("");
    if (categoryCenter) {
        categoryCenter.innerHTML = displayProduct;
    }
};

/*
=============
Filtering
=============
 */

const filterBtn = document.querySelectorAll(".filter-btn");
const categoryContainer = document.getElementById("category");

if (categoryContainer) {
    categoryContainer.addEventListener("click", async e => {
        const target = e.target.closest(".section__title");
        if (!target) return;

        const id = target.dataset.id;
        const products = await getProducts();

        if (id) {
            // remove active from buttons
            Array.from(filterBtn).forEach(btn => {
                btn.classList.remove("active");
            });
            target.classList.add("active");

            // Load Products
            let menuCategory = products.filter(product => {
                if (product.category === id) {
                    return product;
                }
            });

            if (id === "All Products") {
                displayProductItems(products);
            } else {
                displayProductItems(menuCategory);
            }
        }
    });
}

/*
=============
Product Details Left
=============
 */
// const pic1 = document.getElementById("pic1");
// const pic2 = document.getElementById("pic2");
// const pic3 = document.getElementById("pic3");
// const pic4 = document.getElementById("pic4");
// const pic5 = document.getElementById("pic5");
// const picContainer = document.querySelector(".product__pictures");
// const zoom = document.getElementById("zoom");
// const pic = document.getElementById("pic");

// // Picture List
// const picList = [pic1, pic2, pic3, pic4, pic5];

// // Active Picture
// let picActive = 1;

// ["mouseover", "touchstart"].forEach(event => {
//     if (picContainer) {
//         picContainer.addEventListener(event, e => {
//             const target = e.target.closest("img");
//             if (!target) return;
//             const id = target.id.slice(3);
//             changeImage(`{{asset('images/products/iPhone/iphone${id}.jpeg')}}`, id);
//         });
//     }
// });

// // change active image
// const changeImage = (imgSrc, n) => {
//     // change the main image
//     pic.src = imgSrc;
//     // change the background-image
//     zoom.style.backgroundImage = `url(${id})`;
//     //   remove the border from the previous active side image
//     picList[picActive - 1].classList.remove("img-active");
//     // add to the active image
//     picList[n - 1].classList.add("img-active");
//     //   update the active side picture
//     picActive = n;
// };


/*
=============
Product Details Bottom
=============
 */

const btns = document.querySelectorAll(".detail-btn");
const detail = document.querySelector(".product-detail__bottom");
const contents = document.querySelectorAll(".content");

if (detail) {
    detail.addEventListener("click", e => {
        const target = e.target.closest(".detail-btn");
        if (!target) return;

        const id = target.dataset.id;
        if (id) {
            Array.from(btns).forEach(btn => {
                // remove active from all btn
                btn.classList.remove("active");
                e.target.closest(".detail-btn").classList.add("active");
            });
            // hide other active
            Array.from(contents).forEach(content => {
                content.classList.remove("active");
            });
            const element = document.getElementById(id);
            element.classList.add("active");
        }
    });
}

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