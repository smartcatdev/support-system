import PropTypes from 'prop-types'
import './LicenseStatus.scss'

const LicenseStatus = ({ status }) => {
    let valid = status === 'valid'

    return (
        <span className={ `license-status ${ valid ? 'ok': 'not-ok'}` }>
            { valid 
                ? <span className="dashicons dashicons-yes" /> 
                : <span className="dashicons dashicons-warning" /> 
            }
        </span>
    )
}

LicenseStatus.propTypes = {
    status: PropTypes.string.isRequired
}

export default LicenseStatus