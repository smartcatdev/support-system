import License from '../components/License'
import { manageExtension } from '../utils'
import { activateExtension, deactivateExtension, updateLicenseKey, updateLicense } from '../actions'

const mapDispatchToProps = (dispatch, { license }) => {
    const dispatchUpdate = (id, action, license = {}) => {
        dispatch(updateLicense(id, { action_pending: true, last_error: '' })) // Append pending status to license
        manageExtension(id, action, license)
            .then(data => {
                dispatch(updateLicense(id, { ...data, action_pending: false }))
            })
            .catch(err => dispatch(updateLicense(id, { last_error: err.message, action_pending: false })))
    }

    return {
        onKeyChange: ({ target: { value }}) => dispatch(updateLicense(license.id, { key: value })),

        onActivate: () => dispatchUpdate(license.id, 'activate', { key: license.key }),

        onDeactivate: () => dispatchUpdate(license.id, 'deactivate')
    }
}

export default ReactRedux.connect(null, mapDispatchToProps)(License) 