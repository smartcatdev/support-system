import ExtensionList from './components/ExtensionList'
import Reducers from './reducers'
import { loadExtensions } from './actions'
import { extensions } from './data'

const store = Redux.createStore(Reducers, { extensions })

ReactDOM.render(
    <ReactRedux.Provider store={ store }>
        <ExtensionList extensions={ extensions } />
    </ReactRedux.Provider>,
    document.querySelector('#ucare-add-ons')
)