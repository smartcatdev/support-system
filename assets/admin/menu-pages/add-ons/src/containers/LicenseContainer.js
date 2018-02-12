import License from '../components/License'
import { manageExtension } from '../utils'
import { activateExtension, deactivateExtension, updateLicenseKey } from '../actions'

const mapDispatchToProps = (dispatch, { license }) => {
    return {
        onKeyChange: ({ target: { value }}) => dispatch(updateLicenseKey(license.id, value)),

        onActivate: () => {
            manageExtension(license.id, 'activate', { key: license.key })
                .then(() => {
                    dispatch(activateExtension( license.id ))
                })
        },

        onDeactivate: () => {
            manageExtension(license.id, 'deactivate')
                .then(() => {
                    dispatch(deactivateExtension( license.id ))
                })
        }
    }
}

export default ReactRedux.connect(null, mapDispatchToProps)(License) 