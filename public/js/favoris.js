function postFavori(idutilisateurfav, idannoncefav){
    fetch('/favoris/new', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.parse({
            idutilisateurfav: idutilisateurfav,
            idannoncefav: idannoncefav,
            datecreation: Date.now().toString()
        })
    })
        .then(response => {
            response.json()
        })
        .then(data => console.log(data))
        .catch(error => console.error(error));
}
