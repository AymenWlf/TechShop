document.querySelectorAll(".js-quantity-plus").forEach(function(link)
{
    link.addEventListener("click", function(event)
    {
        event.preventDefault();
        const url = this.href;
        var counterValue = document.getElementById('js-counter-btn').value;
        
        axios
            .get(url)
            .then((response) => 
            {
                console.log(counterValue);
                if (counterValue < 10)
                {
                    counterValue++;
                    document.getElementById('js-counter-btn').value = counterValue;
                    document.getElementById("js-counter-btn-resp").value = counterValue;
                } else if (counterValue > 9)
                {
                    document.querySelectorAll(".js-quantity-plus").forEach((btn) => {
                        btn.setAttribute("disabled");
                    })
                    document.querySelectorAll(".plus-cart-svg").forEach((svg) => {
                        svg.classList.add("js-disabled-btn");
                    })
                }
            })
    })
})



