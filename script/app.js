(function () {
    let news = document.getElementsByClassName('news');
    let del = document.getElementsByClassName('delete');
    let title = document.getElementsByClassName('title4js');

    for (let i = 0; i < news.length; i++) {
        news[i].addEventListener('mouseover', function () {
            news[i].style.filter = 'brightness(0.95)';
            news[i].style.cursor = 'pointer';
        })
    }

    for (let i = 0; i < news.length; i++) {
        news[i].addEventListener('mouseout', function () {
            news[i].style.filter = 'brightness(1)';
            news[i].style.cursor = 'default';
        })
    }

    for (let i = 0; i < news.length; i++) {
        news[i].addEventListener('click', function () {
            location.replace('?id=' + news[i].firstElementChild.value)
        })
    }

    for (let i = 0; i < del.length; i++) {
        del[i].addEventListener('click', function (e) {
            let response = confirm('⚠\n\n' + 'La news "' + title[i].value + '" va être supprimer.' +
                '\n\nVoulez-vous vraiment la supprimer (cette action est irreversible) ?');
            if (!response) {
                e.preventDefault();
            }
        })
    }
})()