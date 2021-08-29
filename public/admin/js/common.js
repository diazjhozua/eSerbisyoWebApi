function sample() {
    console.log('public function')
}

async function apiCall (method, url, data) {
    baseURL = 'http://127.0.0.1:8000/api/'
    api = baseURL + url
    try {
        return await $.ajax({
            method: method,
            url: api,
            data: data,
            dataType: 'json',
        })
    } catch (e) {
        console.log(e)
    }


}


