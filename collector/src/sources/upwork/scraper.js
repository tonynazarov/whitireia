function range(start, end) {
    if (start === end) return [start];
    return [start, ...range(start + 1, end)];
}

(function (console) {

    console.save = function (data, filename) {

        if (!data) {
            console.error('Console.save: No data')
            return;
        }
        if (!filename) filename = 'console.json'
        if (typeof data === "object") {
            data = JSON.stringify(data, undefined, 4)
        }
        var blob = new Blob([data], {type: 'text/json'}),
            a = document.createElement('a')
        var e = new MouseEvent('click', {
            view: window,
            bubbles: true,
            cancelable: false
        });

        a.download = filename
        a.href = window.URL.createObjectURL(blob)
        a.dataset.downloadurl = ['text/json', a.download, a.href].join(':')
        a.dispatchEvent(e)
    }
})(console)

function getTotalJobs() {
    return parseInt($('span[data-test=jobs-count] strong').innerText.replace(',', ''));
}

function getTotalPages() {
    return Math.ceil(getTotalJobs() / 100)
}

async function save(element) {
    const url = "https://www.upwork.com/search/jobs/url?q=chatgpt&per_page=100&sort=recency&page=".concat(element)
    console.log('Run page: ' + element + ': ' + url)

    const response = await fetch(url, {
        "headers": {
            "accept": "application/json, text/plain, */*",
            "accept-language": "en,en-US;q=0.9",
            "sec-ch-ua": "\"Google Chrome\";v=\"119\", \"Chromium\";v=\"119\", \"Not?A_Brand\";v=\"24\"",
            "sec-ch-ua-full-version-list": "\"Google Chrome\";v=\"119.0.6045.105\", \"Chromium\";v=\"119.0.6045.105\", \"Not?A_Brand\";v=\"24.0.0.0\"",
            "sec-ch-ua-mobile": "?0",
            "sec-ch-ua-platform": "\"macOS\"",
            "sec-ch-viewport-width": "1597",
            "sec-fetch-dest": "empty",
            "sec-fetch-mode": "cors",
            "sec-fetch-site": "same-origin",
            "vnd-eo-parent-span-id": "06cce513-a51e-412d-ac17-7c3ce49fed2a",
            "vnd-eo-span-id": "28088087-562a-47f8-8beb-9e317ca5888c",
            "vnd-eo-trace-id": "82000b50ea9e50a4-AKL",
            "x-odesk-user-agent": "oDesk LM",
            "x-requested-with": "XMLHttpRequest",
            "x-upwork-accept-language": "en-US"
        },
        "referrer": "https://www.upwork.com/nx/jobs/search/?q=chatgpt&sort=recency&page=" + element + "&per_page=100",
        "referrerPolicy": "origin-when-cross-origin",
        "body": null,
        "method": "GET",
        "mode": "cors",
        "credentials": "include"
    });

    const jso = await response.json();

    let d = Date.now()
    console.save(jso, jso.searchGuid + '.json')

    console.log('done');
}

function main() {
    let total = getTotalPages();

    for (let i=1; i <= total; i++) {
            save(i)
    }
}