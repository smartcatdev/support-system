import ExtensionContainer from '../containers/ExtensionContainer'
import PropTypes from 'prop-types'
import './ExtensionList.scss'

const ExtensionList = ({ extensions }) => {
    return (
        <ul className="extensions">
            { extensions.map(extension => <ExtensionContainer extension={ extension } />) }
        </ul>
    )
}

ExtensionList.propTypes = {
    extensions: PropTypes.array.isRequired
}

export default ExtensionList