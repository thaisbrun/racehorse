function closeMessage(){
        document.getElementById("messagePrev").style.display = "none";
}
window.onload = () => {
        const FiltersForm = document.querySelector("#filters");
    document.querySelectorAll("#filters input, div.select").forEach(input =>{
           input.addEventListener("change", () =>{
               //On récupère les données du formulaire
               const Form = new FormData(FiltersForm);
               //On fabrique la queryString
               const Params = new URLSearchParams();
                   Form.forEach((value, key) =>{
                       Params.append(key,value);
                       console.log(Params.toString());
                   });
                   //On récupère l'URL active
               const Url = new URL(window.location.href);
               //On lance la requête AJAX
               fetch(Url.pathname + "?" + Params.toString() + "&ajax=1",{
                   headers:{
                       "X-Requested-with": "XMLHttpRequest"
                   }
               }).then(response => response.json()
               ).then(data => {
                   //On va chercher le contenu
                   const content = document.querySelector("#content");
                   //On met à jour le contenu
                   content.innerHTML = data.content;
                   })
                   .catch(e => alert(e));
           })
        });


}