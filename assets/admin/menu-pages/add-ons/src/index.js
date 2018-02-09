import Root from './component/Root'
import Reducers from './reducer'
import { loadExtensions } from './actions'
import { DOWNLOADS_URL } from './data'

const store = Redux.createStore(Reducers)

fetch(DOWNLOADS_URL).then(
    res => res.json().then(json => store.dispatch(loadExtensions(json)))
)

ReactDOM.render(
    <ReactRedux.Provider store={ store }>
        <Root />
    </ReactRedux.Provider>,
    document.querySelector('#ucare-add-ons')
)