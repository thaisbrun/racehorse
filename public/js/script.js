function closeMessage(){
        document.getElementById("messagePrev").style.display = "none";
}
window.onload = () => {
        const filtersForm = document.querySelector("#filters");

        document.querySelectorAll("#filters input").forEach(input =>{
           input.addEventListener("change", () =>{
                   console.log("clic");
           })
        });
}