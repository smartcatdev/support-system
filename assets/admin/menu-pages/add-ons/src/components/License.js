import strings from '../localize'

const License = ({ license, onActivate, onDeactivate }) => {
    return (
        <div>
            <label>
                { strings.license }
                <input type="text" value={ license.key } />
            </label>
        </div>
    )
}

export default License