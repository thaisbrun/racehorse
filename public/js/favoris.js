function postFavori(idutilisateurfav, idannoncefav){
    const data = new URLSearchParams();
    data.append('idutilisateurfav', idutilisateurfav);
    data.append('idannoncefav', idannoncefav);
    data.append('datecreation', Date.now().toString());
    fetch('/favoris/new', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error(error));
}
