
import PropTypes from 'prop-types'
import './Thumbnail.scss'

const Thumbnail = ({ src }) => {
    return (
        <div className="thumbnail">
            <img src={ src } />
        </div>
    )
}

Thumbnail.propTypes = {
    src: PropTypes.string.isRequired
}

export default Thumbnail