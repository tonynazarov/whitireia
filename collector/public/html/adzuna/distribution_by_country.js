(async function () {
    const data_all = {
        labels: transform_countries([
            'nz', 'be', 'za', 'mx', 'ch', 'at', 'au', 'pl', 'br', 'es', 'ca', 'fr', 'sg', 'nl', 'de', 'uk', 'in', 'us'
        ]),
        datasets: [{
            label: 'Jobs amount on 02.10.2023',
            data: [
                1, 2, 3, 3, 4, 5, 7, 12, 17, 18, 25, 26, 26, 28, 62, 71, 130, 621,
            ],
            hoverOffset: 4
        }]
    };


    new Chart(
        document.getElementById('all'),
        {type: 'pie', data: data_all}
    );

})();
