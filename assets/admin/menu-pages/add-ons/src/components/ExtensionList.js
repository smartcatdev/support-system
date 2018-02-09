import Extension from './Extension'
import PropTypes from 'prop-types'
import './ExtensionList.scss'

const ExtensionList = ({ extensions }) => {
    return (
        <ul className="extensions">
            { extensions.map(extension => <Extension extension={ extension } />) }
        </ul>
    )
}

Extension.propTypes = {
    extensions: PropTypes.array.isRequired
}

export default ExtensionList