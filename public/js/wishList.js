document.querySelectorAll(".js-wishlistAdd").forEach(function (link) {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    const url = this.href;
    icone = this.querySelector("i");

    axios
      .get(url)
      .then(function (response) {
        if (icone.classList.contains("fas")) {
          icone.classList.replace("fas", "far");
        } else {
          icone.classList.replace("far", "fas");
        }
      })
      .catch(function (error) {
        if (error.response.status === 403) {
          window.alert(
            "Connecter vous d'abord pour ajouter un produit dans votre wishList !"
          );
        } else {
          window.alert("Une erreur s'est produit, réesseyez !");
        }
      });
  });
});
