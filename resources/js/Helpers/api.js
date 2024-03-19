import axios from "axios"

function sendPostRequest(path, payload)
{
    return axios.post(path, payload)
}

function sendGet(path, query_params = []) {
    return axios.get("/api/v1/" + path, {
        params: query_params
    })
}

export {sendPostRequest, sendGet}
