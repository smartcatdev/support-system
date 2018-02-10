import strings from '../localize'

const License = ({ license, onKeyChange, onActivate, onDeactivate }) => {
    return (
        <div>
            <label>
                <span className="label">{ strings.license }</span>
                <input type="text" value={ license.key } onChange={ onKeyChange } disabled={ license.status === 'valid' } />
            </label>
            { license.status === 'valid' 
                ?  <button onClick={ onDeactivate }>{ strings.deactivate }</button>
                :  <button onClick={ onActivate } disabled={ license.key.length === 0 }>{ strings.activate }</button>
            }
        </div>
    )
}

export default License