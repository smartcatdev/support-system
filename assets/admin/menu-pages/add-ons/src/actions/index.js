export const ActionTypes = {
    UPDATE_LICENSE: 'UPDATE_LICENSE'
}

export const updateLicense = (id, license) => {
    return {
        type: ActionTypes.UPDATE_LICENSE, id, license
    }
}