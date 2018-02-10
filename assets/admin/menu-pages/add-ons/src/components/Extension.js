import PropTypes from 'prop-types'
import Thumbnail from './Thumbnail'
import LicenseManager from '../containers/LicenseManager'
import decode from 'unescape'
import './Extension.scss'

const Extension = ({ license, extension }) => {
    return (
        <li className="extension">
            <Thumbnail src={ extension.thumbnail } />
            <div className="info">
                <h3 className="title">
                    { decode(extension.title) } 
                </h3>
                <p className="excerpt">{ excerpt }</p>
            </div>
            <div className="actions">
                <button className="button-primary">Get Add-on</button>
            </div>
            <div className="clear" />
            { license && !extension['pro_add-on'] ? <LicenseManager license={ license } /> : '' }
        </li>
    )   
}

Extension.propTypes = {
    extension: PropTypes.object.isRequired
}

const mapStateToProps = (state, ownProps) => {
    return {
        license: state.find(license => license.item_name === ownProps.extension.title)
    }
}

export default ReactRedux.connect(mapStateToProps)(Extension)

const excerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel odi auctor  rutrum ante eu ex ultrices rhoncus. Quisque convallis ut sapien ac hendrerit. Phasellus mollis finibus erat, ut auctor felis hendrerit in. Aenean accumsan vestibulum nibh at maximus. Nam rutrum nisi id libero rutrum eleifend. Morbi consequat nulla in mi feugiat tincidunt. Donec vestibulum risus nec lectus molestie dignissim. Donec quam ex, porta sed commodo vitae, pharetra quis ligula. Curabitur pretium ex sapien, et ulla.'