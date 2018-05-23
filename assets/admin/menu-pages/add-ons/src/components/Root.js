import ExtensionList from './ExtensionList'
import { extensions } from '../data'
import strings from '../localize'
import './Root.scss'

export default () => {
    return (
        <ExtensionList extensions={ extensions } />
    )
}