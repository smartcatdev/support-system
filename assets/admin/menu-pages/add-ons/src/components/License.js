import strings from '../localize'
import './License.scss'

const License = ({ license, onKeyChange, onActivate, onDeactivate }) => {
    return (
        <div className="product-license">
            <label>
                <span className="label">{ strings.license }</span>
                <input type="text" className="key" value={ license.key } onChange={ onKeyChange } disabled={ license.status === 'valid' } />
            </label>
            <div className="manage">
                { license.status === 'valid' 
                    ?  <button className="deactivate button" onClick={ onDeactivate }>{ strings.deactivate }</button>
                    :  <button className="activate button-primary" onClick={ onActivate } disabled={ license.key.length === 0 }>{ strings.activate }</button>
                }
                <button className="button renew">{ strings.renew }</button>
            </div>
        </div>
    )
}

export default License