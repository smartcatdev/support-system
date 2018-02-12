export const ActionTypes = {
    ACTIVATE_EXTENSION: 'ACTIVATE_EXTESNION',
    DEACTIVATE_EXTENSION: 'DEACTIVATE_EXTENSION',
    UPDATE_LICENSE_KEY: 'UPDATE_LICENSE_KEY'
}

export const activateExtension = (id, license) => {
    return {
        type: ActionTypes.ACTIVATE_EXTENSION, id, license
    }
}

export const deactivateExtension = (id, license) => {
    return {
        type: ActionTypes.DEACTIVATE_EXTENSION, id, license
    }
}

export const updateLicenseKey = (id, key) => {
    return {
        type: ActionTypes.UPDATE_LICENSE_KEY, id, key
    }
}