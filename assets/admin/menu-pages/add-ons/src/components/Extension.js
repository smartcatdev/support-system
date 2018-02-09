import PropTypes from 'prop-types'
import Thumbnail from './Thumbnail'
import LicenseStatus from './LicenseStatus'
import decode from 'unescape'
import './Extension.scss'

const Extension = ({ extension }) => {
    return (
        <li className="extension">
            <Thumbnail src={ extension.thumbnail } />
            <div className="info">
                <h3 className="title">
                    { decode(extension.title) } 
                    <LicenseStatus  />
                </h3>
                <p className="excerpt">{ excerpt }</p>
            </div>
            <div className="actions">
                <button className="button-primary">Get Add-on</button>
            </div>
            <div className="clear" />
        </li>
    )
}

Extension.propTypes = {
    extension: PropTypes.object.isRequired
}

export default Extension

const excerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel odi auctor  rutrum ante eu ex ultrices rhoncus. Quisque convallis ut sapien ac hendrerit. Phasellus mollis finibus erat, ut auctor felis hendrerit in. Aenean accumsan vestibulum nibh at maximus. Nam rutrum nisi id libero rutrum eleifend. Morbi consequat nulla in mi feugiat tincidunt. Donec vestibulum risus nec lectus molestie dignissim. Donec quam ex, porta sed commodo vitae, pharetra quis ligula. Curabitur pretium ex sapien, et ulla.'