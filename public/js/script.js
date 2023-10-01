function closeMessage(){
        document.getElementById("messagePrev").style.display = "none";
}
window.onload = () => {
        const FiltersForm = document.querySelector("#filters");

        document.querySelectorAll("#filters input").forEach(input =>{
           input.addEventListener("change", () =>{
               const Form = new FormData(FiltersForm);
               const Params = new URLSearchParams();
                   Form.forEach((value, key) =>{
                       Params.append(key,value);
                       console.log(Params.toString());
                   });
           })
        });
}