import License from '../components/License'
import { manageExtension } from '../utils'
import { activateExtension, deactivateExtension, updateLicenseKey } from '../actions'

const mapDispatchToProps = (dispatch, { license }) => {
    return {
        onKeyChange: ({ target: { value }}) => dispatch(updateLicenseKey(license.id, value)),

        onActivate: () => {
            manageExtension(license.id, 'activate', { key: license.key })
                .then(data => {
                    dispatch(activateExtension(license.id, data))
                })
        },

        onDeactivate: () => {
            manageExtension(license.id, 'deactivate')
                .then(data => {
                    dispatch(deactivateExtension(license.id, data))
                })
        }
    }
}

export default ReactRedux.connect(null, mapDispatchToProps)(License) 