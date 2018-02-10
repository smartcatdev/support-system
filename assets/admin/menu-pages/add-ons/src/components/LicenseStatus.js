import PropTypes from 'prop-types'

const LicenseStatus = ({ status }) => {
    return status === 'valid' ? ' ok' : ' not ok'
}

LicenseStatus.propTypes = {
    status: PropTypes.string.isRequired
}

export default LicenseStatus