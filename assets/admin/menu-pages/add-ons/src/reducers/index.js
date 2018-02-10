import { ActionTypes } from './../actions'

export default (state = [], action) => {
    switch (action.type) {
        case ActionTypes.UPDATE_LICENSE_KEY:
            return state.map(license => {
                if (license.id === action.id) {
                    return { ...license, key: action.key }
                }
                return license
            })

        case ActionTypes.ACTIVATE_EXTENSION:
            return state.map(license => {
                if (license.id === action.id) {
                    return { ...license, status: 'valid' }
                }
                return license
            })

        case ActionTypes.DEACTIVATE_EXTENSION:
            return state.map(license => {
                if (license.id === action.id) {
                    return { ...license, status: '' }
                }
                return license
            })

        default:
            return state
    }
}