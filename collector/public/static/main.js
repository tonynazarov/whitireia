//import Chart from 'chart.js/auto'

const transform_countries = function (country_list) {
    let list = []
    country_list.forEach(function (key, index) {
        list.push(countries[key])
    })

    return list
}
