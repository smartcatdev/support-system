export const loadExtensions = (extensions) => {
    return {
        type: 'LOAD_EXTENSIONS',
        extensions: extensions
    }
}

export const ActionTypes = {
    LOAD_EXTENSIONS: 'LOAD_EXTENSIONS'
}
