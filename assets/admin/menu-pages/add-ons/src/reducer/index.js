import { ActionTypes } from './../actions'

const extensions = (state = [], action) => {
    switch (action.type) {
        case ActionTypes.LOAD_EXTENSIONS:
          state = action.extensions
    }

    return state
}

export default Redux.combineReducers({ extensions })
