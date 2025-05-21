function closeMessage(){
    document.getElementById("messagePrev").style.display = "none";
}

function updateFileName(input) {
    var fileName = input.files[0].name;
    var fileLabel = input.parentElement.querySelector('.file-name');
    fileLabel.textContent = fileName;
}

window.onload = () => {
    const FiltersForm = document.querySelector("#filters");
    if (FiltersForm) {
        document.querySelectorAll("#filters input, #filters select").forEach(element => {
            element.addEventListener("change", () => {
                const Form = new FormData(FiltersForm);
                const Params = new URLSearchParams();

                Form.forEach((value, key) => {
                    if (value !== "") {
                        Params.append(key, value);
                    }
                });

                const Url = new URL(window.location.href);

                fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const content = document.querySelector("#content");
                        content.innerHTML = data.content;
                        if (typeof initCarousel === 'function') {
                            initCarousel();
                        }
                    })
                    .catch(e => alert("Erreur AJAX : " + e));
            });
        });
    }
}


// Initialisation du carousel
function initCarousel() {
    bulmaCarousel.attach('#carousel-images', {
        slidesToScroll: 1,
        slidesToShow: 1,
        infinite: true,
        navigation: true,
        loop: true
    });
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    initCarousel();
});