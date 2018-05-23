import { ActionTypes } from './../actions'

export default (state = [], action) => {
    switch (action.type) {
        case ActionTypes.UPDATE_LICENSE:
            return state.map(license => {
                if (license.id === action.id) {
                    return { ...license, ...action.license }
                }
                return license
            })

        default:
            return state
    }
}