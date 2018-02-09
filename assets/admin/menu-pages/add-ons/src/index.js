import ExtensionList from './component/ExtensionList'
import Reducers from './reducer'
import { loadExtensions } from './actions'
import { extensions } from './data'

const store = Redux.createStore(Reducers, { extensions })

ReactDOM.render(
    <ReactRedux.Provider store={ store }>
        <ExtensionList extensions={ extensions } />
    </ReactRedux.Provider>,
    document.querySelector('#ucare-add-ons')
)