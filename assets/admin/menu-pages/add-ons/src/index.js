import Root from './components/Root'
import Reducers from './reducers'
import { licenses } from './data'
import './style.scss'

const store = Redux.createStore(Reducers, licenses)

ReactDOM.render(
    <ReactRedux.Provider store={ store }>
        <Root />
    </ReactRedux.Provider>,
    document.querySelector('#ucare-add-ons')
)