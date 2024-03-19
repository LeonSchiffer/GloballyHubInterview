import {toast} from "vue3-toastify"

function toastSuccess(message, duration_in_milliseconds = 3000)
{
    toast.success(message, {
        autoClose: duration_in_milliseconds
    })
}

function toastError(message, duration_in_milliseconds = 3000)
{
    toast.error(message, {
        autoClose: duration_in_milliseconds
    })
}

export {toastError, toastSuccess}
