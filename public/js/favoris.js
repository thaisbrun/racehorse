function postFavori(){
    fetch('/favoris/new', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body:{
            idutilisateurfav:3,
            idannoncefav: 2,
            datecreation: Date.now().toString()
        }
    })
        .then(response => {
            response.json()
        })
        .then(data => console.log(data))
        .catch(error => console.error(error));
}
