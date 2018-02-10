import License from '../components/License'
import { activateExtension, deactivateExtension, updateLicenseKey } from '../actions'

const mapDispatchToProps = (dispatch, { license }) => {
    return {
        onKeyChange: ({ target: { value }}) => dispatch(updateLicenseKey(license.id, value)),

        onActivate: () => alert(`activate ${license.id}`),

        onDeactivate: () => alert(`deactivate ${license.id}`)
    }
}

export default ReactRedux.connect(null, mapDispatchToProps)(License)