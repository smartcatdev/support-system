
import PropTypes from 'prop-types'
import strings from '../localize'
import './Thumbnail.scss'

const Thumbnail = ({ src, draft }) => {
    return (
        <div className="thumbnail">
            { draft 
                ? <div className="inner">
                    <div className="border-triangle" /><div className="banner-text">{ strings.coming_soon }</div>
                    </div>
                : ''
            }
            <img src={ src } />
        </div>
    )
}

Thumbnail.propTypes = {
    src: PropTypes.string.isRequired
}

export default Thumbnail