import Extension from './Extension'
import PropTypes from 'prop-types'
import './ExtensionList.scss'

const ExtensionList = ({ extensions }) => {
    return (
        <div className="extensions">
            { extensions.map(extension => <Extension extension={ extension } />) }
        </div>
    )
}

Extension.propTypes = {
    extensions: PropTypes.array.isRequired
}

export default ExtensionList