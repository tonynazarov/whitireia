(async function () {
    const data = {
        labels: ['pa', 'cs', 'cr', 'ng', 'el', 'co', 'en', 'dk', 'lu', 'ma', 'pe', 'it', 'tr', 'gr', 'ae', 'th', 'kz', 'ie', 'id', 'kr', 'no', 'pk', 'cl', 'ro', 'vn', 'il', 'at', 'ua', 'cn', 'mx', 'za', 'tw', 'hu', 'ar', 'nz', 'cz', 'pt', 'ch', 'se', 'fi', 'be', 'my', 'au', 'ru', 'pl', 'hk', 'es', 'ph', 'sg', 'ca', 'br', 'nl', 'uk', 'fr', 'in', 'de', 'sv', 'jp', 'us',],
        datasets: [{
            label: 'My First Dataset',
            data: [1, 1, 1, 1, 1, 3, 4, 4, 4, 4, 5, 5, 6, 6, 7, 8, 8, 11, 11, 12, 12, 13, 14, 14, 15, 17, 18, 19, 20, 21, 21, 22, 22, 25, 26, 26, 27, 28, 29, 32, 33, 63, 71, 72, 72, 84, 88, 131, 135, 148, 152, 209, 231, 310, 402, 534, 569, 1913, 2823],
            hoverOffset: 4
        }]
    };


    new Chart(
        document.getElementById('a'),
        {type: 'pie', data: data}
    );
})();