import { rest_url, wp_nonce } from './data'
import queryString from 'query-string'

export const manageExtension = (id, data) => {
    return fetch(`${rest_url}/${id}?${queryString.stringify(data)}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({ 
            'X-WP-Nonce': wp_nonce 
        })
    })
    .then(res => {
        if (!res.ok) {
            throw Error(res.json())
        }

        return res
    })
}